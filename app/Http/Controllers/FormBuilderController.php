<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function get_in_touch_form_index()
    {
        return view('backend.form-builder.get-in-touch-form');
    }

    public function update_get_in_touch_form(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            $all_fields_name[] = strtolower(Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('get_in_touch_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

    // public function case_study_query_index()
    // {
    //     return view('backend.form-builder.case-study-query-form');
    // }
    // public function update_case_study_query(Requests $request)
    // {
    //     $request->validate( [
    //         'field_name' => 'required|max:191',
    //         'field_placeholder' => 'required|max:191',
    //     ]);
    //     unset($request['_token']);
    //     $all_fields_name = [];
    //     $all_request_except_token = $request->all();
    //     foreach ($request->field_name as $fname) {
    //         $all_fields_name[] = Str::slug($fname);
    //     }
    //     $all_request_except_token['field_name'] = $all_fields_name;
    //     $json_encoded_data = json_encode($all_request_except_token);

    //     update_static_option('case_study_query_form_fields', $json_encoded_data);
    //     return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    // }
    // public function service_query_index()
    // {
    //     return view('backend.form-builder.service-query-form');
    // }

    // public function update_service_query(Requests $request)
    // {
    //     $request->validate( [
    //         'field_name' => 'required|max:191',
    //         'field_placeholder' => 'required|max:191',
    //     ]);
    //     unset($request['_token']);
    //     $all_fields_name = [];
    //     $all_request_except_token = $request->all();
    //     foreach ($request->field_name as $fname) {
    //         $all_fields_name[] = Str::slug($fname);
    //     }
    //     $all_request_except_token['field_name'] = $all_fields_name;
    //     $json_encoded_data = json_encode($all_request_except_token);

    //     update_static_option('service_query_form_fields', $json_encoded_data);
    //     return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    // }

    // public function quote_form_index()
    // {
    //     return view('backend.form-builder.quote-form');
    // }

    // public function update_quote_form(Requests $request)
    // {
    //     $request->validate( [
    //         'field_name' => 'required|max:191',
    //         'field_placeholder' => 'required|max:191',
    //     ]);
    //     unset($request['_token']);
    //     $all_fields_name = [];
    //     $all_request_except_token = $request->all();
    //     foreach ($request->field_name as $fname) {
    //         $all_fields_name[] = Str::slug($fname);
    //     }
    //     $all_request_except_token['field_name'] = $all_fields_name;
    //     $json_encoded_data = json_encode($all_request_except_token);

    //     update_static_option('quote_page_form_fields', $json_encoded_data);
    //     return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    // }

    public function order_form_index()
    {
        return view('backend.form-builder.order-form');
    }

    public function update_order_form(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            $all_fields_name[] = Str::slug($fname);
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('order_page_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

    public function contact_form_index()
    {
        return view('backend.form-builder.contact-form');
    }

    public function update_contact_form(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            $all_fields_name[] = Str::slug($fname);
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('contact_page_contact_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

    // public function apply_job_form_index()
    // {
    //     return view('backend.form-builder.apply-job-form');
    // }
    // public function update_apply_job_form(Requests $request)
    // {
    //     $request->validate( [
    //         'field_name' => 'required|max:191',
    //         'field_placeholder' => 'required|max:191',
    //     ]);
    //     unset($request['_token']);
    //     $all_fields_name = [];
    //     $all_request_except_token = $request->all();
    //     foreach ($request->field_name as $fname) {
    //         $all_fields_name[] = Str::slug($fname);
    //     }
    //     $all_request_except_token['field_name'] = $all_fields_name;
    //     $json_encoded_data = json_encode($all_request_except_token);

    //     update_static_option('apply_job_page_form_fields', $json_encoded_data);
    //     return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    // }

    // public function event_attendance_form_index()
    // {
    //     return view('backend.form-builder.event-attendance-form');
    // }

    // public function update_event_attedance_form(Requests $request)
    // {
    //     $request->validate( [
    //         'field_name' => 'required|max:191',
    //         'field_placeholder' => 'required|max:191',
    //     ]);
    //     unset($request['_token']);
    //     $all_fields_name = [];
    //     $all_request_except_token = $request->all();
    //     foreach ($request->field_name as $fname) {
    //         $all_fields_name[] = Str::slug($fname);
    //     }
    //     $all_request_except_token['field_name'] = $all_fields_name;
    //     $json_encoded_data = json_encode($all_request_except_token);

    //     update_static_option('event_attendance_form_fields', $json_encoded_data);
    //     return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    // }

    // public function appointment_form_index()
    // {
    //     return view('backend.form-builder.appointment-form');
    // }

    // public function update_appointment_form(Requests $request)
    // {
    //     $request->validate( [
    //         'field_name' => 'required|max:191',
    //         'field_placeholder' => 'required|max:191',
    //     ]);
    //     unset($request['_token']);
    //     $all_fields_name = [];
    //     $all_request_except_token = $request->all();
    //     foreach ($request->field_name as $fname) {
    //         $all_fields_name[] = Str::slug($fname);
    //     }
    //     $all_request_except_token['field_name'] = $all_fields_name;
    //     $json_encoded_data = json_encode($all_request_except_token);

    //     update_static_option('appointment_form_fields', $json_encoded_data);
    //     return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    // }

    public function estimate_form_index()
    {
        return view('backend.form-builder.estimate-form');
    }

    public function update_estimate_form(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            $all_fields_name[] = Str::slug($fname);
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('estimate_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }
}
