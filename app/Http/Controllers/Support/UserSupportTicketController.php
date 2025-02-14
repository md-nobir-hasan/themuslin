<?php

namespace App\Http\Controllers\Support;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use App\Support\SupportTicket;
use App\Support\SupportDepartment;
use Illuminate\Routing\Controller;

class UserSupportTicketController extends Controller
{
    const BASE_PATH = 'frontend.user.dashboard.support-tickets.';

    public function page() {
        $departments = SupportDepartment::where(['status' => 'publish'])->get();
        return view(self::BASE_PATH.'create', compact('departments'));
    }

    public function store(Request $request){
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

        SupportTicket::create([
            'title' => $request->title,
            'via' => $request->via,
            'operating_system' => null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'description' => $request->description,
            'subject' => $request->subject,
            'status' => 'open',
            'priority' => $request->priority,
            'user_id' => auth('web')->user()->id,
            'admin_id' => null,
            'departments' => $request->departments
        ]);

        $msg = get_static_option('support_ticket_success_message') ?? __('Thanks for contact us, we will reply soon');

        return back()->with(FlashMsg::settings_update($msg));
    }
}
