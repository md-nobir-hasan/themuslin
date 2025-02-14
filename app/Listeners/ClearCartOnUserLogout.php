<?php

namespace App\Listeners;

use App\Helpers\CartHelper;
use App\Helpers\CompareHelper;
use App\Helpers\WishlistHelper;
use Illuminate\Auth\Events\Logout;

class ClearCartOnUserLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Logout $event)
    {
        CartHelper::clear();
        CompareHelper::clear();
        WishlistHelper::clear();
    }
}
