<?php

namespace App\Http\Controllers\Admin;

use App\CategoryMenu;
use App\Http\Requests\CategoryMenus\Fetch_sub_category_request;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\SubCategory;
use Modules\Product\Entities\ProductCategory;

class CategoryMenuController extends Controller
{
    public function index()
    {
        $all_menu = CategoryMenu::all();

        return view('backend.pages.category_menu.menu-index')->with([
            'all_menu' => $all_menu,
        ]);
    }

    public function store_new_menu(Request $request)
    {
        $request->validate([
            'content' => 'nullable',
            'title' => 'required',
        ]);

        CategoryMenu::create([
            'content' => $request->page_content,
            'title' => $request->title,
        ]);

        return redirect()->back()->with([
            'msg' => __('New Menu Created...'),
            'type' => 'success',
        ]);
    }

    public function edit_menu($id)
    {
        $page_post = CategoryMenu::find($id);

        return view('backend.pages.category_menu.menu-edit')->with([
            'page_post' => $page_post,
        ]);
    }

    public function update_menu(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable',
            'title' => 'required',
        ]);
        CategoryMenu::where('id', $id)->update([
            'content' => $request->menu_content,
            'title' => $request->title,
        ]);
        //dd($request->all());

        return redirect()->back()->with([
            'msg' => __('Menu updated...'),
            'type' => 'success',
        ]);
    }

    public function delete_menu(Request $request, $id)
    {
        CategoryMenu::find($id)->delete();

        return redirect()->back()->with([
            'msg' => __('Menu Delete Success...'),
            'type' => 'danger',
        ]);
    }

    public function set_default_menu(Request $request, $id)
    {
        $lang = CategoryMenu::find($id);
        CategoryMenu::where(['status' => 'default'])->update(['status' => '']);

        CategoryMenu::find($id)->update(['status' => 'default']);
        $lang->status = 'default';
        $lang->save();

        return redirect()->back()->with([
            'msg' => 'Default Menu Set To '.$lang->title,
            'type' => 'success',
        ]);
    }

    public function mega_menu_item_select_markup(Request $request)
    {

        $output = '';
        $item_details = ProductCategory::where('status', 'publish')->get();
        $output .= '<label for="items_id">'.__('Select Category').'</label>';
        $output .= '<select name="items_id" class="form-control product_mega_menu_category_selection">';
        $output .= '<option value="">'.__('select category').'</option>';
        foreach ($item_details as $item) {
            $output .= '<option value="'.$item->id.'" >'.htmlspecialchars(strip_tags($item->title)) ?? ''.'</option>';
        }
        $output .= '</select>';

        //sub category sleection

        $output .= '<br><label>'.__('Select Sub Category').'</label>';
        $output .= '<select multiple name="sub_cat_items_id" class="form-control sub_category_menus">';
        $output .= '<option value="">'.__('select subcategory').'</option>';
        $output .= '</select>';

        return $output;
    }

    public function fetch_sub_category(Fetch_sub_category_request $request)
    {
        $data = SubCategory::where('category_id', $request->validated()['category_id'])->get();

        return view('backend.sub_category.fetch_sub_category', compact('data'))->render();
    }
}
