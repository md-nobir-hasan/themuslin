<?php

namespace Modules\Vendor\Http\Services;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Modules\Order\Entities\SubOrder;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\VendorAddress;
use Modules\Vendor\Entities\VendorBankInfo;
use Modules\Vendor\Entities\VendorShopInfo;
use Modules\Wallet\Entities\Wallet;

class VendorServices
{
    public static function store_vendor($data,$type = "create")
    {
        if($type == "create"){
            return Vendor::create($data);
        }

        return Vendor::where("id",$data["id"])->update($data);
    }

    public static function store_vendor_address($data,$type = "create")
    {
        if($type == "create"){
            return VendorAddress::create($data);
        }

        return VendorAddress::where("id",$data["vendor_id"])->update($data);
    }

    public static function store_vendor_shop_info($data,$type = "create")
    {
        if($type == "create"){
            return VendorShopInfo::create($data);
        }

        return VendorShopInfo::updateOrCreate(["vendor_id" => $data["vendor_id"]], $data);
    }

    public static function store_vendor_bank_info($data,$type = "create")
    {
        if($type == "create") {
            return VendorBankInfo::create($data);
        }

        return VendorBankInfo::where("vendor_id",$data["vendor_id"])->update($data);
    }

    public static function prepare_data_for_update($data,$type): array
    {
        return match ($type){
            "vendor" => ["owner_name" => $data["owner_name"],"username" => $data["username"],"business_name" => $data["business_name"],"business_type_id" => $data["business_type_id"],"description" => $data["description"],"status_id" => $data["status_id"]],
            "vendor_address" => ["vendor_id" => $data["id"],"country_id" => $data["country_id"],"state_id" => $data["state_id"] ?? null,"city_id" => $data["city_id"] ?? null,"zip_code" => $data["zip_code"] ?? null,"address" => $data["address"] ?? null],
            "vendor_shop_info" => ["vendor_id" => $data["id"],"location" => $data["location"],"number" => $data["number"] ?? null,"email" => $data["email"],"facebook_url" => $data["facebook_url"],"website_url" => $data["website_url"],"logo_id" => $data["logo_id"],"cover_photo_id" => $data["cover_photo_id"]],
            "vendor_bank_info" => ["vendor_id" => $data["id"],"bank_name" => $data["bank_name"],"bank_email" => $data["bank_email"],"bank_code" => $data["bank_code"],"account_number" => $data["account_number"]],
        };
    }

    public static function generateReport($vendor_id, $from, $to = null, $type = "year"): Collection|array|null
    {
        if($to == null){
            $to = now()->format('Y-m-d');
        }

        // check report type
        $type = match ($type){
            "year" => "%b",
            "week" => "%a",
        };

        return DB::table('sub_orders')
            ->join('order_tracks', 'sub_orders.order_id', '=', 'order_tracks.order_id')
            ->selectRaw("DATE_FORMAT(order_tracks.created_at, '$type') as date")
            ->selectRaw("IFNULL(SUM(sub_orders.total_amount), 0) as amount")
            ->where('sub_orders.vendor_id', $vendor_id ?? 0)
            ->where('order_tracks.name', 'delivered')
            ->whereBetween('order_tracks.created_at', [$from,$to])
            ->groupBy('date')->get()?->pluck('amount','date') ?? [];
    }

    public static function vendorAccountBanner($type = 'web'): array
    {
        // hare will check type if this method is called for api then auth guard will be sanctum
        $vendor_id = $type == 'web' ? auth()->guard("vendor")->id() : auth('sanctum')->id();
        $wallet = Wallet::where("vendor_id", $vendor_id)->first();

        return [
            "total_order_amount" => (double) SubOrder::where("vendor_id", $vendor_id)->sum("total_amount"),
            "total_complete_order_amount" => (double) SubOrder::where("vendor_id", $vendor_id)
                ->whereHas("orderTrack", function ($order){
                    $order->where("name", "delivered");
                })->sum("total_amount"),
            "pending_balance" => toFixed($wallet->pending_balance ?? 0, 0),
            "current_balance" => toFixed($wallet->balance ?? 0, 0),
            "yearly_income_statement" => self::generateReport($vendor_id, now()->subYear(1)->format("Y-m-d")),
            "weekly_statement" =>  self::generateReport($vendor_id, now()->subWeek(1)->format("Y-m-d"), type: 'week')
        ];
    }
}