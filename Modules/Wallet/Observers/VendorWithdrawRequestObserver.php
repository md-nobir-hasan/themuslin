<?php

namespace Modules\Wallet\Observers;

use Modules\Wallet\Entities\VendorWithdrawRequest;
use Modules\Wallet\Services\VendorWithdrawRequestNotificationService;

class VendorWithdrawRequestObserver
{
    public function created(VendorWithdrawRequest $vendorWithdrawRequest): void
    {
        VendorWithdrawRequestNotificationService::init($vendorWithdrawRequest)
            ->setType("withdraw_request")
            ->send($vendorWithdrawRequest, $vendorWithdrawRequest->request_status);
    }

    public function updated(VendorWithdrawRequest $vendorWithdrawRequest): void
    {
        VendorWithdrawRequestNotificationService::init($vendorWithdrawRequest)
            ->setType("withdraw_request")
            ->setVendor($vendorWithdrawRequest->vendor_id)
            ->send($vendorWithdrawRequest, $vendorWithdrawRequest->request_status);
    }
}
