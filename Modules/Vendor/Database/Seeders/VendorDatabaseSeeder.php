<?php

namespace Modules\Vendor\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\VendorAddress;
use Modules\Vendor\Entities\VendorBankInfo;
use Modules\Vendor\Entities\VendorShopInfo;
use Modules\Wallet\Entities\Wallet;

class VendorDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // first delete old data
        VendorBankInfo::query()->forceDelete();
        VendorShopInfo::query()->forceDelete();
        VendorAddress::query()->forceDelete();
        Vendor::query()->forceDelete();

        // now create new vendor
        Vendor::factory(20)->create();
        VendorAddress::factory(20)->create();
        VendorShopInfo::factory(20)->create();
        VendorBankInfo::factory(20)->create();

        $wallets = [];
        for($i = 1; $i <= 200; $i++){
            $wallets[] = [
                'vendor_id' => $i,'balance' => 0,'pending_balance' => 0,'status' => 1
            ];
        }

        Wallet::insert($wallets);

//        Model::unguard();

//        $this->call("OthersTableSeeder");
    }
}
