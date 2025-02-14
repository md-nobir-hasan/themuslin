<?php

namespace Modules\EmailTemplate\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailTemplateController extends Controller
{
    public function all_templates()
    {
        return view('emailtemplate::template.all-templates');
    }

    // hare will be request send email template settings
    public function requestSend(){
        return view("emailtemplate::refund.request-send");
    }

    public function updateRefundRequestSend(Request $request){
        $requestData = $request->validate([
            "refund_request_send_mail_subject" => "nullable|string",
            "refund_request_send_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request send email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestApproved(){
        return view("emailtemplate::refund.request-approved");
    }

    public function updateRefundRequestApproved(Request $request){
        $requestData = $request->validate([
            "refund_request_approved_mail_subject" => "nullable|string",
            "refund_request_approved_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request send email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestDeclined(){
        return view("emailtemplate::refund.request-declined");
    }

    public function updateRefundRequestDeclined(Request $request){
        $requestData = $request->validate([
            "refund_request_declined_mail_subject" => "nullable|string",
            "refund_request_declined_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request send email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestCancel(){
        return view("emailtemplate::refund.cancel");
    }

    public function updateRefundRequestCancel(Request $request){
        $requestData = $request->validate([
            "refund_request_cancel_mail_subject" => "nullable|string",
            "refund_request_cancel_mail_body" => "nullable|string"
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request send email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestReadyForPickup(){
        return view("emailtemplate::refund.request-ready-for-pickup");
    }

    public function updateRefundRequestReadyForPickup(Request $request){
        $requestData = $request->validate([
            "refund_request_ready_for_pickup_mail_subject" => "nullable|string",
            "refund_request_ready_for_pickup_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestPickedUp(){
        return view("emailtemplate::refund.request-pickedup");
    }

    public function updateRefundRequestPickedUp(Request $request){
        $requestData = $request->validate([
            "refund_request_picked_up_mail_subject" => "nullable|string",
            "refund_request_picked_up_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestOnTheWay(){
        return view("emailtemplate::refund.request-on-the-way");
    }

    public function updateRefundRequestOnTheWay(Request $request){
        $requestData = $request->validate([
            "refund_request_on_the_way_mail_subject" => "nullable|string",
            "refund_request_on_the_way_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestReturned(){
        return view("emailtemplate::refund.request-returned");
    }

    public function updateRefundRequestReturned(Request $request){
        $requestData = $request->validate([
            "refund_request_returned_mail_subject" => "nullable|string",
            "refund_request_returned_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestVerifyProduct(){
        return view("emailtemplate::refund.request-verify-product");
    }

    public function updateRefundRequestVerifyProduct(Request $request){
        $requestData = $request->validate([
            "refund_request_verify_product_mail_subject" => "nullable|string",
            "refund_request_verify_product_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestPaymentProcessing(){
        return view("emailtemplate::refund.request-payment-processing");
    }

    public function updateRefundRequestPaymentProcessing(Request $request){
        $requestData = $request->validate([
            "refund_request_payment_processing_mail_subject" => "nullable|string",
            "refund_request_payment_processing_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestPaymentTransferred(){
        return view("emailtemplate::refund.request-payment-transferred");
    }

    public function updateRefundRequestPaymentTransferred(Request $request){
        $requestData = $request->validate([
            "refund_request_payment_transferred_mail_subject" => "nullable|string",
            "refund_request_payment_transferred_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }

    // hare will be request send email template settings
    public function refundRequestPaymentCompleted(){
        return view("emailtemplate::refund.request-payment-completed");
    }

    public function updateRefundRequestPaymentCompleted(Request $request){
        $requestData = $request->validate([
            "refund_request_payment_completed_mail_subject" => "nullable|string",
            "refund_request_payment_completed_mail_body" => "nullable|string",
        ]);

        foreach($requestData as $key => $data){
            update_static_option($key, $data);
        }

        return back()->with([
            "msg" => __("Successfully updated refund request ready for pickup email"),
            "type" => "success"
        ]);
    }







    //todo: user identity verify request mail
    public function user_identity_verify_request(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_verify_subject'=>'required|min:5|max:100',
                'user_identity_verify_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_identity_verify_subject',
                'user_identity_verify_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-verify-request');
    }

    //todo: user register mail
    public function user_register(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_register_subject'=>'required|min:5|max:100',
                'user_register_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_register_subject',
                'user_register_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::register.user-register');
    }

    //todo: user identity verify confirm mail
    public function user_identity_verify_confirm(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_verify_confirm_subject'=>'required|min:5|max:100',
                'user_identity_verify_confirm_message'=>'required|min:10|max:5000',
            ]);
            $fields = [
                'user_identity_verify_confirm_subject',
                'user_identity_verify_confirm_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-verify-confirm');
    }

    //todo: user identity reverification mail
    public function user_identity_reverification(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_re_verify_subject'=>'required|min:5|max:100',
                'user_identity_re_verify_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_identity_re_verify_subject',
                'user_identity_re_verify_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-reverification');
    }

    //todo: user identity decline mail
    public function user_identity_decline(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_decline_subject'=>'required|min:5|max:100',
                'user_identity_decline_message'=>'required|min:10|max:3000',
            ]);
            $fields = [
                'user_identity_decline_subject',
                'user_identity_decline_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-decline');
    }

   //todo: user info and password update email
    public function user_info_update_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_info_update_subject'=>'required|min:5|max:100',
                'user_info_update_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_info_update_subject',
                'user_info_update_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::user-info-update.user-info-update-mail');
    }

    //todo: user info update email
    public function user_password_change_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_password_change_subject'=>'required|min:5|max:100',
                'user_password_change_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_password_change_subject',
                'user_password_change_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::password.password-change');
    }

    //todo: user status active email
    public function user_status_active_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_status_active_subject'=>'required|min:5|max:100',
                'user_status_active_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_status_active_subject',
                'user_status_active_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::user-status.active');
    }

    //todo: user status active email
    public function user_status_inactive_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_status_inactive_subject'=>'required|min:5|max:100',
                'user_status_inactive_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_status_inactive_subject',
                'user_status_inactive_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::user-status.inactive');
    }

    //todo: user status active email
    public function user_2fa_disable_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                '_2fa_disable_subject'=>'required|min:5|max:100',
                '_2fa_disable_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                '_2fa_disable_subject',
                '_2fa_disable_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::-2fa.disable-2fa');
    }

    //todo:user email verified
    public function user_verified_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_email_verified_subject'=>'required|min:5|max:100',
                'user_email_verified_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_email_verified_subject',
                'user_email_verified_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::email-verify.email-verify');
    }

    //todo:project
    //todo:user project create mail
    public function project_create_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_create_email_subject'=>'required|min:5|max:100',
                'project_create_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'project_create_email_subject',
                'project_create_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-create');
    }

    //todo:user project edit mail
    public function project_edit_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_edit_email_subject'=>'required|min:5|max:100',
                'project_edit_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'project_edit_email_subject',
                'project_edit_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-edit');
    }

    //todo:user project activate mail
    public function project_activate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_approve_email_subject'=>'required|min:5|max:100',
                'project_approve_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'project_approve_email_subject',
                'project_approve_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-activate');
    }

    //todo:user project activate mail
    public function project_inactivate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_inactivate_email_subject'=>'required|min:5|max:100',
                'project_inactivate_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'project_inactivate_email_subject',
                'project_inactivate_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-inactivate');
    }

    //todo:user project decline mail
    public function project_decline_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_decline_email_subject'=>'required|min:5|max:100',
                'project_decline_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'project_decline_email_subject',
                'project_decline_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-decline');
    }

    //todo:deposit
    //todo:user deposit mail
    public function user_deposit_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_deposit_to_wallet_subject'=>'required|min:5|max:100',
                'user_deposit_to_wallet_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_deposit_to_wallet_subject',
                'user_deposit_to_wallet_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::wallet.user-deposit');
    }
    //todo:user deposit mail to admin
    public function user_deposit_mail_receive_admin(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_deposit_to_wallet_subject_admin'=>'required|min:5|max:100',
                'user_deposit_to_wallet_message_admin'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'user_deposit_to_wallet_subject_admin',
                'user_deposit_to_wallet_message_admin',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::wallet.user-deposit-admin-mail');
    }

    //todo:job
    //todo:user job create mail
    public function job_create_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_create_email_subject'=>'required|min:5|max:100',
                'job_create_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'job_create_email_subject',
                'job_create_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-create');
    }

    //todo:user job edit mail
    public function job_edit_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_edit_email_subject'=>'required|min:5|max:100',
                'job_edit_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'job_edit_email_subject',
                'job_edit_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-edit');
    }

    //todo:user job activate mail
    public function job_activate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_approve_email_subject'=>'required|min:5|max:100',
                'job_approve_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'job_approve_email_subject',
                'job_approve_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-activate');
    }

    //todo:user job activate mail
    public function job_inactivate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_inactivate_email_subject'=>'required|min:5|max:100',
                'job_inactivate_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'job_inactivate_email_subject',
                'job_inactivate_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-inactivate');
    }

   //todo:user job decline mail
    public function job_decline_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_decline_email_subject'=>'required|min:5|max:100',
                'job_decline_email_message'=>'required|min:10|max:1000',
            ]);
            $fields = [
                'job_decline_email_subject',
                'job_decline_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-decline');
    }
}
