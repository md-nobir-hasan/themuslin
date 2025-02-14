<?php

namespace Modules\Order\Interface;

interface OrderInterface
{
    static function isVendorMailable();

    static function isMailSendWithQueue();

    static function prepareOrderForVendor();
    static function prepareOrderForAdmin();
    static function cartContent();

    static function cartInstanceName();
    static function groupByColumn();
}