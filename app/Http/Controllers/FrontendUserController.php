<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Http\Middleware\CustomServiceByDc;
use Carbon\Carbon;
use App\Mail\UserEmailVerify;
use App\Mail\BasicMail;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\CustomerVerification;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;
use Modules\Wallet\Http\Services\WalletService;
use App\Shipping\ShippingAddress;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\SubOrder;
use Modules\CountryManage\Entities\State;
use Modules\CountryManage\Entities\Country;




class FrontendUserController extends Controller
{

    public function __construct()
    {
        // Alternatively, you can specify the methods you want the middleware to apply to
        $this->middleware('auth')->only(['showMyProfile', 'profileUpdate', 'submitSignOut', 'addAddress', 'editAddress', 'deleteAddress', 'orderDetails']);
    }


    protected function customerValidator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:50']
        ], [
            'password.confirmed' => __('both password does not matched'),
            'first_name.required' => __('First name is required'),
            'last_name.required' => __('Last name is required'),
            'username.required' => __('username is required'),
            'username.unique' => __('username is already taken'),
            'email.unique' => __('email is already taken'),
            'email.required' => __('email is required'),
        ]);
    }

    protected function customerVerificationValidator(array $data)
    {
        return Validator::make($data, [
            'entity_value' => ['required'],
        ], [
            'entity_value.required' => __('Email/Phone is required'),
        ]);
    }

    protected function checkVerificationCodeValidator(array $data)
    {
        return Validator::make($data, [
            'entity_value' => ['required', 'min:5'],
            'otp_code' => ['required', 'max:6', 'min:6'],
        ], [
            'entity_value.required' => __('Email/Phone is required'),
            'otp_code.required' => __('OTP is required'),
            'otp_code.max' => __('The Verification Code must not exceed :max characters.'),
            'otp_code.min' => __('The Verification Code must be at least :min characters.'),
            'entity_value.min' => __('The Email/Phone must be at least :min characters.'),
        ]);
    }

    protected function identifyInputType($input)
    {
        // Email regex pattern
        $emailPattern = '/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/';

        // Phone number regex pattern
        $phonePattern = '/^(?:\+?88)?\d{11}(?:(?<=\+88)\d{3})?$/';

        // Check if input matches email pattern
        if (preg_match($emailPattern, $input)) {
            return 'email';
        }

        // Check if input matches phone number pattern
        if (preg_match($phonePattern, $input)) {
            return 'phone';
        }

        // Neither email nor phone
        return 'unknown';
    }


    public function showRegistrationForm()
    {
        return view('muslin.phone-verification');
    }


    /**
     * Generate OTP for customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function createCustomerPhoneVerification(Request $request)
    {
        $validator = $this->customerVerificationValidator($request->all());

        if ($validator->fails()) {

            $response['type'] = 'error';
            $response['msg'] = $validator->errors()->first();
        } 
        else {

            $requestEntityType = $this->identifyInputType($request['entity_value']);

            $existingUser = User::where(function($query) use ($request) {
                                    $query->where('username', $request['entity_value'])
                                          ->orWhere('email', $request['entity_value'])
                                          ->orWhere('phone', $request['entity_value']);
                                })
                                ->first();

            if($existingUser) {

                $response['type'] = 'error';
                $response['msg'] = 'Email Address/Phone Number is already taken';

                return response()->json($response);
            }


            if ($requestEntityType !== 'unknown') {

                $customerVerification = CustomerVerification::where('entity_type', $requestEntityType)
                                                            ->where('entity_value', $request['entity_value'])
                                                            ->where('type', 1)
                                                            ->where('verified_status', 0)
                                                            ->where('expired', '>', Carbon::now())
                                                            ->orderBy('created_at')
                                                            ->first();

                if ($customerVerification == null) {

                    $otpCode = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

                    if ($requestEntityType == 'phone') {

                        $messageBody = 'Your verification code is:' . $otpCode . ' Please do not share it with others';
                        $toPhone = $request['entity_value'];

                        $middleware = app()->make(CustomServiceByDc::class);
                        $response = $middleware->handle($request, function ($request) {
                            $curlResponse = $request->attributes->get('curlResponse');
                        }, $messageBody, $toPhone);

                    }
                    else if($requestEntityType == 'email') {

                        $messageBody = 'Your account verification code is: <strong>' . $otpCode . '</strong> . Please do not share it with others';
                        
                        Mail::to($request['entity_value'])->send(new UserEmailVerify($messageBody));
                    }


                    $customer = CustomerVerification::create([
                        'type' => 1,
                        'entity_type' => $requestEntityType,
                        'entity_value' => $request['entity_value'],
                        'verification_code' => $otpCode,
                        'verified_status' => 0,
                        'expired' => date('Y-m-d H:i:s', strtotime("+10 minutes")),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $response['type'] = 'success';
                    $response['msg'] = 'Verification code sent to your ' . $requestEntityType;

                } else {

                    $response['type'] = 'error';
                    $response['msg'] = 'Next OTP will be send after 10 minutes';
                }

            } else {

                $response['type'] = 'error';
                $response['msg'] = 'Email Address/Phone Number pattern not matched';
            }
        }

        return response()->json($response);
    }


    public function checkCustomerPhoneVerification(Request $request)
    {
        $validator = $this->checkVerificationCodeValidator($request->all());

        if ($validator->fails()) {
            $errorMessages = implode(' & ', $validator->errors()->all());
            return redirect()->route('phone-verification')->with('validation-error', $errorMessages);

        } else {

            $request->session()->put('username', $request->entity_value);
            $request->session()->put('otp_code', $request->otp_code);

            $today = Carbon::today()->toDateString();
            $verifiedCustomer = CustomerVerification::where('entity_value', $request->entity_value)
                ->where('verification_code', $request->otp_code)
                ->whereDate('created_at', $today)
                ->get();

            if ($verifiedCustomer && $request->session()->has('username') && $request->session()->has('otp_code')) {
                return redirect()->route('account-setup');
            }
        }

        return redirect()->route('registration');
    }

    public function showAfterVerificationForm(Request $request)
    {
        if ($request->session()->has('username') && $request->session()->has('otp_code')) {

            $requestEntityType = $this->identifyInputType($request->session()->get('username'));

            $all_country = Country::where('status', 'publish')->get()->toArray();   
            $all_country = array_column($all_country, 'name', 'id');


            return view('muslin.registration', compact('requestEntityType', 'all_country'));

        }

        return redirect()->route('registration');

    }
    

    public function submitRegistrationForm(Request $request)
    {
        $validator = $this->customerValidator($request->all());

        if ($validator->fails()) {

            return redirect('account-setup')->with('error', $validator->errors()->first())->withInput();

        } else {

            $today = Carbon::today()->toDateString();

            $verifiedCustomer = CustomerVerification::where('entity_value', $request->entity_value)
                ->where('verification_code', $request->otp_code)
                ->whereDate('created_at', $today)
                ->get();

            if ($verifiedCustomer) {

                $user = User::create([

                    'username' => $request->username,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'zipcode' => $request->zip_code,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'password' => Hash::make($request->password)
                ]);

                if($user) {

                    $address = ShippingAddress::create([
                            'user_id' => $user->id,
                            'name' => $request->first_name . ' ' . $request->last_name,
                            'email' => $request->email,
                            'phone' => $request->phone,
                            'country_id' => $request->country,
                            'state_id' => $request->state,
                            'city' => $request->city,
                            'zip_code' => $request->zip_code,
                            'address' => $request->address,
                            'default_shipping_status' => 1
                        ]);

                    session()->forget('username');
                    session()->forget('otp_code');


                    if(!empty($request->email)) {


                        $data['subject'] = __('Welcome to The Muslin');
                        $data['message'] = __('Dear').' '.$request->first_name . ' ' . $request->last_name.', <br><br>';
                        $data['message'] .= __('Welcome to The Muslin! Your account is all set up and ready to go—start exploring now at <a href="http://themuslinbd.com/"> The Muslin</a>. Enjoy your journey with us! ');
                        
                        Mail::to($request->email)->send(new BasicMail($data));

                    }

                    return redirect('sign-in')->with('success', 'Your account has been created. Please login');
                }
            }
        }
    }


    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect('my-profile');
        } else {
            return view('muslin.login');
        }
    }


    public function showLoginFormSubmit(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:8',
        ], [
            'username.required' => __('username required'),
            'password.required' => __('password required'),
            'password.min' => __('password length must be 8 characters'),
        ]);

        $login_key = 'username';
        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $login_key = 'email';
        }

        $user_data = array(
            'username' => $request->get('username'),
            'password' => $request->get('password')
        );

        if (Auth::attempt($user_data)) {
            return redirect('/');

        } 
        else {
            return back()->with('error', 'Wrong Username or Password');
        }
    }


    public function showMyProfile()
    {
        if(Auth::check()){
            $userData = User::find(Auth::id());

            $all_shipping_address = ShippingAddress::where('user_id', getUserByGuard('web')->id)->paginate(10);
            $all_orders = Order::with('paymentMeta')->withCount('isDelivered')
                                ->where('user_id', auth('web')->user()->id)
                                ->orderBy('id', 'DESC')
                                ->paginate(10);

            $all_country = Country::where('status', 'publish')->get()->toArray();   
            $all_country = array_column($all_country, 'name', 'id');

            return view('muslin.profile', compact('userData', 'all_shipping_address', 'all_orders', 'all_country'));

        } else {
            return redirect('sign-in');
        }
    }

    public function profileUpdate(Request $request)
    {
        $user = User::find(Auth::id());

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone
        ];

        $user->fill($data);

        if($user->save()) {

            $response['type'] = 'success';
            $response['msg'] = 'Profile updated';
        }
        else {
            $response['type'] = 'error';
            $response['msg'] = 'Something went wrong';   
        }
        
        return response()->json($response);
    }


    public function submitSignOut()
    {
        if (auth()->check()) {
            CartHelper::clear();
            Cart::instance('default')->destroy();
            Auth::logout();
        }
        return redirect('/');

    }


    public function showForgetPasswordForm()
    {
        return view('muslin.forget-password');
    }


    public function forgetPasswordSubmit(Request $request)
    {
        $validator = $this->customerVerificationValidator($request->all());

        if ($validator->fails()) {

            $response['type'] = 'error';
            $response['msg'] = $validator->errors()->first();

        } else {

            $userCheck = User::where(['username' => $request['entity_value']])->first();

            if($userCheck) {

                $requestEntityType = $this->identifyInputType($request['entity_value']);

                if ($requestEntityType !== 'unknown') {

                    $customerVerification = CustomerVerification::where('entity_type', $requestEntityType)
                                                                ->where('entity_value', $request['entity_value'])
                                                                ->where('type', 2)
                                                                ->where('verified_status', 0)
                                                                ->where('expired', '>', Carbon::now())
                                                                ->orderBy('created_at')
                                                                ->first();

                    if ($customerVerification == null) {

                        $otpCode = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

                        if ($requestEntityType == 'phone') {

                            $messageBody = 'Your verification code is:' . $otpCode . ' Please do not share it with others';
                            $toPhone = $request['entity_value'];

                            $middleware = app()->make(CustomServiceByDc::class);
                            $response = $middleware->handle($request, function ($request) {
                                $curlResponse = $request->attributes->get('curlResponse');
                            }, $messageBody, $toPhone);

                        }
                        else if($requestEntityType == 'email') {

                            $messageBody = 'Your account verification code is: <strong>' . $otpCode . ' </strong> Please do not share it with others';
                            
                            Mail::to($request['entity_value'])->send(new UserEmailVerify($messageBody));
                        }


                        $customer = CustomerVerification::create([
                            'type' => 2,
                            'entity_type' => $requestEntityType,
                            'entity_value' => $request['entity_value'],
                            'verification_code' => $otpCode,
                            'verified_status' => 0,
                            'expired' => date('Y-m-d H:i:s', strtotime("+10 minutes")),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $response['type'] = 'success';
                        $response['msg'] = 'Verification code send to your ' . $requestEntityType;

                    } else {

                        $response['type'] = 'error';
                        $response['msg'] = 'Next OTP will be send after 10 minutes';
                    }


                } else {

                    $response['type'] = 'error';
                    $response['msg'] = 'Email address /Phone Number pattern not matched';
                }

            } else {

                $response['type'] = 'error';
                $response['msg'] = 'No user found';
            }
        }

        return response()->json($response);
    }


    public function forgetPasswordOtpVerification(Request $request)
    {
        $validator = $this->checkVerificationCodeValidator($request->all());

        if ($validator->fails()) {

            $errorMessages = implode(' & ', $validator->errors()->all());

            return redirect()->route('forget-password')->with('error', $errorMessages);

        } else {
            
            $request->session()->put('forget_username', $request->entity_value);
            $request->session()->put('foget_otp_code', $request->otp_code);


            $today = Carbon::today()->toDateString();

            $verifiedCustomer = CustomerVerification::where('entity_value', $request->entity_value)
                                                    ->where('verification_code', $request->otp_code)
                                                    ->where('type', 2)
                                                    ->where('expired', '>', Carbon::now())
                                                    ->first();

            if ($verifiedCustomer && $request->session()->has('forget_username') && $request->session()->has('foget_otp_code')) {
                return redirect()->route('reset-password');
            }

            return redirect()->route('forget-password')->with('error', 'OTP not valid');
        }

    }


    public function showResetPassword() 
    {
        if (session()->has('forget_username') && session()->has('foget_otp_code')) {

            return view('muslin.reset-password');
        }
        else {

            return redirect('forget-password')->with('error', 'You have no permission');
        }
    }


    public function resetPasswordSubmit(Request $request) 
    {
        $verifiedCustomer = CustomerVerification::where('entity_value', $request->forget_username)
                                                ->where('verification_code', $request->foget_otp_code)
                                                ->where('type', 2)
                                                ->where('expired', '>', Carbon::now())
                                                ->first();

        if ($verifiedCustomer) {

            $user = User::where(['username' => $request->forget_username])->first();

            if($user) {

                $validator =  Validator::make($request->all(), [
                                'password' => 'required|string|min:8|confirmed',
                            ]);

                if ($validator->fails()) {

                    return redirect('reset-password')->with('error', $validator->errors()->first());

                } else {

                    $data = [
                        'password' => Hash::make($request->password)
                    ];

                    $user->fill($data);

                    if($user->save()) {

                        session()->forget('forget_username');
                        session()->forget('foget_otp_code');

                        return redirect('sign-in')->with('success', 'Password has been reset');
                    }
                    else {
                            
                        return redirect()->back()->with('error', 'Something went wrong. Try again.');
                    }
                }

            }
            else {

                return redirect('sign-in')->with('error', 'User not found.');
            }   
        }
        else {
            return redirect('sign-in')->with('error', 'Code may be expired or invalid');
        }

    }


    public function changePassword(Request $request) 
    {


        $validator = Validator::make($request->all(), [
                        'old_password' => 'required',
                        'new_password' => 'required|confirmed',
                    ]);

        if ($validator->fails()) {
            
            return response()->json(['result' => 'error', 'errors' => $validator->errors()], 422);
        }


        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)) {

            $response['type'] = 'error';
            $response['msg'] = 'Old password does not match';
        }
        else {

            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            $response['type'] = 'success';
            $response['msg'] = 'Password has been changed';
        }

        return $response;
        
    }


    public function deleteAddress($id)
    {
        $address = ShippingAddress::findOrFail($id);

        if($address->delete()) {

            session()->flash('success', 'Address has been deleted');
            return redirect(route('my-profile') . '#address');

        } else {

            session()->flash('error', 'Address could not delete.');
            return redirect()->back()->with('error', 'Address could not delete.');
        } 

    }


    public function addressInfo($id)
    {
        $address = ShippingAddress::findOrFail($id);

        if($address) {

            $all_country = Country::where('status', 'publish')->get()->toArray();   
            $all_country = array_column($all_country, 'name', 'id');

            $all_state = [];

            if(!empty($address->state_id)) {
            
                $all_state = State::select('id', 'name')->where('country_id', $address->country_id)->get()->toArray();
                $all_state = array_column($all_state, 'name', 'id');
            }
 
            $partial_view_html = view('muslin._edit_address_form', ['data' => $address, 'all_country' => $all_country, 'all_state' => $all_state])->render();

            $response['type'] = 'success';
            $response['msg'] = 'Data found';
            $response['result'] = $partial_view_html;

        } else {

            $response['errpr'] = 'data not found';
            $response['result'] = [];
        } 

        return $response;
    }


    public function addAddress(Request $request)
    {
        $address = ShippingAddress::create([
                        'user_id' => auth()->user()->id,
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'phone' => $request->phone,
                        'country_id' => $request->country_id,
                        'state_id' => $request->state_id,
                        'city' => $request->city,
                        'zip_code' => $request->zip_code,
                        'address' => $request->address,
                        'default_shipping_status' => !empty($request->default_address) ? 1 : 0

                    ]);

        if($address) {

            if(!empty($request->default_address)) {

                $unset_default = ShippingAddress::where('user_id', auth()->user()->id)
                                                ->where('id', '!=', $address->id)
                                                ->update(['default_shipping_status' => 0]);
            }
            

            session()->flash('success', 'Address saved successfully.');   

            return redirect(route('my-profile') . '#address');
        }
        else {
            session()->flash('error', 'Address could not save.');   
        }
    }


    public function editAddress(Request $request, $id)
    {
        $address = ShippingAddress::findOrFail($id);

        if($address) {

            $data = [
                'user_id' => auth()->user()->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
                'default_shipping_status' => !empty($request->default_address) ? 1 : 0
            ];

            $address->fill($data);

            if($address->save()) {

                if(!empty($request->default_address)) {

                    $unset_default = ShippingAddress::where('user_id', auth()->user()->id)
                                                    ->where('id', '!=', $address->id)
                                                    ->update(['default_shipping_status' => 0]);
                }

                session()->flash('success', 'Address updated successfully.');   

                return redirect(route('my-profile') . '#address');

            } else {
               session()->flash('error', 'Address could not save.');   
            }
        } 
        else {
           session()->flash('error', 'Address not found');   
        } 
    }


    public function orderDetails($id)
    {
        // $order = Order::findOrFail($id);

        $orders = SubOrder::with(['order', 'vendor', 'orderItem', 'orderItem.product', 'orderItem.variant', 'orderItem.variant.productColor', 'orderItem.variant.productSize'])
            ->where('order_id', $id)->get();

        $payment_details = Order::with('address', 'paymentMeta')->find($id);

        if($payment_details) {

            $partial_view_html = view('muslin._order_details', ['payment_details' => $payment_details, 'orders' => $orders])->render();

            $response['type'] = 'success';
            $response['msg'] = 'data found';
            $response['result'] = $partial_view_html;

        } else {

            $response['type'] = 'error';
            $response['msg'] = 'data not found';
            $response['result'] = [];
        } 

        return $response;
    }


}
