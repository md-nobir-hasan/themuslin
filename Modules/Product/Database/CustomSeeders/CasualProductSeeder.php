<?php

namespace Modules\Product\Database\CustomSeeders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaravelIdea\Helper\Modules\Attributes\Entities\_IH_Category_QB;
use Modules\Attributes\Entities\Category;

class CasualProductSeeder
{
    public static function getProducts(): array
    {
        return [
            [
                'name' => 'High Heel Wedding Shoes',
                'slug' => 'high-heel-wedding-shoes',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Heel Height approximately 3.0" Platform measures approximately 0.25"| True size to fit.

All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.

This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.

This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!

Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!

 apples and other desserts.',
                'image_id' => '529',
                'price' => '250',
                'sale_price' => '240',
                'cost' => '250',
                'badge_id' => '2',
                'brand_id' => '2',
                'status_id' => '1',
                'product_type' => '1',
                'sold_count' => null,
                'min_purchase' => '1',
                'max_purchase' => '10',
                'is_refundable' => '0',
                'is_in_house' => '1',
                'is_inventory_warn_able' => '1',
                'created_at' => '2022-11-16 10:29:36',
                'updated_at' => '2023-06-24 18:25:10',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Mans Silver Ridge Lite Long Sleeve Shirt',
                'slug' => 'mans-silver-ridge-lite-long-sleeve-shirt-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Neck StyleCollared NeckAbout this Item. Omni-wick - the ultimate moisture management technology for the outdoors. Omni-wick quickly moves moisture from the skin into the fabric where it spreads across the surface to quickly evaporate—keeping you cool and your clothing dry.',
                'image_id' => '532',
                'price' => '774',
                'sale_price' => '533',
                'cost' => '774',
                'badge_id' => null,
                'brand_id' => '2',
                'status_id' => '1',
                'product_type' => '1',
                'sold_count' => null,
                'min_purchase' => '1',
                'max_purchase' => '10',
                'is_refundable' => '0',
                'is_in_house' => '1',
                'is_inventory_warn_able' => '1',
                'created_at' => '2022-11-16 10:30:14',
                'updated_at' => '2023-06-24 18:24:13',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Buck  Long Sleeve Button Down Shirt',
                'slug' => 'buck-long-sleeve-button-down-shirt-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Fabric Type64% Cotton, 34% Polyester, 2% Spandex. Care InstructionsMachine Wash',
                'image_id' => '531',
                'price' => '452',
                'sale_price' => '321',
                'cost' => '452',
                'badge_id' => null,
                'brand_id' => '2',
                'status_id' => '1',
                'product_type' => '1',
                'sold_count' => null,
                'min_purchase' => '1',
                'max_purchase' => '10',
                'is_refundable' => '0',
                'is_in_house' => '1',
                'is_inventory_warn_able' => '1',
                'created_at' => '2022-11-16 10:32:18',
                'updated_at' => '2023-06-24 18:23:52',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Mens Regular Fit Long Sleeve Poplin Jacket',
                'slug' => 'mens-regular-fit-long-sleeve-poplin-jacket-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Fabric Type64% Cotton, 34% Polyester, 2% Spandex',
                'image_id' => '530',
                'price' => '800',
                'sale_price' => '1000',
                'cost' => '800',
                'badge_id' => '3',
                'brand_id' => '2',
                'status_id' => '1',
                'product_type' => '1',
                'sold_count' => null,
                'min_purchase' => '1',
                'max_purchase' => '10',
                'is_refundable' => '0',
                'is_in_house' => '1',
                'is_inventory_warn_able' => '1',
                'created_at' => '2022-11-16 10:37:51',
                'updated_at' => '2023-06-24 18:23:33',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Baby shoes',
                'slug' => 'baby-shoes',
                'summary' => '100% Textile
Synthetic sole
Boys sneaker-style boots with hook and loop closure
High-top styling
Hook and loop closure for easy on-and-off',
                'description' => '100% TextileSynthetic soleBoy’s sneaker-style boots with hook and loop closureHigh-top stylingHook and loop closure for easy on-and-off',
                'image_id' => '537',
                'price' => '223',
                'sale_price' => '200',
                'cost' => '223',
                'badge_id' => null,
                'brand_id' => '2',
                'status_id' => '1',
                'product_type' => '1',
                'sold_count' => null,
                'min_purchase' => '1',
                'max_purchase' => '10',
                'is_refundable' => '0',
                'is_in_house' => '1',
                'is_inventory_warn_able' => '1',
                'created_at' => '2022-11-16 10:51:12',
                'updated_at' => '2023-06-24 18:23:07',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Stylish color  Jersey',
                'slug' => 'stylish-color-jersey',
                'summary' => 'The Blackout Jersey will match with any dirt bike pant, because what doesnt match with black? It has a moisture-wicking main body construction to keep you comfortable while youre putting down laps on the track or miles on the local trail. Plus, it has a perforated mesh fabric, so there is plenty of airflow through this motocross jersey.',
                'description' => '100% PolyesterImportedPull On closureMachine WashBreathable crewneck jersey made for soccerRegular fit is wider at the body, with a straight silhouetteCrewneck provides full coverageThis product is made with recycled content as part of our ambition to end plastic waste',
                'image_id' => '538',
                'price' => '250',
                'sale_price' => '190',
                'cost' => '250',
                'badge_id' => null,
                'brand_id' => '7',
                'status_id' => '1',
                'product_type' => '1',
                'sold_count' => null,
                'min_purchase' => '2',
                'max_purchase' => '10',
                'is_refundable' => '0',
                'is_in_house' => '1',
                'is_inventory_warn_able' => '1',
                'created_at' => '2022-11-16 10:54:10',
                'updated_at' => '2023-06-24 18:22:37',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'High Heel Wedding Shoes',
                'slug' => 'high-heel-wedding-shoes-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Product details',
                'image_id' => '375',
                'price' => '250',
                'sale_price' => '240',
                'cost' => '250',
                'badge_id' => '2',
                'brand_id' => '2',
                'status_id' => '1',
                'product_type' => '1',
                'sold_count' => null,
                'min_purchase' => '1',
                'max_purchase' => '10',
                'is_refundable' => '0',
                'is_in_house' => '1',
                'is_inventory_warn_able' => '1',
                'created_at' => '2022-11-16 11:24:22',
                'updated_at' => '2022-11-16 11:25:58',
                'deleted_at' => '2022-11-16 11:25:58',
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
        ];
    }

    public static function getCategory(): Builder|Model|_IH_Category_QB|Category
    {
        return Category::with('subcategory.childcategory')->where('slug', 'fashion')->first();
    }
}