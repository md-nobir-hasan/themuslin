<?php

namespace Modules\Vendor\Database\factories;

use App\MediaUpload;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Vendor\Entities\VendorShopInfo;

class VendorShopInfoFactory extends Factory
{
    protected $model = VendorShopInfo::class;

    public function definition(): array
    {
        return [
            'vendor_id' => \Modules\Vendor\Entities\Vendor::inRandomOrder()->first()->id,
            'location' => $this->faker->word(),
            'number' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'facebook_url' => $this->faker->url(),
            'website_url' => $this->faker->url(),
            'logo_id' => MediaUpload::inRandomOrder()->first()->id,
            'cover_photo_id' => MediaUpload::inRandomOrder()->first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
