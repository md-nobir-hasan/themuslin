<?php

namespace Modules\Vendor\Database\factories;

use App\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Vendor\Entities\VendorAddress;

class VendorAddressFactory extends Factory
{
    protected $model = VendorAddress::class;

    public function definition(): array
    {
        return [
            'vendor_id' => \Modules\Vendor\Entities\Vendor::inRandomOrder()->first()->id,
            'zip_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
            'country_id' => 1,
            'state_id' => 1,
            'city_id' => 1,
        ];
    }
}
