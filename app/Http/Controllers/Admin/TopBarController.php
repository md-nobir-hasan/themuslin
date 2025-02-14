<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Menu;
use App\SocialIcons;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TopBarController extends Controller
{
    public function topbar_settings(): Factory|View|Application
    {
        $all_social_icons = SocialIcons::all();
        $menus = Menu::all();

        return view('backend.pages.topbar-settings')->with([
            'all_social_icons' => $all_social_icons,
            'menus' => $menus,
        ]);
    }

    public function selectTopBarMenu(Request $request): RedirectResponse
    {
        $request->validate(['topbar_menu' => 'exists:menus,id']);
        update_static_option('topbar_menu', $request->topbar_menu);

        return back()->with(FlashMsg::explain('success', __('Top-bar menu updated')));
    }

    /** ===================================================================
     *                          SOCIAL ICONS
     * =================================================================== */
    public function new_social_item(Request $request): RedirectResponse
    {
        $request->validate([
            'icon' => 'required|string',
            'url' => 'required|string',
        ]);

        SocialIcons::create([
            'icon' => $request->sanitize_html('icon'),
            'url' => $request->sanitize_html('url'),
        ]);

        return redirect()->back()->with([
            'msg' => __('New Social Item Added...'),
            'type' => 'success',
        ]);
    }

    public function update_social_item(Request $request): RedirectResponse
    {
        $request->validate([
            'icon' => 'required|string',
            'url' => 'required|string',
        ]);

        SocialIcons::find($request->id)->update([
            'icon' => $request->sanitize_html('icon'),
            'url' => $request->sanitize_html('url'),
        ]);

        return redirect()->back()->with([
            'msg' => __('Social Item Updated...'),
            'type' => 'success',
        ]);
    }

    public function delete_social_item(Request $request, $id): RedirectResponse
    {
        SocialIcons::find($id)->delete();

        return redirect()->back()->with([
            'msg' => __('Social Item Deleted...'),
            'type' => 'danger',
        ]);
    }
}
