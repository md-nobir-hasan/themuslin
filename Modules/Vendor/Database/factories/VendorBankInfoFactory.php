<?php

namespace Modules\Vendor\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\VendorBankInfo;

class VendorBankInfoFactory extends Factory
{
    protected $model = VendorBankInfo::class;

    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::inRandomOrder()->first()->id,
            'bank_name' => $this->faker->name(),
            'bank_email' => $this->faker->unique()->safeEmail(),
            'bank_code' => $this->faker->word(),
            'account_number' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
