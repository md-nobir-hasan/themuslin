<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Blog;
use App\ContactInfoItem;
use App\Language;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Campaign\Entities\Campaign;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderPaymentMeta;
use Modules\Order\Entities\SubOrder;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductSellInfo;
use Modules\Vendor\Entities\Vendor;

class AdminDashboardController extends Controller
{
    public function adminIndex()
    {
        // pills
        $total_admin = Admin::count();
        $total_user = User::count();
        // $total_vendor = Vendor::count();
        $all_blogs_count = Blog::count();
        $all_products_count = Product::count();
        $all_completed_sell_count = Order::where('order_status', 'complete')->count();
        $all_pending_sell_count = Order::where('order_status', 'pending')->count();
        $total_ongoing_campaign = Campaign::where('status', 'publish')->count();
        $total_sold_amount = OrderPaymentMeta::whereHas('order', function ($orderQuery) {
            $orderQuery->where('order_status', 'complete');
        })->sum('total_amount');

        // charts
        $sell_per_month = Order::select('id', 'created_at')
            ->where('order_status', 'complete')
            ->where('created_at', '>', Carbon::now()->subYear(1))
            ->get()
            ->groupBy(fn ($query) => Carbon::parse($query->created_at)->format('m'));
        $user_enroll_per_month = User::select('id', 'created_at')
            ->get()
            ->groupBy(fn ($query) => Carbon::parse($query->created_at)->format('m'));

        $yearly_income_statement = SubOrder::whereNull('vendor_id')
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%b') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->subYear(1)->format('Y-m-d'),
                Carbon::now()->addDay(1)->format('Y-m-d')]
            )->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get();

        $weekly_statement = SubOrder::whereNull('vendor_id')
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%a') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->subWeek(1)->format('Y-m-d'),
                Carbon::now()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');

                return $query;
            })
            ->groupBy('date')->get();

        $running_month_earning = SubOrder::whereNull('vendor_id')
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%e') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $last_month_earning = SubOrder::whereNull('vendor_id')
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%e') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d'),
                Carbon::now()->subMonth(1)->endOfMonth()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $this_year_earning = SubOrder::whereNull('vendor_id')
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%e') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->startOfYear()->format('Y-m-d'),
                Carbon::now()->endOfYear()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $running_week_earning = SubOrder::whereNull('vendor_id')
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%a') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->endOfWeek()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $yearly_income_statement = $yearly_income_statement->pluck('amount', 'date');
        $weekly_statement = $weekly_statement->pluck('amount', 'date');

        return view('backend.admin-home')->with([
            'running_month_earning' => $running_month_earning,
            'last_week_earning' => $running_week_earning,
            'last_month_earning' => $last_month_earning,
            'this_year_earning' => $this_year_earning,
            'total_admin' => $total_admin,
            'total_user' => $total_user,
            'all_blogs_count' => $all_blogs_count,
            'all_products_count' => $all_products_count,
            'all_completed_sell_count' => $all_completed_sell_count,
            'total_ongoing_campaign' => $total_ongoing_campaign,
            'all_pending_sell_count' => $all_pending_sell_count,
            'total_sold_amount' => float_amount_with_currency_symbol($total_sold_amount),
            'sell_per_month' => $sell_per_month,
            'user_enroll_per_month' => $user_enroll_per_month,
            // 'total_vendor' => $total_vendor,
            'total_vendor' => [],
            'yearly_income_statement' => $yearly_income_statement,
            'weekly_statement' => $weekly_statement,
        ]);
    }

    public  function health()
    {
        $all_user = Admin::all()->except(Auth::id());

        return view('backend.health')->with(['all_user' => $all_user]);
    }

    public function get_chart_data(Request $request)
    {
        /* -------------------------------------
            TOTAL RAISED BY MONTH CHART DATA
        ------------------------------------- */
        $all_sell_amount = ProductSellInfo::select('total_amount', 'created_at')
            ->whereYear('created_at', date('Y'))
            ->where(['status' => 'complete'])
            ->get()
            ->groupBy(function ($query) {
                return Carbon::parse($query->created_at)->format('M');
            })->toArray();

        $chart_labels = [];
        $chart_data = [];

        foreach ($all_sell_amount as $month => $amount) {
            $chart_labels[] = $month;
            $chart_data[] = array_sum(array_column($amount, 'total_amount'));
        }

        return response()->json([
            'labels' => $chart_labels,
            'data' => $chart_data,
        ]);
    }

    public function get_chart_by_date_data(Request $request)
    {
        /* -----------------------------------------------------
           TOTAL RAISED BY Per Day In last 30 days
       -------------------------------------------------------- */
        $all_sales_total_per_month = ProductSellInfo::select('total_amount', 'created_at')
            ->where(['status' => 'complete'])
                                                // ->whereMonth('created_at',date('m'))
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy(function ($query) {
                return Carbon::parse($query->created_at)->format("D, d M 'y");
            })->toArray();
        $chart_labels = [];
        $chart_data = [];
        foreach ($all_sales_total_per_month as $month => $amount) {
            $chart_labels[] = $month;
            $chart_data[] = array_sum(array_column($amount, 'total_amount'));
        }

        return response()->json([
            'labels' => $chart_labels,
            'data' => $chart_data,
        ]);
    }

    public function getSaleCountPerDayChartData(Request $request)
    {
        /* -----------------------------------------------------
           TOTAL SALES Per Day In last 30 days
       -------------------------------------------------------- */
        $chart_labels = [];
        $chart_data = [];

        $all_sales_per_day = ProductSellInfo::select('id', 'created_at')
            ->where(['status' => 'complete'])
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy(function ($query) {
                return Carbon::parse($query->created_at)->format("D, d M 'y");
            })->toArray();

        foreach ($all_sales_per_day as $date => $sales) {
            $chart_labels[] = $date;
            $chart_data[] = count($sales);
        }

        return response()->json([
            'labels' => $chart_labels,
            'data' => $chart_data,
        ]);
    }

    public function getOrderCountPerDayChartData(Request $request)
    {
        /* -----------------------------------------------------
           TOTAL SALES Per Day In last 30 days
       -------------------------------------------------------- */
        $chart_labels = [];
        $chart_data = [];

        $all_sales_per_day = ProductSellInfo::select('id', 'created_at')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy(function ($query) {
                return Carbon::parse($query->created_at)->format("D, d M 'y");
            })->toArray();

        foreach ($all_sales_per_day as $date => $sales) {
            $chart_labels[] = $date;
            $chart_data[] = count($sales);
        }

        return response()->json([
            'labels' => $chart_labels,
            'data' => $chart_data,
        ]);
    }

    public function logged_user_details()
    {
        $old_details = '';
        if (empty($old_details)) {
            $old_details = User::findOrFail(Auth::guard('web')->user()->id);
        }

        return $old_details;
    }

    public function admin_settings()
    {
        return view('auth.admin.settings');
    }

    public function admin_profile_update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'image' => 'nullable|string|max:191',
        ]);

        Admin::find(Auth::user()->id)->update(['name' => $request->name, 'email' => $request->email, 'image' => $request->image]);

        return redirect()->back()->with(['msg' => __('Profile Update Success'), 'type' => 'success']);
    }

    public function admin_password_chagne(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Admin::findOrFail(Auth::id());

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();

            return redirect()->route('admin.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function adminLogout()
    {
        Auth::guard('admin')->logout();

        return redirect()
            ->route('admin.login')
            ->with(['msg' => __('You Logged Out !!'), 'type' => 'danger']);
    }

    public function admin_profile()
    {
        return view('auth.admin.edit-profile');
    }

    public function admin_password()
    {
        return view('auth.admin.change-password');
    }

    public function contact()
    {
        $all_contact_info_items = ContactInfoItem::all();

        return view('backend.pages.contact')->with([
            'all_contact_info_item' => $all_contact_info_items,
        ]);
    }

    public function update_contact(Request $request)
    {
        $request->validate([
            'page_title' => 'required|string|max:191',
            'get_title' => 'required|string|max:191',
            'get_description' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        update_static_option('contact_page_title', $request->page_title);
        update_static_option('contact_page_get_title', $request->get_title);
        update_static_option('contact_page_get_description', $request->get_description);
        update_static_option('contact_page_latitude', $request->latitude);
        update_static_option('contact_page_longitude', $request->longitude);

        return redirect()->back()->with(['msg' => __('Contact Page Info Update Success'), 'type' => 'success']);
    }

    public function blog_page()
    {
        $all_languages = Language::orderBy('default', 'desc')->get();

        return view('backend.pages.blog')->with(['all_languages' => $all_languages]);
    }

    public function blog_page_update(Request $request)
    {
        $request->validate([
            'blog_page_title' => 'nullable',
            'blog_page_item' => 'nullable',
            'blog_page_category_widget_title' => 'nullable',
            'blog_page_recent_post_widget_title' => 'nullable',
            'blog_page_recent_post_widget_item' => 'nullable',
        ]);
        $blog_page_title = 'blog_page_title';
        $blog_page_item = 'blog_page_item';
        $blog_page_category_widget_title = 'blog_page_category_widget_title';
        $blog_page_recent_post_widget_title = 'blog_page_recent_post_widget_title';
        $blog_page_recent_post_widget_item = 'blog_page_recent_post_widget_item';

        update_static_option('blog_page_title', $request->$blog_page_title);
        update_static_option('blog_page_item', $request->$blog_page_item);
        update_static_option('blog_page_category_widget_title', $request->$blog_page_category_widget_title);
        update_static_option('blog_page_recent_post_widget_title', $request->$blog_page_recent_post_widget_title);
        update_static_option('blog_page_recent_post_widget_item', $request->$blog_page_recent_post_widget_item);

        return redirect()->back()->with(['msg' => __('Blog Settings Update Success'), 'type' => 'success']);
    }

    public function home_variant()
    {
        return view('backend.pages.home.home-variant');
    }

    public function update_home_variant(Request $request)
    {
        $request->validate([
            'home_page_variant' => 'required|string',
        ]);
        update_static_option('home_page_variant', $request->home_page_variant);

        return redirect()->back()->with(['msg' => __('Home Variant Settings Updated..'), 'type' => 'success']);
    }

    public function admin_set_static_option(Request $request)
    {
        $request->validate([
            'static_option' => 'required|string',
            'static_option_value' => 'required|string',
        ]);
        set_static_option($request->static_option, $request->static_option_value);

        return 'ok';
    }

    public function admin_get_static_option(Request $request)
    {
        $request->validate([
            'static_option' => 'required|string',
        ]);
        $data = get_static_option($request->static_option);

        return response()->json($data);
    }

    public function admin_update_static_option(Request $request)
    {
        $request->validate([
            'static_option' => 'required|string',
            'static_option_value' => 'required|string',
        ]);
        update_static_option($request->static_option, $request->static_option_value);

        return 'ok';
    }

    public function dark_mode_toggle(Request $request)
    {
        if ($request->mode == 'off') {
            update_static_option('site_admin_dark_mode', 'on');
        }
        if ($request->mode == 'on') {
            update_static_option('site_admin_dark_mode', 'off');
        }

        return response()->json(['status' => 'done']);
    }
}
