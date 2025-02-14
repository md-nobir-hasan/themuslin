<?php

namespace Modules\EmailTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryManEmailController extends Controller
{
    public function assignMail()
    {
        return view("emailtemplate::delivery-man.assign-delivery-man-mail");
    }

    public function handleAssignMail(Request $request){
        $validatedData = $request->validate([
            "assign_delivery_man_mail_subject" => "nullable|string",
            "assign_delivery_man_mail_body" => "nullable|string",
        ]);

        foreach($validatedData as $key => $value){
            update_static_option($key, $value);
        }

        return back()->with([
            "type" => "success",
            "msg" => __("Successfully updated assign delivery mail")
        ]);
    }

    public function assignMailToUser()
    {
        return view("emailtemplate::delivery-man.assign-delivery-man-mail-to-user");
    }

    public function handleAssignMailToUser(Request $request){
        $validatedData = $request->validate([
            "assign_delivery_man_mail_to_user_subject" => "nullable|string",
            "assign_delivery_man_mail_to_user_body" => "nullable|string",
        ]);

        foreach($validatedData as $key => $value){
            update_static_option($key, $value);
        }

        return back()->with([
            "type" => "success",
            "msg" => __("Successfully updated assign delivery mail")
        ]);
    }
}
