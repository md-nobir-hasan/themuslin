<?php

namespace Modules\Vendor\Database\factories;

use App\MediaUpload;
use App\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Vendor\Entities\BusinessType;
use Modules\Vendor\Entities\Vendor;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'owner_name' => $this->faker->name(),
            'business_name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'username' => $this->faker->userName(),
            'password' => bcrypt(12345678),
            'remember_token' => Str::random(10),
            'image_id' => MediaUpload::inRandomOrder()->first()->id,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified' => $this->faker->unique()->safeEmail(),
            'email_verify_token' => $this->faker->unique()->safeEmail(),
            'commission_type' => $this->faker->word(),
            'commission_amount' => $this->faker->word(),
            'check_online_status' => Carbon::now(),
            'business_type_id' => random_int(1,2),
            'status_id' => 1,
        ];
    }
}
