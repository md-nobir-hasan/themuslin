<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FormBuilderHelpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:appearance-form-builder');
    }

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
        update_static_option('get_in_touch_form_fields', FormBuilderHelpers::filter_form_field($request));

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

    public function case_study_query_index()
    {
        return view('backend.form-builder.case-study-query-form');
    }

    public function update_case_study_query(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            array_push($all_fields_name, Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('case_study_query_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

    public function service_query_index()
    {
        return view('backend.form-builder.service-query-form');
    }

    public function update_service_query(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            array_push($all_fields_name, Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('service_query_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

    public function quote_form_index()
    {
        return view('backend.form-builder.quote-form');
    }

    public function update_quote_form(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            array_push($all_fields_name, Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('quote_page_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

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
            array_push($all_fields_name, Str::slug($fname));
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
            array_push($all_fields_name, Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('contact_page_contact_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }

    public function apply_job_form_index()
    {
        return view('backend.form-builder.apply-job-form');
    }

    public function update_apply_job_form(Request $request)
    {
        $request->validate([
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
        ]);
        unset($request['_token']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname) {
            array_push($all_fields_name, Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        update_static_option('apply_job_page_form_fields', $json_encoded_data);

        return redirect()->back()->with(['msg' => __('Form Updated...'), 'type' => 'success']);
    }
}
