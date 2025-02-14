<?php

namespace App\Http\Services;

use App\Http\Traits\CommissionTrait;
use Modules\Vendor\Entities\Vendor;

class Commission
{
    use CommissionTrait;

    private function global_commission_settings(): object
    {
        return (object) [
            'system_type' => get_static_option("system_type"),
            'commission_type' => get_static_option("commission_type"),
            'commission_amount' => get_static_option("commission_amount"),
        ];
    }

    private function setVendor($vendor): Commission
    {
        $commission = new self();
        $commission->vendor = $vendor;
        return $commission;
    }

    private function has_individual_commission(Commission $self): bool
    {
        $commission = $self;
        $vendor = $commission->vendor;

        if(!empty($vendor->commission_type ?? null) && !empty($vendor->commission_amount ?? null) && !empty($vendor)){
            return true;
        }

        return false;
    }

    private function calculate(Commission $self, $sub_total): array
    {
        $commission = $self;

        // check is vendor has commission or not if not then apply global commission
        if($commission->has_individual_commission($commission)){
            // write code for vendor
            // get vendor commission type
            if($commission->vendor->commission_type == 'percentage'){
                return $commission->commissionDataType($commission->vendor->commission_type,($commission->vendor->commission_amount / 100) * $sub_total, true);
            }elseif($commission->vendor->commission_type == 'amount' || $commission->vendor->commission_type == 'fixed_amount'){
                return $commission->commissionDataType($commission->vendor->commission_type,$commission->vendor->commission_amount, true);
            }
        }

        // retrieve global commission information
        $globalCommission = $commission->global_commission_settings();

        if($globalCommission->system_type == 'commission'){
            if($globalCommission->commission_type == 'percentage'){
                return $commission->commissionDataType($globalCommission->commission_type,($globalCommission->commission_amount / 100) * $sub_total, false);
            }elseif($globalCommission->commission_type == 'amount' || $globalCommission->commission_type == 'fixed_amount'){
                return $commission->commissionDataType($globalCommission->commission_type,$globalCommission->commission_amount, false);
            }
        }elseif($globalCommission->system_type == 'subscription'){
            // this feature is no longer available
        }
    }

    private function commissionDataType($type, $amount,$is_individual): array
    {
        return [
            "commission_type" => $type,
            "commission_amount" => $amount,
            "is_commission_individual" => $is_individual
        ];
    }

    public static function get(float $sub_total, ?int $vendor_id): array
    {
        // first need to set vendor id into $vendor property
        // create an instance of this class and store it in a temporary variable
        $commission = new self();
        $commission->vendor = Vendor::select("id","status_id", "commission_type","commission_amount")->find($vendor_id ?? 0);
        // now this vendor is available throughout the class

        // now calculate and return calculated amount
        return $commission->calculate($commission,$sub_total);
    }
}