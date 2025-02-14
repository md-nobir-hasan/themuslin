<?php

namespace Modules\SupportTicket\Http\Controllers;

use App\Helpers\FlashMsg;
use Modules\SupportTicket\Entities\SupportDepartment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SupportTicket\Http\Requests\AdminStoreDepartmentRequest;
use Modules\SupportTicket\Http\Requests\AdminUpdateDepartmentRequest;

class SupportDepartmentController extends Controller
{
    public function category()
    {
        $all_category = SupportDepartment::all();
        return view('supportticket::backend.support-ticket-category.category')->with([
            'all_category' => $all_category,
        ]);
    }

    public function new_category(AdminStoreDepartmentRequest $request)
    {
        SupportDepartment::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with(FlashMsg::item_new());
    }

    public function update(AdminUpdateDepartmentRequest $request)
    {
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
