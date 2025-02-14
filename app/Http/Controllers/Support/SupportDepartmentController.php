<?php

namespace Modules\CountryManage\Http\Controllers\Support;

use App\Helpers\FlashMsg;
use App\Support\SupportDepartment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SupportDepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:support-ticket-department-list|support-ticket-department-create|support-ticket-department-edit|support-ticket-department-delete', ['only', ['category']]);
        $this->middleware('permission:support-ticket-department-create', ['only', ['new_category']]);
        $this->middleware('permission:support-ticket-department-edit', ['only', ['update']]);
        $this->middleware('permission:support-ticket-department-delete', ['only', ['delete', 'bulk_action']]);
    }

    public function category()
    {
        $all_category = SupportDepartment::all();

        return view('backend.support-ticket.support-ticket-category.category')->with([
            'all_category' => $all_category,
        ]);
    }

    public function new_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:support_departments',
            'status' => 'required|string|max:191',
        ]);

        SupportDepartment::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with(FlashMsg::item_new());
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:support_departments,id,'.$request->id,
            'status' => 'required|string|max:191',
        ]);

        SupportDepartment::find($request->id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function delete(Request $request, $id)
    {
        SupportDepartment::find($id)->delete();

        return redirect()->back()->with(FlashMsg::item_delete());
    }

    public function bulk_action(Request $request)
    {
        SupportDepartment::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }
}
