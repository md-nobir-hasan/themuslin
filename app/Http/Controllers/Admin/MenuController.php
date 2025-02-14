<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Menu;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:appearance-menu-manage-list|appearance-menu-manage-create|appearance-menu-manage-edit|appearance-menu-manage-delete',['only' => ['index']]);
        $this->middleware('permission:appearance-menu-manage-create',['only' => ['store_new_menu']]);
        $this->middleware('permission:appearance-menu-manage-edit',['only' => ['edit_menu','update_menu','set_default_menu']]);
        $this->middleware('permission:appearance-menu-manage-delete',['only' => ['delete_menu']]);
    }
    public function index(){
        $all_menu = Menu::all();
        return view('backend.pages.menu.menu-index')->with([
            'all_menu' => $all_menu,
        ]);
    }

    public function store_new_menu(Request $request){
        $request->validate([
            'content' => 'nullable',
            'title' => 'required',
        ]);

        Menu::create([
            'content' => $request->page_content,
            'title' => $request->title,
        ]);

        return redirect()->back()->with([
            'msg' => __('New Menu Created...'),
            'type' => 'success'
        ]);
    }
    public function edit_menu($id){
        $page_post = Menu::find($id);

        return view('backend.pages.menu.menu-edit')->with([
            'page_post' => $page_post,
        ]);
    }
    public function update_menu(Request $request,$id){

        $request->validate([
            'content' => 'nullable',
            'title' => 'required',
        ]);

        Menu::where('id',$id)->update([
            'content' => $request->menu_content,
            'title' => $request->title,
        ]);

        return redirect()->back()->with([
            'msg' => __('Menu updated...'),
            'type' => 'success'
        ]);
    }
    public function delete_menu(Request $request,$id){
        Menu::find($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Menu Delete Success...'),
            'type' => 'danger'
        ]);
    }

    public function set_default_menu(Request $request,$id){
        $lang = Menu::find($id);
        Menu::where(['status' => 'default'])->update(['status' => '']);

        Menu::find($id)->update(['status' => 'default']);
        $lang->status = 'default';
        $lang->save();
        return redirect()->back()->with([
            'msg' => 'Default Menu Set To '.$lang->title,
            'type' => 'success'
        ]);
    }

    public function mega_menu_item_select_markup(Request $request)
    {
        $output = '';
        $class_name = '\\'.$request->type;
        $instance = new $class_name();
        $model_name = '\\'.$instance->model();
        $model = new $model_name();
        if ($instance->query_type() === 'old_lang'){
            $item_details =  $model->where('lang',$request->lang)->get();
        }elseif($instance->query_type() === 'new_lang'){
            $item_details =  $model->with(['lang_query' => function($query) use ($request){
                $query->where('lang',$request->lang);
            }])->get();
        }else{
            $item_details =  $model->get();
        }

        $output .= '<label for="items_id">' . __('Select Items') . '</label>';
        $output .= '<select name="items_id" multiple class="form-control">';
        foreach ($item_details as $item):
            $title_param = $instance->title_param();
            if ($instance->query_type() === 'old_lang'){
                $title = $item->$title_param ?? '';
            }elseif($instance->query_type() === 'new_lang'){
                $title = $item->lang_query->$title_param ?? '';
            }else{
                $title = $item->$title_param ?? '';
            }

            $title = $instance->query_type() === 'old_lang' ? $item->$title_param : $title;

            $output .= '<option value="' . $item->id . '" >' . htmlspecialchars(strip_tags($title)) ?? '' . '</option>';
        endforeach;
        $output .= '</select>';
        return $output;
    }
}
