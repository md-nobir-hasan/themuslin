<?php

namespace App\Http\Controllers\Admin;

use App\Widgets;
use App\WidgetsBuilder\WidgetBuilderSetup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WidgetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $all_widget = Widgets::orderBy('widget_order', 'ASC')->get()->groupBy('widget_location');

        return view('backend.widgets.widget-index')->with(['all_widgets' => $all_widget]);
    }

    public function widget_markup(Request $request)
    {
        $output = WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->widget_name,
            'type' => 'new',
            'after' => false,
            'before' => false,
        ]);

        return $output;
    }

    public function new_widget(Request $request)
    {
        $request->validate([
            'widget_name' => 'required',
            'widget_order' => 'required',
            'widget_location' => 'required',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        $widget_id = Widgets::create([
            'widget_name' => $request->widget_name,
            'widget_order' => $request->widget_order,
            'widget_location' => $request->widget_location,
            'widget_content' => serialize($widget_content),
        ])->id;
        $data['id'] = $widget_id;
        $data['status'] = 'ok';

        return response()->json($data);
    }

    public function update_widget(Request $request)
    {
        $request->validate([
            'widget_name' => 'required',
            'widget_order' => 'required',
            'widget_location' => 'required',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        Widgets::findOrFail($request->id)->update([
            'widget_name' => $request->widget_name,
            'widget_order' => $request->widget_order,
            'widget_location' => $request->widget_location,
            'widget_content' => serialize($widget_content),
        ]);

        return response()->json('ok');
    }

    public function delete_widget(Request $request)
    {
        Widgets::findOrFail($request->id)->delete();

        return response()->json('ok');
    }

    public function update_order_widget(Request $request)
    {
        Widgets::findOrFail($request->id)->update(['widget_order' => $request->widget_order]);

        return response()->json('ok');
    }
}
