<?php

namespace Modules\Product\Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Modules\Product\Database\CustomSeeders\AromaticsProductSeeder;
use Modules\Product\Database\CustomSeeders\CasualProductSeeder;
use Modules\Product\Database\CustomSeeders\CustomProductSeeder;
use Modules\Product\Database\CustomSeeders\ElectronicsProductSeeder;
use Modules\Product\Database\CustomSeeders\FashionProductSeeder;
use Modules\Product\Database\CustomSeeders\FurnitureProductSeeder;
use Throwable;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function run(): void
    {
        \DB::transaction(function (){

            $electronicProducts = ElectronicsProductSeeder::getProducts();
            $electronicCategories = ElectronicsProductSeeder::getCategory();

            CustomProductSeeder::seed($electronicProducts, $electronicCategories);

            $electronicProducts = AromaticsProductSeeder::getProducts();
            $electronicCategories = AromaticsProductSeeder::getCategory();

            CustomProductSeeder::seed($electronicProducts, $electronicCategories);

            $electronicProducts = CasualProductSeeder::getProducts();
            $electronicCategories = CasualProductSeeder::getCategory();

            CustomProductSeeder::seed($electronicProducts, $electronicCategories);

            $electronicProducts = FurnitureProductSeeder::getProducts();
            $electronicCategories = FurnitureProductSeeder::getCategory();

            CustomProductSeeder::seed($electronicProducts, $electronicCategories);

            $electronicProducts = FashionProductSeeder::getProducts();
            $electronicCategories = FashionProductSeeder::getCategory();

            CustomProductSeeder::seed($electronicProducts, $electronicCategories);
        });
    }
}
