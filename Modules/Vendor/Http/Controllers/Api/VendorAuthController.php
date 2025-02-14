<?php

namespace Modules\Vendor\Http\Controllers\Api;

use App\Mail\BasicMail;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\MobileApp\Http\Services\Api\UserServices;
use Modules\User\Entities\User;
use Modules\Vendor\Entities\BusinessType;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Http\Requests\VendorRegistrationRequest;
use Modules\Wallet\Entities\Wallet;

class VendorAuthController extends Controller
{
    public function businessType(){
        return BusinessType::all();
    }
    public function register(VendorRegistrationRequest $request){
        // store validated data into a temporary variable
        $data = $request->validated();
        // now change password value and make it hash
        $data["password"] = \Hash::make($data['password']);

        // now create vendor
        $vendor = Vendor::create($data);
        // after creating vendor now need to create wallet
        if($vendor){
            Wallet::create([
                'user_id',
                'vendor_id' => $vendor->id,
                'balance' => 0,
                'pending_balance' => 0,
                'status' => 0
            ]);
        }

        return response()->json([
            "msg" => $vendor ? __("Registration success") : __("Registration failed"),
            "status" => (bool) $vendor
        ]);
    }
    public function login(Request $request) {
        // First validate
        $req = $request->validate([
            "username" => "required",
            "password" => "min:6",
        ]);

        // Set login type
        $user_login_type = 'username';
        if(filter_var($request->username,FILTER_VALIDATE_EMAIL)){
            $user_login_type = 'email';
        }

        $vendor = Vendor::where($user_login_type, $request->username)->first();

        if (!$vendor || !Hash::check($req["password"], $vendor->password)) {
            return response()->json([
                'message' => __('Invalid ' . $user_login_type . ' or Password')
            ])->setStatusCode(422);
        }

        $token = $vendor->createToken(Str::slug(get_static_option('site_title', 'safecart') . '-vendor') . 'api_keys')->plainTextToken;


        return response()->json([
            'vendor' => $vendor,
            'token' => $token,
        ]);


        $validate = UserServices::validateLoginRequest($request->all());

        if ($validate->fails()){
            return UserServices::validationErrorsResponse($validate);
        }

        $validated = $validate->validated();

        // Set login type
        $user_login_type = UserServices::loginUserType($validated["username"]);

        if($user_login_type == 'email' && !UserServices::isValideEmail($validated["username"])){
            return UserServices::emailValidationResponse();
        }

        $user = User::select('id', 'password', $user_login_type,'email_verified')
            ->where($user_login_type, $validated["username"])->first();
        if (!$user || !Hash::check($validated["password"], $user->password)) {
            return response()->json([
                'message' => __('Invalid ' . $user_login_type . ' or Password')
            ])->setStatusCode(422);
        }

        $token = $user->createToken(Str::slug(get_static_option('site_title', 'safecart')) . 'api_keys')->plainTextToken;

        return response()->json([
            'users' => $user,
            'token' => $token,
        ]);
    }

    public function profile(){
        $vendor = Vendor::with(["vendor_address","vendor_address.country:id,name","vendor_address.state:id,name","vendor_address.city:id,name","vendor_shop_info","business_type","vendor_bank_info"])
            ->where('username', auth('sanctum')->user()?->username)->first();

        $vendor->logo = render_image($vendor->vendor_shop_info?->logo, render_type: 'path');
        $vendor->cover_photo = render_image($vendor->vendor_shop_info?->cover_photo, render_type: 'path');

        $options = [];

        $businessType = BusinessType::select("id","name")->get();
        $options["options"]["business_types"] = $businessType;
        $options["vendor_info"] = $vendor;

        return $options;
    }

    public function logout(){
        auth("sanctum")->user()->tokens()->delete();

        return response()->json([
            'message' => __('Logout Success'),
        ]);
    }


    public function user_email_verify_index()
    {
        $user_details = Auth::guard('sanctum')->user();

        if ($user_details->email_verified == 1) {
            return response()->json([
                "msg" => __("Already validated")
            ]);
        }

        if (empty($user_details->email_verify_token)) {
            Vendor::find($user_details->id)->update(['email_verify_token' => \Str::random(8)]);

            $user_details = Vendor::find($user_details->id);
            $message_body = __('Here is your verification code') . ' <span class="verify-code">' . $user_details->email_verify_token . '</span>';

            try {
                Mail::to($user_details->email)->send(new BasicMail([
                    'subject' => __('Verify your email address'),
                    'message' => $message_body
                ]));
            } catch (\Exception $e) {

            }
        }

        return response()->json([
            "msg" => __("Successfully otp token is sent."),
            "token" => $user_details->email_verify_token
        ]);
    }


    public function user_email_verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required'
        ], [
            'verification_code.required' => __('Verify code is required')
        ]);

        $user_details = Auth::guard('sanctum')->user();
        $user_info = Vendor::where(['id' => $user_details->id, 'email_verify_token' => $request->verification_code])->first();

        if (empty($user_info)) {
            return response()->json(['msg' => __('Your verification code is wrong, try again'), 'type' => 'danger']);
        }

        if($user_info->email_verified == 1){
            return response()->json([
                "msg" => __("Already verified this user")
            ])->setStatusCode(422);
        }

        $user_info->email_verified = 1;
        $user_info->save();

        return response()->json([
            "msg" => __("Vendor verification is successful")
        ]);
    }

    public function sendOTP(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required',
        ]);

        if ($validate->fails()){

            return response()->json([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }
        $otp_code = sprintf("%d", random_int(123411, 999999));
        $user_email = Vendor::where('email', $request->email)->first();

        if (!is_null($user_email)) {
            try {
                $message_body = __('Here is your otp code') . ' <span class="verify-code">' . $otp_code . '</span>';
                Mail::to($request->email)->send(new BasicMail([
                    'subject' => __('Your OTP Code'),
                    'message' => $message_body
                ]));
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                ])->setStatusCode(422);
            }

            return response()->json([
                'email' => $request->email,
                'otp' => $otp_code,
            ]);

        }

        return response()->json([
            'message' => __('Email Does not Exists'),
        ])->setStatusCode(422);
    }

    public function resetPassword(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }
        $email = $request->email;
        $user = Vendor::select('email')->where('email', $email)->first();
        if (!is_null($user)) {
            Vendor::where('email', $user->email)->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'message' => __('Email Not Found'),
            ])->setStatusCode(422);
        }
    }

    public function deleteAccount(){
        $auth = auth("sanctum")->user();

        $getPwd = request()->password;
        if(Hash::check($getPwd,$auth->password)){
            auth("sanctum")->user()->tokens()->delete();

            $vendor = Vendor::where("id", $auth->id)->delete();

            return response()->json([
                "msg" => $vendor ? __("Successfully deleted account") : __("Failed to delete your account")
            ]);
        }

        return response()->json([
            "msg" => __("Password are not matched.")
        ]);
    }
}