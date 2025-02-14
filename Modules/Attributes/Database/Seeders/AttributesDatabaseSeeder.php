<?php

namespace Modules\Attributes\Database\Seeders;

use App\MediaUpload;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\ProductAttribute;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\SubCategory;
use Modules\Attributes\Entities\Tag;
use Modules\Attributes\Entities\Unit;
use Modules\Badge\Entities\Badge;
use Str;
use Throwable;

class AttributesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run(): void
    {
        \DB::transaction(function (){
            // disable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $categoriesWithSubAndChildCategories = [
                "Electronics" => [
                    "Smartphones" => ["Android Phones", "iPhones", "Refurbished Phones", "Gaming Phones", "Business Phones", "Feature Phones", "Rugged Phones"],
                    "Laptop & Desktops" => ["Ultrabooks", "Gaming Laptops", "Desktop PCs", "MacBooks", "Workstations", "Chromebooks", "All-in-One PCs"],
                    "Audio Devices" => ["Headphones", "Earbuds", "Bluetooth Speakers", "Soundbars", "Microphones", "Turntables", "Portable MP3 Players"],
                    "Cameras & Drones" => ["DSLR Cameras", "Mirrorless Cameras", "Compact Cameras", "Drones", "Action Cameras", "Camcorders", "360 Cameras"],
                    "Smartwatches & Wearables" => ["Fitness Trackers", "Smartwatches for Android", "Apple Watch", "Kids' Smartwatches", "Sport Watches", "Classic Design", "Smart Glasses"],
                    // ... Continue for other subcategories of Electronics
                ],
                "Fashion" => [
                    "Men's Clothing" => ["T-Shirts", "Suits & Blazers", "Casual Shirts", "Jeans", "Shorts", "Outerwear", "Sportswear"],
                    "Women's Clothing" => ["Dresses", "Blouses & Shirts", "Leggings", "Skirts", "Lingerie", "Outerwear", "Maternity Clothes"],
                    "Footwear" => ["Sneakers", "Formal Shoes", "Sandals & Flip Flops", "Heels", "Boots", "Sports Shoes", "Loafers"],
                    "Handbags & Wallets" => ["Totes", "Backpacks", "Satchels", "Wallets", "Clutches", "Crossbody Bags", "Luggage & Travel"],
                    // ... Continue for other subcategories of Fashion
                ],
                "Home & Living" => [
                    "Furniture" => ["Sofas & Couches", "Bed Frames & Sets", "Dining Tables & Chairs", "Wardrobes", "Outdoor Furniture", "Coffee Tables", "Office Furniture"],
                    "Home Decor" => ["Wall Art", "Clocks", "Vases & Pots", "Mirrors", "Rugs & Carpets", "Candles & Holders", "Photo Frames"],
                    "Kitchen & Dining" => ["Cookware", "Tableware", "Bakeware", "Utensils & Gadgets", "Coffee & Tea Sets", "Storage & Organization", "Dinnerware"],
                    // ... Continue for other subcategories of Home & Living
                ],
                // ... Continue this pattern for all the main categories
                "Garden & Outdoors" => [
                    "Gardening" => ["Plant Seeds", "Gardening Tools", "Plant Containers", "Fertilizers", "Pesticides", "Flowering Plants", "Ornamental Plants"],
                    "Outdoor Furniture" => ["Garden Benches", "Swing Chairs", "Hammocks", "Picnic Tables", "Outdoor Umbrellas", "Rattan Furniture", "Deck Chairs"],
                    "BBQ & Outdoor Cooking" => ["Charcoal Grills", "Gas Grills", "BBQ Tools", "Fire Pits", "Outdoor Ovens", "Grill Covers", "Smokers"],
                    "Outdoor Decor" => ["Garden Statues", "Wind Chimes", "Fountains", "Bird Baths", "Decorative Stones", "Solar Lights", "Flags & Banners"],
                ],
                "Sports & Fitness" => [
                    "Team Sports" => ["Football", "Basketball", "Volleyball", "Baseball", "Hockey", "Cricket", "Rugby"],
                    "Fitness Equipment" => ["Treadmills", "Weights", "Resistance Bands", "Yoga Mats", "Ellipticals", "Exercise Bikes", "Rowing Machines"],
                    "Water Sports" => ["Swimming", "Diving", "Kayaking", "Surfing", "Fishing", "Snorkeling", "Water Skiing"],
                    "Camping & Hiking" => ["Tents", "Sleeping Bags", "Camping Cookware", "Hiking Boots", "Backpacks", "Navigation Tools", "Camping Furniture"],
                ],
                "Personal Care & Beauty" => [
                    "Hair Care" => ["Shampoos", "Conditioners", "Hair Oils", "Hair Colors", "Styling Tools", "Hair Masks", "Hair Brushes"],
                    "Skin Care" => ["Moisturizers", "Face Masks", "Cleansers", "Sunscreens", "Serums", "Exfoliators", "Eye Creams"],
                    "Makeup" => ["Foundations", "Lipsticks", "Eyeliners", "Mascaras", "Blushes", "Eyeshadows", "Makeup Removers"],
                    "Men's Grooming" => ["Shaving Creams", "Aftershaves", "Beard Oils", "Trimmers", "Face Washes", "Body Washes", "Deodorants"],
                ],
                "Kids & Babies" => [
                    "Clothing" => ["Infant Wear", "Dresses", "T-Shirts", "School Uniforms", "Sleepwear", "Footwear", "Accessories"],
                    "Toys" => ["Educational Toys", "Soft Toys", "Board Games", "Action Figures", "Puzzles", "Remote Control Toys", "Craft Kits"],
                    "Baby Care" => ["Diapers", "Baby Foods", "Feeding Bottles", "Baby Wipes", "Strollers", "Car Seats", "High Chairs"],
                    "School Supplies" => ["Backpacks", "Stationery", "Lunch Boxes", "School Kits", "Notebooks", "Art Supplies", "Calculators"],
                ],
                "Music & Instruments" => [
                    "String Instruments" => ["Guitars", "Violins", "Ukuleles", "Cellos", "Harps", "Banjos", "Mandolins"],
                    "Percussion" => ["Drums", "Cymbals", "Tambourines", "Congas", "Bongos", "Maracas", "Timpani"],
                    "Wind Instruments" => ["Flutes", "Clarinets", "Saxophones", "Trumpets", "Harmonicas", "Bagpipes", "Oboes"],
                    "Keyboards & Pianos" => ["Electronic Keyboards", "Grand Pianos", "Organs", "Synthesizers", "Harpsichords", "Accordion", "Midi Controllers"],
                ],
                "Automotive & Vehicles" => [
                    "Car Parts & Accessories" => ["Engine Components", "Brakes", "Suspensions", "Lighting", "Audio & Electronics", "Exterior Accessories", "Interior Accessories"],
                    "Motorcycles & Scooters" => ["Street Bikes", "Cruisers", "Off-Road", "Electric Scooters", "Touring", "Parts & Accessories", "Riding Gear"],
                    "RVs & Campers" => ["Class A Motorhomes", "Travel Trailers", "Camper Vans", "Fifth Wheel", "Toy Haulers", "Pop-Up Campers", "Truck Campers"],
                    "Boats & Watercraft" => ["Sailboats", "Motorboats", "Yachts", "Fishing Boats", "Jet Skis", "Kayaks & Canoes", "Boat Parts & Accessories"],
                ],
                "Arts & Crafts" => [
                    "Drawing & Painting" => ["Sketchbooks", "Paints", "Brushes", "Canvases", "Drawing Tools", "Pastels", "Watercolors"],
                    "Sculpting & Ceramics" => ["Clay", "Pottery Tools", "Ceramic Paints", "Kilns", "Mold Making", "Pottery Wheels", "Glazing & Underglazing"],
                    "Needlecraft & Sewing" => ["Fabrics", "Sewing Machines", "Embroidery Kits", "Patterns", "Needles", "Yarns", "Quilting"],
                    "Beadwork & Jewelry Making" => ["Beads", "Findings", "Tools", "Wire & Chain", "Stringing Materials", "Jewelry Kits", "Gemstones"],
                ],
                "Health & Wellness" => [
                    "Vitamins & Supplements" => ["Multivitamins", "Probiotics", "Vitamin C", "Protein Powders", "Fish Oils", "Herbal Supplements", "Minerals"],
                    "Fitness & Exercise" => ["Activity Trackers", "Gym Equipment", "Resistance Bands", "Yoga Mats", "Foam Rollers", "Kettlebells", "Fitness DVDs"],
                    "Alternative Medicine" => ["Essential Oils", "Acupuncture", "Herbal Remedies", "Holistic Health", "Chiropractic", "Aromatherapy", "Homeopathy"],
                    "Personal Care" => ["Oral Care", "Skin Care", "Hair Care", "Foot Care", "Eye Care", "Bath & Body", "Men's Grooming"],
                ],
                "Pets & Animal Supplies" => [
                    "Dogs" => ["Dog Food", "Dog Toys", "Collars & Leashes", "Beds & Furniture", "Grooming", "Training Aids", "Health Supplies"],
                    "Cats" => ["Cat Food", "Litter & Accessories", "Cat Toys", "Furniture & Trees", "Beds & Blankets", "Carriers & Transport", "Grooming"],
                    "Fish & Aquatics" => ["Aquariums", "Decor & Gravel", "Fish Food", "Filters & Pumps", "Water Treatments", "Live Fish", "Heating & Lighting"],
                    "Birds" => ["Cages & Accessories", "Bird Food", "Toys", "Perches & Ladders", "Nesting & Bedding", "Feeders & Waterers", "Health Supplies"],
                ],
                "Books & Literature" => [
                    "Genres" => ["Science Fiction", "Mystery & Thriller", "Romance", "Fantasy", "Non-Fiction", "Biographies", "Children's Books"],
                    "Formats" => ["Audiobooks", "E-Books", "Print", "Magazines", "Comics", "Textbooks", "Journals"],
                    "Languages & Learning" => ["Language Guides", "Educational", "Dictionaries", "Encyclopedias", "Learning Kits", "Grammar Books", "Phrasebooks"],
                    "Collectibles & Antiques" => ["First Editions", "Signed Copies", "Vintage Magazines", "Maps", "Posters", "Art Books", "Historical Documents"],
                ],
                // ... You can continue expanding this pattern for other main categories
            ];

            ChildCategory::query()->forceDelete();
            SubCategory::query()->forceDelete();
            Category::query()->forceDelete();

            foreach ($categoriesWithSubAndChildCategories as $categoryName => $subCategories) {
                $category = Category::create([
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                    'description' => 'Description for ' . $categoryName,
                    'image_id' => MediaUpload::inRandomOrder()->first()->id, // Replace with actual image ID
                    'status_id' => 1, // Replace with actual status ID
                ]);

                foreach ($subCategories as $subCategoryName => $childCategories) {
                    $subCategory = $category->subcategory()->create([
                        'name' => $subCategoryName,
                        'slug' => Str::slug($subCategoryName),
                        'description' => 'Description for ' . $subCategoryName,
                        'image_id' => MediaUpload::inRandomOrder()->first()->id, // Replace with actual image ID
                        'status_id' => 1, // Replace with actual status ID
                    ]);

                    foreach ($childCategories as $childCategoryName) {
                        $subCategory->childcategory()->create([
                            'category_id' => $category->id,
                            'name' => $childCategoryName,
                            'slug' => Str::slug($childCategoryName),
                            'description' => 'Description for ' . $childCategoryName,
                            'image_id' => MediaUpload::inRandomOrder()->first()->id, // Replace with actual image ID
                            'status_id' => 1, // Replace with actual status ID
                        ]);
                    }
                }
            }

            $ecommerceTags = [
                ['tag_text' => 'BestSeller'],
                ['tag_text' => 'Trending'],
                ['tag_text' => 'NewArrival'],
                ['tag_text' => 'Discounted'],
                ['tag_text' => 'LimitedEdition'],
                ['tag_text' => 'Organic'],
                ['tag_text' => 'EcoFriendly'],
                ['tag_text' => 'Handmade'],
                ['tag_text' => 'LocalProduct'],
                ['tag_text' => 'FreeShipping'],
                ['tag_text' => 'BuyOneGetOne'],
                ['tag_text' => 'Seasonal'],
                ['tag_text' => 'Featured'],
                ['tag_text' => 'Exclusive'],
                ['tag_text' => 'HotDeal'],
                ['tag_text' => 'SpecialOffer'],
                ['tag_text' => 'GiftIdea'],
                ['tag_text' => 'PopularChoice'],
                ['tag_text' => 'TopRated'],
                ['tag_text' => 'HighQuality'],
                ['tag_text' => 'UnderBudget'],
                ['tag_text' => 'Luxury'],
                ['tag_text' => 'FlashSale'],
                ['tag_text' => 'BundleOffer'],
                ['tag_text' => 'Recommended'],
                ['tag_text' => 'BackInStock'],
                ['tag_text' => 'FastDelivery'],
                ['tag_text' => 'MoneyBackGuarantee'],
                ['tag_text' => 'PremiumQuality'],
                ['tag_text' => 'EditorsPick'],
                ['tag_text' => 'CustomerFavorite'],
                ['tag_text' => 'MustHave'],
                ['tag_text' => 'Clearance'],
                ['tag_text' => 'Closeout'],
                ['tag_text' => 'HolidaySpecial'],
                ['tag_text' => 'QuickSetup'],
                ['tag_text' => 'WarrantyIncluded'],
                ['tag_text' => 'Refurbished'],
                ['tag_text' => 'Certified'],
                ['tag_text' => 'EnergyEfficient'],
                ['tag_text' => 'Customizable']
            ];

            Tag::query()->forceDelete();
            Tag::insert($ecommerceTags);

            $deliveryOptions = [
                [
                    'icon' => 'la la-rocket',  // Express icon
                    'title' => 'Express Delivery',
                    'sub_title' => 'Get it within 24 hours'
                ],
                [
                    'icon' => 'la la-truck',  // Truck icon for standard delivery
                    'title' => 'Standard Delivery',
                    'sub_title' => '3-5 business days'
                ],
                [
                    'icon' => 'la la-clock',  // Clock icon for same-day delivery
                    'title' => 'Same Day Delivery',
                    'sub_title' => 'Order before 10am'
                ],
                [
                    'icon' => 'la la-calendar-check',  // Scheduled delivery
                    'title' => 'Scheduled Delivery',
                    'sub_title' => 'Choose your preferred time'
                ],
                [
                    'icon' => 'la la-calendar-alt',  // Weekend delivery
                    'title' => 'Weekend Delivery',
                    'sub_title' => 'Saturday & Sunday deliveries'
                ],
                [
                    'icon' => 'la la-moon',  // Evening delivery
                    'title' => 'Evening Delivery',
                    'sub_title' => 'After 6pm deliveries'
                ],
                [
                    'icon' => 'la la-user-slash',  // No contact delivery
                    'title' => 'No Contact Delivery',
                    'sub_title' => 'Leave at the door delivery'
                ]
            ];

            DeliveryOption::query()->forceDelete();
            DeliveryOption::insert($deliveryOptions);


            $brands = [
                // Electronics
                [
                    'name' => 'Samsung',
                    'slug' => 'samsung',
                    'description' => 'Samsung, a global powerhouse in electronics, provides a wide range of products from smartphones to televisions, ensuring cutting-edge technology and innovation at every step.',
                    'title' => 'Samsung Electronics',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Home & Living
                [
                    'name' => 'Ikea',
                    'slug' => 'ikea',
                    'description' => 'IKEA is renowned worldwide for its functional and stylish furniture and home accessories. Offering a vast range of products for every room, IKEA combines modern design with affordability.',
                    'title' => 'IKEA Furniture & Design',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Fashion
                [
                    'name' => 'Zara',
                    'slug' => 'zara',
                    'description' => 'Zara, a trendsetter in the fashion world, offers the latest styles and designs in clothing and accessories. With a keen sense of global fashion trends, Zara brings chic and stylish collections season after season.',
                    'title' => 'Zara Fashion Line',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Health & Beauty
                [
                    'name' => 'L\'Oréal',
                    'slug' => 'loreal',
                    'description' => 'L\'Oréal, a world leader in beauty, provides a diverse range of makeup, skincare, and hair products. Driven by innovation, L\'Oréal is committed to providing quality and excellence in every beauty solution.',
                    'title' => 'L\'Oréal Beauty Products',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Sports & Outdoors
                [
                    'name' => 'Nike',
                    'slug' => 'nike',
                    'description' => 'Nike is synonymous with top-notch sports apparel, footwear, and equipment. Championing innovation and design, Nike pushes boundaries to ensure athletes achieve their best while looking impeccable.',
                    'title' => 'Nike Athletic Gear',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                [
                    'name' => 'TechNest',
                    'slug' => 'technest',
                    'description' => 'TechNest is known for its innovative range of electronics. From smart wearables to cutting-edge laptops, our products are designed for the tech-savvy generation. Our commitment to quality and futuristic design sets us apart in the electronics industry.',
                    'title' => 'TechNest Electronics',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                [
                    'name' => 'EcoPure',
                    'slug' => 'ecopure',
                    'description' => 'EcoPure is dedicated to providing eco-friendly home and lifestyle products. Each product is crafted with care, ensuring minimal environmental impact. Choose EcoPure for a sustainable and green future.',
                    'title' => 'EcoPure Green Living',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                [
                    'name' => 'FitStride',
                    'slug' => 'fitstride',
                    'description' => 'FitStride is a leading brand in athletic footwear. Designed for both casual and professional athletes, our shoes offer maximum comfort, performance, and style. Step up your game with FitStride.',
                    'title' => 'FitStride Athletic Footwear',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                [
                    'name' => 'LuxeDrape',
                    'slug' => 'luxedrape',
                    'description' => 'LuxeDrape brings luxury to your wardrobe with a collection of sophisticated attire. Our pieces are timeless, and our designs merge both classic and contemporary styles. Dress in elegance with LuxeDrape.',
                    'title' => 'LuxeDrape Fashion Line',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                [
                    'name' => 'MunchDelight',
                    'slug' => 'munchdelight',
                    'description' => 'MunchDelight is your destination for gourmet snacks and treats. Made with the finest ingredients, our products offer a unique taste sensation. Indulge in the deliciousness of MunchDelight.',
                    'title' => 'MunchDelight Gourmet Snacks',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Electronics
                [
                    'name' => 'Apple',
                    'slug' => 'apple',
                    'description' => 'Apple is a tech giant renowned for its innovative products, ranging from iPhones to MacBooks. They prioritize user-centric design, intuitive interfaces, and cutting-edge technology.',
                    'title' => 'Apple Tech Innovations',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Home & Living
                [
                    'name' => 'Tempur-Pedic',
                    'slug' => 'tempur-pedic',
                    'description' => 'Tempur-Pedic is celebrated for its memory foam mattresses that adapt to your body shape, ensuring restful sleep. They combine science with luxury, revolutionizing sleep experiences.',
                    'title' => 'Tempur-Pedic Comfort Sleep',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Fashion
                [
                    'name' => 'Gucci',
                    'slug' => 'gucci',
                    'description' => 'Gucci stands as an emblem of luxury in the fashion realm. Their eclectic designs, rich history, and artisanal detail make them a top choice for fashion enthusiasts worldwide.',
                    'title' => 'Gucci Luxury Fashion',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Health & Beauty
                [
                    'name' => 'Clinique',
                    'slug' => 'clinique',
                    'description' => 'Clinique, rooted in dermatological heritage, offers skincare and makeup products that are allergy-tested and 100% fragrance-free, ensuring the best for your skin.',
                    'title' => 'Clinique Skincare and Makeup',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Sports & Outdoors
                [
                    'name' => 'Under Armour',
                    'slug' => 'under-armour',
                    'description' => 'Under Armour produces performance apparel suitable for athletes of all kinds. Their gear is designed to keep sports enthusiasts cool, dry, and light throughout the course of a game, practice, or workout.',
                    'title' => 'Under Armour Performance Gear',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
                // Books & Stationery
                [
                    'name' => 'Moleskine',
                    'slug' => 'moleskine',
                    'description' => 'Moleskine notebooks are beloved by writers, artists, and planners. With their signature bound covers and elastic closure, they offer a touch of classic design in a modern world.',
                    'title' => 'Moleskine Classic Notebooks',
                    'image_id' => MediaUpload::inRandomOrder()->first()->id,
                    'banner_id' => MediaUpload::inRandomOrder()->first()->id,
                ],
            ];

            Brand::query()->forceDelete();
            Brand::insert($brands);

            $badges = [
                [
                    'name' => 'Best Seller',
                    'image' => MediaUpload::inRandomOrder()->first()->id,
                    'for' => 'products',
                    'sale_count' => 1000,
                    'type' => 'sales',
                    'status' => 'active'
                ],
                [
                    'name' => 'New Arrival',
                    'image' => MediaUpload::inRandomOrder()->first()->id,
                    'for' => 'products',
                    'sale_count' => null,
                    'type' => 'arrival',
                    'status' => 'active'
                ],
                [
                    'name' => 'Limited Edition',
                    'image' => MediaUpload::inRandomOrder()->first()->id,
                    'for' => 'products',
                    'sale_count' => null,
                    'type' => 'exclusive',
                    'status' => 'active'
                ],
                [
                    'name' => 'Eco-Friendly',
                    'image' => MediaUpload::inRandomOrder()->first()->id,
                    'for' => 'products',
                    'sale_count' => null,
                    'type' => 'sustainability',
                    'status' => 'active'
                ],
                [
                    'name' => 'Top Rated',
                    'image' => MediaUpload::inRandomOrder()->first()->id,
                    'for' => 'products',
                    'sale_count' => 500,
                    'type' => 'rating',
                    'status' => 'active'
                ]
            ];

            Badge::query()->forceDelete();
            Badge::insert($badges);



            $colors = [
                ["name" => "Red", "slug" => Str::slug("Red"), "color_code" => "#FF0000"],
                ["name" => "Green", "slug" => Str::slug("Green"), "color_code" => "#00FF00"],
                ["name" => "Blue", "slug" => Str::slug("Blue"), "color_code" => "#0000FF"],
                ["name" => "Yellow", "slug" => Str::slug("Yellow"), "color_code" => "#FFFF00"],
                ["name" => "Orange", "slug" => Str::slug("Orange"), "color_code" => "#FFA500"],
                ["name" => "Purple", "slug" => Str::slug("Purple"), "color_code" => "#800080"],
                ["name" => "Pink", "slug" => Str::slug("Pink"), "color_code" => "#FFC0CB"],
                ["name" => "Gray", "slug" => Str::slug("Gray"), "color_code" => "#808080"],
                ["name" => "Black", "slug" => Str::slug("Black"), "color_code" => "#000000"],
                ["name" => "White", "slug" => Str::slug("White"), "color_code" => "#FFFFFF"],
                ["name" => "Brown", "slug" => Str::slug("Brown"), "color_code" => "#A52A2A"],
                ["name" => "Cyan", "slug" => Str::slug("Cyan"), "color_code" => "#00FFFF"],
                ["name" => "Magenta", "slug" => Str::slug("Magenta"), "color_code" => "#FF00FF"],
                ["name" => "Lavender", "slug" => Str::slug("Lavender"), "color_code" => "#E6E6FA"],
                ["name" => "Teal", "slug" => Str::slug("Teal"), "color_code" => "#008080"],
                ["name" => "Lime", "slug" => Str::slug("Lime"), "color_code" => "#00FF00"],
                ["name" => "Gold", "slug" => Str::slug("Gold"), "color_code" => "#FFD700"],
                ["name" => "Silver", "slug" => Str::slug("Silver"), "color_code" => "#C0C0C0"],
                ["name" => "Indigo", "slug" => Str::slug("Indigo"), "color_code" => "#4B0082"],
                ["name" => "Violet", "slug" => Str::slug("Violet"), "color_code" => "#9400D3"],
                ["name" => "Turquoise", "slug" => Str::slug("Turquoise"), "color_code" => "#40E0D0"],
                ["name" => "Maroon", "slug" => Str::slug("Maroon"), "color_code" => "#800000"],
                ["name" => "Beige", "slug" => Str::slug("Beige"), "color_code" => "#F5F5DC"],
                ["name" => "Olive", "slug" => Str::slug("Olive"), "color_code" => "#808000"],
                ["name" => "Navy", "slug" => Str::slug("Navy"), "color_code" => "#000080"],
                ["name" => "Aqua", "slug" => Str::slug("Aqua"), "color_code" => "#00FFFF"],
                ["name" => "Crimson", "slug" => Str::slug("Crimson"), "color_code" => "#DC143C"],
                ["name" => "Slate", "slug" => Str::slug("Slate"), "color_code" => "#708090"],
                ["name" => "Sienna", "slug" => Str::slug("Sienna"), "color_code" => "#A0522D"],
                ["name" => "Pear", "slug" => Str::slug("Pear"), "color_code" => "#D1E231"],
                ["name" => "Cobalt", "slug" => Str::slug("Cobalt"), "color_code" => "#0047AB"],
                ["name" => "Ruby", "slug" => Str::slug("Ruby"), "color_code" => "#E0115F"],
                ["name" => "Sapphire", "slug" => Str::slug("Sapphire"), "color_code" => "#0F52BA"],
                // Add more unique colors as needed
            ];


            Color::query()->forceDelete();
            Color::insert($colors);


            $sizes = [
                ["name" => "Small", "size_code" => "S", "slug" => Str::slug("Small")],
                ["name" => "Medium", "size_code" => "M", "slug" => Str::slug("Medium")],
                ["name" => "Large", "size_code" => "L", "slug" => Str::slug("Large")],
                ["name" => "X-Large", "size_code" => "XL", "slug" => Str::slug("X-Large")],
                ["name" => "XX-Large", "size_code" => "XXL", "slug" => Str::slug("XX-Large")],
                ["name" => "3X-Large", "size_code" => "3XL", "slug" => Str::slug("3X-Large")],
                ["name" => "4X-Large", "size_code" => "4XL", "slug" => Str::slug("4X-Large")],
                ["name" => "5X-Large", "size_code" => "5XL", "slug" => Str::slug("5X-Large")],
                ["name" => "6X-Large", "size_code" => "6XL", "slug" => Str::slug("6X-Large")],
                ["name" => "7X-Large", "size_code" => "7XL", "slug" => Str::slug("7X-Large")],
                // Add more unique size names and size codes as needed
                ["name" => "Petite", "size_code" => "P", "slug" => Str::slug("Petite")],
                ["name" => "Regular", "size_code" => "R", "slug" => Str::slug("Regular")],
                ["name" => "Tall", "size_code" => "T", "slug" => Str::slug("Tall")],
                ["name" => "Plus", "size_code" => "Plus", "slug" => Str::slug("Plus")],
                ["name" => "EU", "size_code" => "EU", "slug" => Str::slug("EU")],
                ["name" => "US", "size_code" => "US", "slug" => Str::slug("US")],
                ["name" => "UK", "size_code" => "UK", "slug" => Str::slug("UK")],
                ["name" => "Twin", "size_code" => "Twin", "slug" => Str::slug("Twin")],
                ["name" => "Full", "size_code" => "Full", "slug" => Str::slug("Full")],
                ["name" => "Queen", "size_code" => "Queen", "slug" => Str::slug("Queen")],
            ];

            Size::query()->forceDelete();
            Size::insert($sizes);

            // Define attributes and terms for different categories
            $attributes = [
                // Electronics
                [
                    'title' => 'Brand',
                    'terms' => json_encode(['Apple', 'Samsung', 'Sony', 'Dell', 'HP', 'Canon', 'Nikon']),
                ],
                [
                    'title' => 'Operating System',
                    'terms' => json_encode(['iOS', 'Android', 'Windows', 'macOS']),
                ],

                // Fashion
                [
                    'title' => 'Material',
                    'terms' => json_encode(['Cotton', 'Leather', 'Polyester', 'Silk', 'Wool']),
                ],
                [
                    'title' => 'Pattern',
                    'terms' => json_encode(['Striped', 'Floral', 'Solid', 'Checkered', 'Plaid']),
                ],

                // Home & Living
                [
                    'title' => 'Furniture Type',
                    'terms' => json_encode(['Sofas', 'Tables', 'Chairs', 'Beds', 'Wardrobes', 'Desks']),
                ],
                [
                    'title' => 'Room',
                    'terms' => json_encode(['Living Room', 'Bedroom', 'Dining Room', 'Office']),
                ],

                // Sports & Outdoor
                [
                    'title' => 'Sport Type',
                    'terms' => json_encode(['Soccer', 'Basketball', 'Tennis', 'Cycling', 'Swimming', 'Hiking']),
                ],

                // Beauty & Personal Care
                [
                    'title' => 'Skin Type',
                    'terms' => json_encode(['Oily Skin', 'Dry Skin', 'Sensitive Skin', 'Normal Skin']),
                ],
                [
                    'title' => 'Hair Type',
                    'terms' => json_encode(['Straight', 'Curly', 'Wavy', 'Coily']),
                ],

                // Kids & Toys
                [
                    'title' => 'Age Group',
                    'terms' => json_encode(['Infants', 'Toddlers', 'Kids', 'Teens']),
                ],
                [
                    'title' => 'Toy Type',
                    'terms' => json_encode(['Action Figures', 'Dolls', 'Building Blocks', 'Board Games']),
                ],

                // Music & Instruments
                [
                    'title' => 'Instrument Type',
                    'terms' => json_encode(['Violin', 'Guitar', 'Drums', 'Flute', 'Piano', 'Saxophone']),
                ],

                // Add more attributes and terms for other categories as needed
            ];

            ProductAttribute::query()->forceDelete();
            ProductAttribute::insert($attributes);

            $units = [
                [
                    'id' => '1',
                    'name' => 'KG',
                    'created_at' => '2022-07-27 09:46:10',
                    'updated_at' => '2022-07-27 09:53:56',
                    'deleted_at' => null,
                ],
                [
                    'id' => '2',
                    'name' => 'Dozzen',
                    'created_at' => '2022-08-01 06:38:02',
                    'updated_at' => '2022-08-07 13:20:24',
                    'deleted_at' => null,
                ],
                [
                    'id' => '3',
                    'name' => 'New',
                    'created_at' => '2022-08-07 13:16:47',
                    'updated_at' => '2022-11-02 13:13:39',
                    'deleted_at' => null,
                ],
                [
                    'id' => '4',
                    'name' => 'Gram (GM)',
                    'created_at' => '2022-08-07 13:17:00',
                    'updated_at' => '2022-08-07 13:17:00',
                    'deleted_at' => null,
                ],
                [
                    'id' => '5',
                    'name' => 'Pound',
                    'created_at' => '2022-08-07 13:17:17',
                    'updated_at' => '2022-08-07 13:17:17',
                    'deleted_at' => null,
                ],
                [
                    'id' => '6',
                    'name' => 'Litter (LT)',
                    'created_at' => '2022-08-07 13:18:02',
                    'updated_at' => '2022-08-07 13:18:02',
                    'deleted_at' => null,
                ],
                [
                    'id' => '7',
                    'name' => 'Ton',
                    'created_at' => '2022-08-07 13:18:10',
                    'updated_at' => '2022-08-07 13:18:10',
                    'deleted_at' => null,
                ],
                [
                    'id' => '8',
                    'name' => 'Piece',
                    'created_at' => '2022-08-07 13:19:18',
                    'updated_at' => '2022-08-07 13:19:18',
                    'deleted_at' => null,
                ],
                [
                    'id' => '9',
                    'name' => 'Box',
                    'created_at' => '2022-08-07 13:19:25',
                    'updated_at' => '2022-08-07 13:20:46',
                    'deleted_at' => null,
                ],
            ];

            Unit::query()->forceDelete();
            Unit::insert($units);

            // Enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }
}
