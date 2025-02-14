<?php

namespace Modules\CountryManage\Http\Controllers\Support;

use App\Events\SupportMessage;
use App\Helpers\FlashMsg;
use App\Support\SupportDepartment;
use App\Support\SupportTicket;
use App\Support\SupportTicketMessage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AdminSupportTicketController extends Controller
{
    const BASE_PATH = 'backend.support-ticket.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:support-ticket-page-settings', ['only', ['page_settings', 'update_page_settings']]);

        $this->middleware('permission:support-ticket-list|support-ticket-view|support-ticket-create|support-ticket-delete|support-ticket-priority-change|support-ticket-status-change|support-ticket-send-message', ['only', ['all_tickets']]);
        $this->middleware('permission:support-ticket-view', ['only', ['view']]);
        $this->middleware('permission:support-ticket-create', ['only', ['new_ticket', 'store_ticket', 'all_tickets']]);
        $this->middleware('permission:support-ticket-delete', ['only', ['delete', 'bulk_action', 'all_tickets']]);

        $this->middleware('permission:support-ticket-priority-change', ['only', ['priority_change', 'all_tickets']]);
        $this->middleware('permission:support-ticket-status-change', ['only', ['status_change', 'all_tickets']]);
        $this->middleware('permission:support-ticket-send-message', ['only', ['send_message', 'all_tickets', 'view']]);
    }

    public function page_settings()
    {
        return view(self::BASE_PATH.'page-settings');
    }

    public function update_page_settings(Request $request)
    {
        $field_rules = [
            'support_ticket_login_notice' => 'nullable|string',
            'support_ticket_form_title' => 'nullable|string',
            'support_ticket_button_text' => 'nullable|string',
            'support_ticket_success_message' => 'nullable|string',
        ];

        $request->validate($field_rules);

        foreach ($field_rules as $field => $rule) {
            update_static_option($field, $request->$field);
        }

        return back()->with(FlashMsg::settings_update());
    }

    public function all_tickets()
    {
        $all_tickets = SupportTicket::with('department', 'user')->orderBy('id', 'desc')->get();

        return view(self::BASE_PATH.'all-tickets')->with(['all_tickets' => $all_tickets]);
    }

    public function new_ticket()
    {
        $all_users = User::all();
        $all_departments = SupportDepartment::where(['status' => 'publish'])->get();

        return view(self::BASE_PATH.'new-ticket')->with(['all_users' => $all_users, 'departments' => $all_departments]);
    }

    public function store_ticket(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'priority' => 'required|string|max:191',
            'description' => 'required|string',
            'departments' => 'required|string',
        ], [
            'title.required' => __('title required'),
            'subject.required' => __('subject required'),
            'priority.required' => __('priority required'),
            'description.required' => __('description required'),
            'departments.required' => __('departments required'),
        ]);

        $support_ticket = SupportTicket::create([
            'title' => $request->title,
            'via' => 'admin',
            'operating_system' => null,
            'user_agent' => null,
            'description' => $request->description,
            'subject' => $request->subject,
            'status' => 'open',
            'priority' => $request->priority,
            'user_id' => $request->user_id,
            'departments' => $request->departments,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return $support_ticket->id
            ? back()->with(FlashMsg::create_succeed('Support ticket'))
            : back()->with(FlashMsg::create_failed('Support ticket'));
    }

    public function view(Request $request, $id)
    {
        $ticket_details = SupportTicket::with('admin')->findOrFail($id);
        $all_messages = SupportTicketMessage::where(['support_ticket_id' => $id])->get();
        $q = $request->q ?? '';

        return view(self::BASE_PATH.'view-ticket')->with([
            'ticket_details' => $ticket_details,
            'all_messages' => $all_messages,
            'q' => $q,
        ]);
    }

    public function delete(Request $request, $id)
    {
        SupportTicket::findOrFail($id)->delete();

        return back()->with(FlashMsg::delete_succeed('Support ticket'));
    }

    public function priority_change(Request $request)
    {
        $request->validate(['priority' => 'required|string|max:191']);
        SupportTicket::findOrFail($request->id)->update([
            'priority' => $request->priority,
        ]);

        return 'ok';
    }

    public function status_change(Request $request)
    {
        $request->validate(['status' => 'required|string|max:191']);
        SupportTicket::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);

        return 'ok';
    }

    public function send_message(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $request->ticket_id,
            'type' => $request->user_type,
            'admin_id' => Auth::guard('admin')->user()->id,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
        ]);

        if ($request->hasFile('file')) {
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name = pathinfo($uploaded_file->getClientOriginalName(), PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/ticket', $file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        event(new SupportMessage($ticket_info));

        return back()->with(FlashMsg::settings_update(__('Message send')));
    }

    public function bulk_action(Request $request)
    {
        SupportTicket::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }
}
