<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportTicket\Http\Controllers\SupportTicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**--------------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/


Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    /*==============================================
                    SUPPORT TICKET MODULE
    ==============================================*/
    Route::prefix('support-tickets')->group(function () {
        Route::controller(SupportTicketController::class)->group(function (){
            Route::get('/', 'all_tickets')->name('admin.support.ticket.all')->permission("support-tickets");
            Route::get('/vendor-tickets/', 'all_vendor_tickets')->name('admin.support.ticket.all.vendor')->permission("support-tickets-vendor-tickets");
            Route::get('/new', 'new_ticket')->name('admin.support.ticket.new')->permission("support-tickets-new");
            Route::post('/new', 'store_ticket')->permission("support-tickets-new");
            Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.delete')->permission("support-tickets-delete");
            Route::get('/view/{id}', 'listView')->name('admin.support.ticket.view')->permission("support-tickets-view");
            Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.bulk.action')->permission("support-tickets-bulk-action");
            Route::post('/priority-change', 'priority_change')->name('admin.support.ticket.priority.change')->permission("support-tickets-priority-change");
            Route::post('/status-change', 'status_change')->name('admin.support.ticket.status.change')->permission("support-tickets-status-change");
            Route::post('/send-message', 'send_message')->name('admin.support.ticket.send.message')->permission("support-tickets-send-message");
            /*-------------------------------------------------
                SUPPORT TICKET : PAGE SETTINGS ROUTES
            --------------------------------------------------*/
            Route::get('/page-settings', 'page_settings')->name('admin.support.ticket.page.settings')->permission("support-tickets-page-settings");
            Route::post('/page-settings', 'update_page_settings')->permission("support-tickets-page-settings");
        });

        /*-------------------------------------------------
            SUPPORT TICKET : DEPARTMENT ROUTES
        --------------------------------------------------*/
        Route::group(['prefix' => 'department'], function () {
            Route::controller("SupportDepartmentController")->group(function (){
                Route::get('/', 'category')->name('admin.support.ticket.department')->permission('support-tickets-department');
                Route::post('/', 'new_category')->permission('support-tickets-department');
                Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.department.delete')->permission('support-tickets-department-delete');
                Route::post('/update', 'update')->name('admin.support.ticket.department.update')->permission('support-tickets-department-update');
                Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.department.bulk.action')->permission('support-tickets-department-bulk-action');
            });
        });
    });
});

/**--------------------------------------------------------------------------------------------------------------------------------
 *                          VENDOR PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/
Route::prefix('vendor-home')->middleware(['userEmailVerify','setlang:backend','setlang:backend', 'adminglobalVariable','auth:vendor'])->as("vendor.")->group(function () {
    /*==============================================
                    SUPPORT TICKET MODULE
    ==============================================*/
    Route::prefix('support-tickets')->controller("VendorSupportTicketController")->as("support.")->group(function () {
        Route::get('/', 'all_tickets')->name('ticket.all');
        Route::get('/new', 'new_ticket')->name('ticket.new');
        Route::post('/new', 'store_ticket');
        Route::post('/delete/{id}', 'delete')->name('ticket.delete');
        Route::get('/view/{id}', 'listView')->name('ticket.view');
        Route::post('/bulk-action', 'bulk_action')->name('ticket.bulk.action');
        Route::post('/priority-change', 'priority_change')->name('ticket.priority.change');
        Route::post('/status-change', 'status_change')->name('ticket.status.change');
        Route::post('/send-message', 'send_message')->name('ticket.send.message');
        /*-------------------------------------------------
            SUPPORT TICKET : PAGE SETTINGS ROUTES
        --------------------------------------------------*/
        Route::get('/page-settings', 'page_settings')->name('ticket.page.settings');
        Route::post('/page-settings', 'update_page_settings');
    });
});