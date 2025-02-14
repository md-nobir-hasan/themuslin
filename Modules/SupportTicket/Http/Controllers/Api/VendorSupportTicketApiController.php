<?php

namespace Modules\SupportTicket\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\SupportDepartment;
use App\Support\SupportTicket;
use App\Support\SupportTicketMessage;
use Illuminate\Http\Request;

class VendorSupportTicketApiController extends Controller
{
    public function viewTickets(Request $request,$id= null)
    {
        $all_messages = SupportTicketMessage::where(['support_ticket_id'=>$id])->orderByDesc('id')
            ->paginate(20)->transform(function($item){
                $item->attachment = !empty($item->attachment) ? asset('assets/uploads/ticket/'.$item->attachment) : null;
                return $item;
            });

        $q = $request->q ?? '';
        return response()->json([
            'ticket_id'=>$id,
            'all_messages' =>$all_messages,
            'q' =>$q,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'file' => 'nullable|mimes:jpg,png,jpeg,gif',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $request->ticket_id,
            'type' => $request->user_type,
            'message' => $request->message,
        ]);

        if ($request->hasFile('file')){
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->extension();
            $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/ticket',$file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        return response()->json([
            'message'=>__('Message Send Success'),
            'ticket_id'=>$request->ticket_id,
            'user_type'=>$request->user_type,
            'ticket_info' => $ticket_info,
        ]);
    }

    public function get_department(){

        $data = SupportDepartment::select("id","name","status")->where(['status' => 'publish'])->get();
        return response()->json(["data" => $data]);
    }

    public function createTicket(Request $request){
        $uesr_info = auth('sanctum')->user()->id;
        $request->validate([
            'title' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'priority' => 'required|string|max:191',
            'description' => 'required|string',
            'departments' => 'required|string',
        ],[
            'title.required' => __('title required'),
            'subject.required' =>  __('subject required'),
            'priority.required' =>  __('priority required'),
            'description.required' => __('description required'),
            'departments.required' => __('departments required'),
        ]);

        $ticket = SupportTicket::create([
            'title' => $request->title,
            'via' => $request->via,
            'operating_system' => null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'description' => $request->description,
            'subject' => $request->subject,
            'status' => 'open',
            'priority' => $request->priority,
            'vendor_id' => $uesr_info,
            'admin_id' => null,
            'departments' => $request->departments
        ]);

        $msg = get_static_option('support_ticket_success_message') ?? __('Thanks for contact us, we will reply soon');

        return response()->json(["msg" => $msg,"ticket" => $ticket]);
    }

    public function get_all_tickets(){
        $user_id = auth('sanctum')->user()->id;

        return SupportTicket::where('vendor_id', $user_id)->orderByDesc('id')
            ->paginate(20)->withQueryString();
    }

    public function single_ticket($id){
        $user_id = auth('sanctum')->user()->id;

        $ticket_details = SupportTicket::where('vendor_id', $user_id)
            ->where("id",$id)
            ->first();
        $all_messages = SupportTicketMessage::where(['support_ticket_id' => $id])->get()->transform(function ($item){
            $item->attachment = !empty($item->attachment) ? asset('assets/uploads/ticket/'.$item->attachment) : null;

            return $item;
        });

        return response()->json(["ticket_details" => $ticket_details,"all_messages" => $all_messages]);
    }

    public function fetch_support_chat($ticket_id){
        $all_messages = SupportTicketMessage::where(['support_ticket_id' => $ticket_id])->get()->transform(function ($item){
            $item->attachment = !empty($item->attachment) ? asset('assets/uploads/ticket/'.$item->attachment) : null;

            return $item;
        });

        return response()->json($all_messages);
    }

    public function priority_change(Request $request)
    {
        $request->validate(['priority' => 'required|string|max:191']);

        SupportTicket::findOrFail($request->id)->update([
            'priority' => $request->priority,
        ]);
        return response()->json(['success' => true]);
    }

    public function status_change(Request $request)
    {
        $request->validate(['status' => 'required|string|max:191']);

        SupportTicket::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);
        return response()->json(['success' => true]);
    }

    public function send_support_chat(Request $request,$ticket_id){
        $request->validate([
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip,jpg,jpeg,png,gif',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $ticket_id,
            'type' => $request->user_type,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
            'attachment' => null,
        ]);

        if ($request->hasFile('file')) {
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name = pathinfo($uploaded_file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file_extension;
            $uploaded_file->move('assets/uploads/ticket', $file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        $ticket = $ticket_info->toArray();
        $ticket["attachment"] = empty($ticket["attachment"]) ? null : asset('assets/uploads/ticket/' . $ticket["attachment"]);

        return response()->json($ticket);
    }

}
