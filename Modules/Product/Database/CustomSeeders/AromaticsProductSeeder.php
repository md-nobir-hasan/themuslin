<?php

namespace Modules\Product\Database\CustomSeeders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaravelIdea\Helper\Modules\Attributes\Entities\_IH_Category_QB;
use Modules\Attributes\Entities\Category;

class AromaticsProductSeeder
{
    public static function getProducts(): array
    {
        return [
            [
                'name' => 'Philosopy',
                'slug' => 'philosopy',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Heel Height approximately 3.0" Platform measures approximately 0.25"| True size to fit.

All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.

This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.

This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!

Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!

 apples and other desserts.',
                'image_id' => '505',
                'price' => '60',
                'sale_price' => '50',
                'cost' => '40',
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
                'updated_at' => '2023-05-25 11:27:36',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Maison Francis',
                'slug' => 'maison-francis',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Product Details

Fabric Type100% Polyester

OriginImported

Closure TypeButton

Neck StyleCollared NeckAbout this Item

Omni-wick - the ultimate moisture management technology for the outdoors. Omni-wick quickly moves moisture from the skin into the fabric where it spreads across the surface to quickly evaporate—keeping you cool and your clothing dry.

Handy features: it features two chest pockets to keep your small items secure.

Adjustable features: front button closures and button-down cuffs add adjustable comfort.

Casual fit: with 100% cotton fabric, this women\'s flannel features a casual fit perfect for everyday wear.

Advanced technology: Columbia women\'s silver ridge lite long sleeve shirt features signature wicking fabric that pulls moisture away from the body so sweat can evaporate quickly and UPF 40 sun protection.',
                'image_id' => '504',
                'price' => '60',
                'sale_price' => '55',
                'cost' => '25',
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
                'updated_at' => '2023-05-25 11:24:18',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Dior',
                'slug' => 'dior',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Product Details

Fabric Type64% Cotton, 34% Polyester, 2% Spandex

Care InstructionsMachine Wash

OriginImported

Closure TypeButton

Country of OriginChina',
                'image_id' => '503',
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
                'updated_at' => '2023-05-25 11:21:34',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Chanel',
                'slug' => 'chanel',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Product Details

Fabric Type64% Cotton, 34% Polyester, 2% Spandex

Care InstructionsMachine Wash

OriginImported

Closure TypeButton

Country of OriginChina',
                'image_id' => '502',
                'price' => '40',
                'sale_price' => '30',
                'cost' => '25',
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
                'updated_at' => '2023-05-25 11:21:00',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Yves Saint',
                'slug' => 'yves-saint',
                'summary' => '100% Textile
Synthetic sole
Boys sneaker-style boots with hook and loop closure
High-top styling
Hook and loop closure for easy on-and-off',
                'description' => '100% TextileSynthetic soleBoy’s sneaker-style boots with hook and loop closureHigh-top stylingHook and loop closure for easy on-and-off',
                'image_id' => '501',
                'price' => '50',
                'sale_price' => '40',
                'cost' => '30',
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
                'updated_at' => '2023-05-25 11:20:08',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Viktor and Rolf',
                'slug' => 'viktor-rolf',
                'summary' => 'The Blackout Jersey will match with any dirt bike pant, because what doesnt match with black? It has a moisture-wicking main body construction to keep you comfortable while youre putting down laps on the track or miles on the local trail. Plus, it has a perforated mesh fabric, so there is plenty of airflow through this motocross jersey.',
                'description' => '100% PolyesterImportedPull On closureMachine WashBreathable crewneck jersey made for soccerRegular fit is wider at the body, with a straight silhouetteCrewneck provides full coverageThis product is made with recycled content as part of our ambition to end plastic waste',
                'image_id' => '500',
                'price' => '20',
                'sale_price' => '20',
                'cost' => '15',
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
                'updated_at' => '2023-05-25 11:19:13',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'High Heel Wedding Shoes',
                'slug' => 'high-heel-wedding-shoes-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => '<h3 class="product-facts-title" style="margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: " amazon="" ember",="" arial,="" sans-serif;="" font-weight:="" 700;="" line-height:="" 20px;="" color:="" rgb(15,="" 17,="" 17);="" font-size:="" 16px;="" text-rendering:="" optimizelegibility;"=""><ul class="a-unordered-list a-vertical a-spacing-small" style="margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;"><li style="margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;"><span class="a-list-item a-size-base a-color-secondary" style="margin: 0px; padding: 0px; line-height: 20px !important;">Heel Height approximately 3.0" Platform measures approximately 0.25"| True size to fit.</span></li></ul><ul class="a-unordered-list a-vertical a-spacing-small" style="margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;"><li style="margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;"><span class="a-list-item a-size-base a-color-secondary" style="margin: 0px; padding: 0px; line-height: 20px !important;">All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.</span></li></ul><ul class="a-unordered-list a-vertical a-spacing-small" style="margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;"><li style="margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;"><span class="a-list-item a-size-base a-color-secondary" style="margin: 0px; padding: 0px; line-height: 20px !important;">This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.</span></li></ul><ul class="a-unordered-list a-vertical a-spacing-small" style="margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;"><li style="margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;"><span class="a-list-item a-size-base a-color-secondary" style="margin: 0px; padding: 0px; line-height: 20px !important;">This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!</span></li></ul><ul class="a-unordered-list a-vertical a-spacing-small" style="margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;"><li style="margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;"><span class="a-list-item a-size-base a-color-secondary" style="margin: 0px; padding: 0px; line-height: 20px !important;">Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!</span></li></ul><span style="margin: 0px; padding: 0px; font-size: 14px; color: rgb(51, 51, 51); font-family: &quot;segoe ui&quot;, Helvetica, &quot;droid sans&quot;, Arial, &quot;lucida grande&quot;, tahoma, verdana, arial, sans-serif;">&nbsp;apples and other desserts.</span><br></h3><h3 class="product-facts-title" style="margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: " amazon="" ember",="" arial,="" sans-serif;="" font-weight:="" 700;="" line-height:="" 20px;="" color:="" rgb(15,="" 17,="" 17);="" font-size:="" 16px;="" text-rendering:="" optimizelegibility;"=""><div class="a-fixed-left-grid product-facts-detail" style="margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: " amazon="" ember",="" arial,="" sans-serif;"=""></div></h3>',
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
            [
                'name' => 'Escentric',
                'slug' => 'escentric',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Heel Height approximately 3.0" Platform measures approximately 0.25"| True size to fit.

All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.

This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.

This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!

Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!

 apples and other desserts.',
                'image_id' => '506',
                'price' => '80',
                'sale_price' => '70',
                'cost' => '50',
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
                'created_at' => '2023-05-25 11:26:00',
                'updated_at' => '2023-05-25 11:27:09',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Alezabeth Aden',
                'slug' => 'alezabeth-aden',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Heel Height approximately 3.0" Platform measures approximately 0.25"| True size to fit.

All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.

This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.

This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!

Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!

 apples and other desserts.',
                'image_id' => '507',
                'price' => '120',
                'sale_price' => '90',
                'cost' => '50',
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
                'created_at' => '2023-05-25 11:27:42',
                'updated_at' => '2023-05-25 11:29:02',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Alezabeth Aden',
                'slug' => 'alezabeth-aden-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Heel Height approximately 3.0" Platform measures approximately 0.25"| True size to fit.

All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.

This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.

This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!

Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!

 apples and other desserts.',
                'image_id' => '507',
                'price' => '120',
                'sale_price' => '90',
                'cost' => '50',
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
                'created_at' => '2023-05-29 10:47:35',
                'updated_at' => '2023-05-29 10:47:38',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Maison Francis',
                'slug' => 'maison-francis-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Product Details

Fabric Type100% Polyester

OriginImported

Closure TypeButton

Neck StyleCollared NeckAbout this Item

Omni-wick - the ultimate moisture management technology for the outdoors. Omni-wick quickly moves moisture from the skin into the fabric where it spreads across the surface to quickly evaporate—keeping you cool and your clothing dry.

Handy features: it features two chest pockets to keep your small items secure.

Adjustable features: front button closures and button-down cuffs add adjustable comfort.

Casual fit: with 100% cotton fabric, this women\'s flannel features a casual fit perfect for everyday wear.

Advanced technology: Columbia women\'s silver ridge lite long sleeve shirt features signature wicking fabric that pulls moisture away from the body so sweat can evaporate quickly and UPF 40 sun protection.',
                'image_id' => '504',
                'price' => '60',
                'sale_price' => '55',
                'cost' => '25',
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
                'created_at' => '2023-05-29 10:47:42',
                'updated_at' => '2023-05-29 10:47:47',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Yves Saint',
                'slug' => 'yves-saint-1',
                'summary' => '100% Textile
Synthetic sole
Boys sneaker-style boots with hook and loop closure
High-top styling
Hook and loop closure for easy on-and-off',
                'description' => '100% TextileSynthetic soleBoy’s sneaker-style boots with hook and loop closureHigh-top stylingHook and loop closure for easy on-and-off',
                'image_id' => '501',
                'price' => '50',
                'sale_price' => '40',
                'cost' => '30',
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
                'created_at' => '2023-05-29 10:47:52',
                'updated_at' => '2023-05-29 10:47:55',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Escentric',
                'slug' => 'escentric-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Heel Height approximately 3.0" Platform measures approximately 0.25"| True size to fit.

All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.

This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.

This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!

Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!

 apples and other desserts.',
                'image_id' => '506',
                'price' => '80',
                'sale_price' => '70',
                'cost' => '50',
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
                'created_at' => '2023-05-29 10:48:04',
                'updated_at' => '2023-05-29 10:48:07',
                'deleted_at' => null,
                'is_taxable' => '0',
                'tax_class_id' => null,
            ],
            [
                'name' => 'Dior',
                'slug' => 'dior-1',
                'summary' => 'No Import Fees Deposit and $25.56 Shipping to Bangladesh',
                'description' => 'Product Details

Fabric Type64% Cotton, 34% Polyester, 2% Spandex

Care InstructionsMachine Wash

OriginImported

Closure TypeButton

Country of OriginChina',
                'image_id' => '503',
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
                'created_at' => '2023-05-29 10:48:23',
                'updated_at' => '2023-05-29 10:48:25',
                'deleted_at' => null,
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