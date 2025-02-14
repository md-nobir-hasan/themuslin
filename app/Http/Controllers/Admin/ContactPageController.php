<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Language;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function contact_page_form_area(){
        return view('backend.pages.contact-page.form-section');
    }
    public function contact_page_update_form_area(Request $request){
        $request->validate([
            'contact_page_form_receiving_mail' => 'nullable|string'
        ]);

        $request->validate([
            'contact_page_form_section_title' => 'nullable|string',
            'contact_page_form_submit_btn_text' => 'nullable|string',
        ]);

        $field = 'contact_page_form_section_title';
        $form_submit_btn_text = 'contact_page_form_submit_btn_text';

        update_static_option('contact_page_form_section_title',$request->$field);
        update_static_option('contact_page_form_submit_btn_text',$request->$form_submit_btn_text);

        update_static_option('contact_page_form_receiving_mail',$request->contact_page_form_receiving_mail);

        return redirect()->back()->with(['msg' => __('Settings Updated..'),'type' => 'success']);
    }
    public function contact_page_map_area(){
        return view('backend.pages.contact-page.google-map-section');
    }
    public function contact_page_update_map_area(Request $request){
        $request->validate([
            'contact_page_map_section_location' => 'required|string',
            'contact_page_map_section_zoom' => 'required|string',
        ]);
        update_static_option('contact_page_map_section_location',$request->contact_page_map_section_location);
        update_static_option('contact_page_map_section_zoom',$request->contact_page_map_section_zoom);

        return redirect()->back()->with(['msg' => __('Settings Updated..'),'type' => 'success']);
    }

    public function contact_page_section_manage(){
        return view('backend.pages.contact-page.section-manage');
    }

    public function contact_page_update_section_manage(Request $request){
        $request->validate([
            'contact_page_contact_info_section_status' => 'required|string',
            'contact_page_contact_section_status' => 'required|string',
        ]);
        update_static_option('contact_page_contact_info_section_status',$request->contact_page_contact_info_section_status);
        update_static_option('contact_page_contact_section_status',$request->contact_page_contact_section_status);

        return redirect()->back()->with(['msg' => __('Settings Updated..'),'type' => 'success']);
    }
}
