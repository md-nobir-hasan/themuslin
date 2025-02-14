<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Page;
use App\PageBuilder;
use App\PageBuilder\PageBuilderSetup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PageBuilderController extends Controller
{
    const BASE_PATH = 'backend.page-builder.';

    //    public function homepage_builder()
    //    {
    //        $home_page_variant = get_static_option('home_page_variant');
    //
    //        switch ($home_page_variant) {
    //            case '01':
    //                return view(self::BASE_PATH . 'homepage');
    //                break;
    //            case '02':
    //                return view(self::BASE_PATH . 'homepage_02');
    //                break;
    //            default:
    //                return view(self::BASE_PATH . 'homepage');
    //                break;
    //        }
    //    }

    //    public function aboutpage_builder()
    //    {
    //        return view(self::BASE_PATH . 'aboutpage');
    //    }

    public function contactpage_builder()
    {
        return view(self::BASE_PATH.'contactpage');
    }

    public function faqpage_builder()
    {
        return view(self::BASE_PATH.'faqpage');
    }

    public function dynamicpage_builder($type, $id)
    {
        if (empty($type) || empty($id)) {
            abort(404);
        }

        $page = Page::findOrFail($id);

        return view(self::BASE_PATH.'dynamicpage', compact('id', 'type', 'page'));
    }

    //    public function update_aboutpage_builder(Requests $request)
    //    {
    //       $request->validate( [
    //            'about_page_page_builder_status' => 'nullable|string|max:191'
    //        ]);
    //        update_static_option('about_page_page_builder_status', $request->about_page_page_builder_status);
    //        return back()->with(FlashMsg::settings_update());
    //    }

    public function update_contactpage_builder(Request $request)
    {
        $request->validate([
            'contact_page_page_builder_status' => 'nullable|string|max:191',
        ]);
        update_static_option('contact_page_page_builder_status', $request->contact_page_page_builder_status);

        return back()->with(FlashMsg::settings_update());
    }

    public function update_faqpage_builder(Request $request)
    {
        $request->validate([
            'faq_page_page_builder_status' => 'nullable|string|max:191',
        ]);
        update_static_option('faq_page_page_builder_status', $request->faq_page_page_builder_status);

        return back()->with(FlashMsg::settings_update());
    }

    //    public function update_homepage_builder(Requests $request)
    //    {
    //       $request->validate( [
    //            'home_page_page_builder_status' => 'nullable|string|max:191'
    //        ]);
    //        update_static_option('home_page_page_builder_status', $request->home_page_page_builder_status);
    //        return back()->with(FlashMsg::settings_update());
    //    }

    public function get_admin_panel_addon_markup(Request $request)
    {
        $output = PageBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->addon_class,
            'namespace' => base64_decode($request->addon_namespace),
            'type' => 'new',
            'page_id' => $request->addon_page_id ?? '',
            'page_type' => $request->addon_page_type ?? '',
            'location' => $request->addon_location ?? '',
            'after' => false,
            'before' => false,
        ]);

        return $output;
    }

    public function store_new_addon_content(Request $request)
    {
        $request->validate([
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
            'addon_location' => 'required',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        $widget_id = PageBuilder::create([
            'addon_type' => $request->addon_type,
            'addon_location' => $request->addon_location,
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => $request->addon_page_id,
            'addon_order' => $request->addon_order,
            'addon_page_type' => $request->addon_page_type,
            'addon_settings' => serialize($widget_content),
        ])->id;
        $data['id'] = $widget_id;
        $data['status'] = 'ok';

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        PageBuilder::findOrFail($request->id)->delete();

        return response()->json('ok');
    }

    public function update_addon_order(Request $request)
    {
        PageBuilder::findOrFail($request->id)->update(['addon_order' => $request->addon_order]);

        return response()->json('ok');
    }

    public function update_addon_content(Request $request)
    {
        $request->validate([
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
            'addon_location' => 'required',
        ]);

        unset($request['_token']);
        $addon_content = (array) $request->all();

        PageBuilder::findOrFail($request->id)->update([
            'addon_type' => $request->addon_type,
            'addon_location' => $request->addon_location,
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => $request->addon_page_id,
            'addon_order' => $request->addon_order,
            'addon_page_type' => $request->addon_page_type,
            'addon_settings' => serialize($addon_content),
        ]);

        return response()->json('ok');
    }

    /* ========================================================
                       BLOG PAGE
      ======================================================== */
    public function blogpage_builder()
    {
        return view(self::BASE_PATH.'blogpage');
    }

    public function update_blogpage_builder(Request $request)
    {
        $request->validate([
            'blog_page_page_builder_status' => 'nullable|string|max:191',
        ]);

        update_static_option('blog_page_page_builder_status', $request->blog_page_page_builder_status);

        return back()->with(FlashMsg::settings_update());
    }
}
