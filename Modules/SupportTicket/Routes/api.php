<?php

use Illuminate\Http\Request;
use Modules\SupportTicket\Http\Controllers\Api\VendorSupportTicketApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix("vendor/v1")->group(function (){
    Route::group(['prefix' => 'auth/', 'middleware' => 'auth:sanctum'], function () {
        Route::get("/get-department", [VendorSupportTicketApiController::class, "get_department"]);
        Route::get("ticket", [VendorSupportTicketApiController::class, "get_all_tickets"]);
        Route::get("ticket/{id}", [VendorSupportTicketApiController::class, "single_ticket"]);
        Route::get("ticket/chat/{ticket_id}", [VendorSupportTicketApiController::class, "fetch_support_chat"]);
        Route::post("ticket/chat/send/{ticket_id}", [VendorSupportTicketApiController::class, "send_support_chat"]);
        Route::post('ticket/message-send', [VendorSupportTicketApiController::class, 'sendMessage']);
        Route::post('ticket/create', [VendorSupportTicketApiController::class, 'createTicket']);
        Route::post('ticket/priority-change', [VendorSupportTicketApiController::class, 'priority_change']);
        Route::post('ticket/status-change', [VendorSupportTicketApiController::class, 'status_change']);
    });
});