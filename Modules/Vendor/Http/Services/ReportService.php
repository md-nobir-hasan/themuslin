<?php

namespace Modules\Vendor\Http\Services;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function generateReport($vendor_id, $reportType): ?Collection
    {
        $subOrderQuery = DB::table('sub_orders')
            ->join('order_tracks', 'sub_orders.order_id', '=', 'order_tracks.order_id')
            ->selectRaw("DATE_FORMAT(order_tracks.created_at, '%a') as date")
            ->selectRaw("IFNULL(SUM(sub_orders.total_amount), 0) as amount")
            ->where('sub_orders.vendor_id', $vendor_id)
            ->where('order_tracks.name', 'delivered')
            ->whereBetween('order_tracks.created_at', $this->getDateRange($reportType))
            ->groupBy('date');


//        $subOrderQuery = DB::table('sub_orders')
//            ->join('order_tracks', 'sub_orders.order_id', '=', 'order_tracks.order_id')
//            ->selectRaw("DATE_FORMAT(order_tracks.created_at, '%a') as date")
//            ->selectRaw("IFNULL(SUM(sub_orders.total_amount), 0) as amount")
//            ->where('sub_orders.vendor_id', $vendor_id ?? 0)
//            ->where('order_tracks.name', 'delivered')
//            ->whereBetween('order_tracks.created_at', $this->getDateRange($reportType))
//            ->groupBy('date')->get();;

        $result = $subOrderQuery->get();
        return $result->pluck('amount', 'date');
    }

    private function getDateFormat($reportType): string
    {
        return $reportType === 'yearly' ? '%b' : '%a';
    }

    private function getDateRange($reportType): array
    {
        $now = now();
        if ($reportType === 'yearly') {
            return [
                $now->subYear(1)->format('Y-m-d'),
                $now->format('Y-m-d')
            ];
        } else {
            return [
                $now->subWeek(1)->format('Y-m-d'),
                $now->format('Y-m-d')
            ];
        }
    }
}
