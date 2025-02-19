<?php

namespace App\Http\Controllers;

use App\Action\CartAction;
use App\Action\CompareAction;
use App\Admin;
use App\AdminShopManage;
use App\Blog;
use App\BlogCategory;
use App\ContactInfoItem;
use App\PaymentGateway;
use Exception;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use App\Exceptions\NotArrayObjectException;
use App\Faq;
use App\HeaderSlider;
use App\Helpers\CartHelper;
use App\Helpers\CompareHelper;
use App\Helpers\FlashMsg;
use App\Helpers\HomePageStaticSettings;
use App\Helpers\WishlistHelper;
use App\Http\Services\CartService;
use App\Language;
use App\Mail\AdminResetEmail;
use App\Mail\BasicMail;
use App\Newsletter;
use App\Page;
use App\Shipping\ShippingAddress;
use App\Shipping\UserShippingAddress;
use App\StaticOption;
use App\Tax\CountryTax;
use App\Tax\StateTax;
use App\User;
use App\SubmittedForm;
use Illuminate\Http\RedirectResponse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\SubCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\Unit;
use Modules\Campaign\Entities\Campaign;
use Modules\CountryManage\Entities\City;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductRating;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUom;
use Modules\Product\Entities\SaleDetails;
use Modules\Product\Services\FrontendProductServices;
use Modules\ShippingModule\Http\ShippingZoneServices;
use Modules\TaxModule\Entities\TaxClassOption;
use Modules\TaxModule\Services\CalculateTaxBasedOnCustomerAddress;
use Modules\TaxModule\Services\CalculateTaxServices;
use Modules\Vendor\Entities\Vendor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Http\Services\Api\ApiProductServices;
use Modules\Product\Entities\ProductInventoryDetail;
use Illuminate\Support\Facades\Http;


class FrontendController extends Controller
{

    public function index()
    {
        $home_slider      = Page::where(['slug' => 'home-slider', 'status' => 'publish'])->first();
        $service_slider   = Page::where(['slug' => 'service-info', 'status' => 'publish'])->first();
        $selling_products = Product::where('status_id', 1)
            ->with('campaign_product', 'inventoryDetail', 'inventory', 'campaign_sold_product', 'category')
            ->orderBy('sold_count', 'DESC')
            ->take(request()->limit ?? 15)
            ->get();


        $featured         = Category::where(['status_id' => 1, 'featured' => 1])->first();

        $categories       = Category::where(['status_id' => 1, 'show_home' => 1])->orderBy('sort_order', 'asc')->get();
        $subcategories    = SubCategory::where(['status_id' => 1, 'show_home' => 1])->orderBy('sort_order', 'asc')->get();
        $childcategories  = ChildCategory::where(['status_id' => 1, 'show_home' => 1])->orderBy('sort_order', 'asc')->get();
        $top_category = [];

        if (!empty($categories)) {
            foreach ($categories as $key => $value) {
                $top_category[(int)$value->sort_order] = $value;
            }
        }

        if (!empty($subcategories)) {
            foreach ($subcategories as $key => $value) {
                $top_category[(int)$value->sort_order] = $value;
            }
        }

        if (!empty($childcategories)) {
            foreach ($childcategories as $key => $value) {

                $top_category[(int)$value->sort_order] = $value;
            }
        }

        ksort($top_category);


        $campaign =  Campaign::with(['products', 'products.product'])->get();

        return view('muslin.home')->with([
            'home_slider' => $home_slider,
            'service_slider' => $service_slider,
            'selling_products' => $selling_products,
            'top_category' => $top_category,
            'featured' => $featured,
            'campaign' => $campaign
        ]);
    }


    public function category($slug)
    {
        $slug = $slug == 'woman' ? 'sharee' : $slug;

        $category = Category::where(['status_id' => 1, 'slug' => $slug])->first();
        $products = collect(); // Initialize an empty collection


        $category_slug = '';
        $subcategory_slug = '';
        $childcategory_slug = '';

        $sub_categories = [];
        $child_categories = [];

        $i = 0;
        $perPage = 30; // Number of products per page


        if (!empty($category)) {

            $product_ids = ProductCategory::where(['category_id' => $category->id])
                ->pluck('product_id')
                ->toArray();

            $products = Product::whereIn('id', $product_ids)
                ->with('category')
                ->where(['status_id' => 1])
                ->paginate($perPage);

            $i = 1;
        } else {

            $category = SubCategory::where(['status_id' => 1, 'slug' => $slug])->first();

            if (!empty($category)) {
                $product_ids = ProductSubCategory::where(['sub_category_id' => $category->id])
                    ->pluck('product_id')
                    ->toArray();

                $products = Product::whereIn('id', $product_ids)
                    ->with('category')
                    ->where(['status_id' => 1])
                    ->paginate($perPage);

                $i = 2;
            } else {
                $category = ChildCategory::where(['status_id' => 1, 'slug' => $slug])->first();

                if (!empty($category)) {
                    $product_ids = ProductChildCategory::where(['child_category_id' => $category->id])
                        ->pluck('product_id')
                        ->toArray();

                    $products = Product::whereIn('id', $product_ids)
                        ->with('category')
                        ->where(['status_id' => 1])
                        ->paginate($perPage);

                    $i = 3;
                }
            }
        }

        $parent_categories = Category::where(['status_id' => 1])->get();

        if ($i == 1) {
            $category_slug = $category->slug;
            $sub_categories = SubCategory::where(['status_id' => 1, 'category_id' => $category->id])->get();
            $child_categories = [];
        } else if ($i == 2) {
            $category_slug = !empty($category->category->slug) ? $category->category->slug : '';
            $subcategory_slug = $category->slug;

            $sub_categories = !empty($category->category->subcategory) ? $category->category->subcategory : [];
            $child_categories = ChildCategory::where(['status_id' => 1, 'sub_category_id' => $category->id])->get();
        } else if ($i == 3) {
            $category_slug = $category->category->slug;
            $subcategory_slug = $category->sub_category->slug;
            $childcategory_slug = $category->slug;

            $sub_categories = !empty($category->category->subcategory) ? $category->category->subcategory : [];
            $child_categories = !empty($category->sub_category->childcategory) ? $category->sub_category->childcategory : [];
        }

        $colors = ProductInventoryDetail::select('color')->distinct()->where('color', '!=', null)->get();
        $sizes = ProductInventoryDetail::select('size')->distinct()->where('size', '!=', null)->get();

        return view('muslin.category')->with([
            'products' => $products,
            'category' => $category,
            'parent_categories' => $parent_categories,
            'category_slug' => $category_slug,
            'subcategory_slug' => $subcategory_slug,
            'childcategory_slug' => $childcategory_slug,
            'sub_categories' => $sub_categories,
            'child_categories' => $child_categories,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }

    public function blogs()
    {
        $blogs = Blog::where(['status' => 'publish'])
            ->orderBy('date', 'desc')
            ->take(12)
            ->get();

        return view('muslin.blogs')->with(['blogs' => $blogs]);
    }


    public function blogDetail($slug)
    {
        $blog = Blog::where(['status' => 'publish', 'slug' => $slug])->first();

        return view('muslin.blog-detail')->with(['blog' => $blog]);
    }


    public function about()
    {
        $data = Page::where(['slug' => 'about', 'status' => 'publish'])->first();
        $ceo  = Page::where(['slug' => 'ceo', 'status' => 'publish'])->first();

        if (!empty($data)) {

            return view('muslin.about')->with(['data' => $data, 'ceo' => $ceo]);
        }

        abort(404);
    }


    public function contact()
    {
        $data  = Page::where(['slug' => 'contact', 'status' => 'publish'])->first();

        if (!empty($data)) {

            return view('muslin.contact')->with(['data' => $data]);
        }

        abort(404);
    }

    public function contactSubmit(Request $request)
    {
        if (empty($request->post('spam_protector'))) {

            $data = [
                'first_name' => $request->post('first_name'),
                'last_name'  => $request->post('last_name'),
                'phone'      => $request->post('phone'),
                'email'      => $request->post('email'),
                'message'    => $request->post('message'),
                'status'     => 1,
                'created_at' => date('Y-m-d H:i'),
            ];

            $form = SubmittedForm::create($data);

            if ($form) {

                return redirect()->back()->with('success', 'Your message has been submitted. ');
            }
        }

        return redirect()->back()->with('error', 'Invalid data');
    }


    public function faq()
    {
        $group  = Faq::where('status', 'publish')->distinct()->pluck('faq_group')->toArray();
        $data   = Faq::where(['status' => 'publish'])->get();

        return view('muslin.faq')->with(['data' => $data, 'group' => $group]);
    }


    public function product($slug)
    {
        $date = now();
        $product = Product::where('slug', $slug)
            ->with([
                'category',
                'color',
                'size',
                'campaign_product' => function ($campaignProduct) use ($date) {
                    $campaignProduct->whereDate("end_date", ">=", $date)->whereDate("start_date", "<=", $date);
                },
                'inventoryDetail',
                'inventoryDetail.productColor',
                'inventoryDetail.productSize',
                'inventoryDetail.attribute',
                'inventory',
                'gallery_images',
                'productDeliveryOption',
                // 'campaign_sold_product',

            ])
            ->where("status_id", 1)
            ->firstOrFail();

        // get selected attributes in this product ( $available_attributes )
        $inventoryDetails = optional($product->inventoryDetail);
        $product_inventory_attributes = $inventoryDetails->toArray();

        $all_included_attributes = array_filter(array_column($product_inventory_attributes, 'attribute', 'id'));
        $all_included_attributes_prd_id = array_keys($all_included_attributes);


        $available_attributes = [];  // FRONTEND : All displaying attributes
        $product_inventory_set = []; // FRONTEND : attribute_store
        $additional_info_store = []; // FRONTEND : $additional_info_store

        foreach ($all_included_attributes as $id => $included_attributes) {
            $single_inventory_item = [];
            foreach ($included_attributes as $included_attribute_single) {
                $available_attributes[$included_attribute_single['attribute_name']][$included_attribute_single['attribute_value']] = 1;
                $single_inventory_item[$included_attribute_single['attribute_name']] = $included_attribute_single['attribute_value'];

                if (optional($inventoryDetails->find($id))->productColor) {
                    $single_inventory_item['Color'] = optional(optional($inventoryDetails->find($id))->productColor)->name;
                }

                if (optional($inventoryDetails->find($id))->productSize) {
                    $single_inventory_item['Size'] = optional(optional($inventoryDetails->find($id))->productSize)->name;
                }
            }

            $item_additional_price = optional(optional($product->inventoryDetail)->find($id))->additional_price ?? 0;
            $item_additional_stock = optional(optional($product->inventoryDetail)->find($id))->stock_count ?? 0;
            $image = get_attachment_image_by_id(optional(optional($product->inventoryDetail)->find($id))->image)['img_url'] ?? '';

            $product_inventory_set[] = $single_inventory_item;

            $sorted_inventory_item = $single_inventory_item;
            ksort($sorted_inventory_item);

            $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                'pid_id' => $id, // ProductInventoryDetails->id
                'additional_price' => $item_additional_price,
                'stock_count' => $item_additional_stock,
                'image' => $image,
            ];
        }

        $productColors = $product->color->unique();
        $productSizes = $product->size->unique();

        if ((empty($available_attributes) && !empty($product_inventory_attributes)) || count($all_included_attributes) < $product->inventoryDetail->count()) {
            $sorted_inventory_item = [];
            $product_id = $product_inventory_attributes[0]['id'];
            // check inventory color and size exists or not

            if (!empty($product->inventoryDetail)) {
                foreach ($product->inventoryDetail as $inventory) {
                    // if this inventory has attributes, then it will fire a continue statement
                    if (in_array($inventory->product_id, $all_included_attributes_prd_id)) {
                        continue;
                    }

                    $single_inventory_item = [];

                    if (optional($inventoryDetails->find($product_id))->color) {
                        $single_inventory_item['Color'] = optional($inventory->productColor)->name;
                    }

                    if (optional($inventoryDetails->find($product_id))->size) {
                        $single_inventory_item['Size'] = optional($inventory->productSize)->name;
                    }

                    $product_inventory_set[] = $single_inventory_item;

                    $item_additional_price = optional($inventory)->additional_price ?? 0;
                    $item_additional_stock = optional($inventory)->stock_count ?? 0;
                    $image = get_attachment_image_by_id(optional($inventory)->image)['img_url'] ?? '';

                    $sorted_inventory_item = $single_inventory_item;
                    ksort($sorted_inventory_item);

                    $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                        // 'pid_id' => $product_id,
                        'pid_id' => $inventory->id,
                        'additional_price' => $item_additional_price,
                        'stock_count' => $item_additional_stock,
                        'image' => $image,
                    ];
                }
            }
        }

        // related products
        $product_category = $product?->category?->id;
        $product_id = $product->id;
        $related_products = Product::with('campaign_product', 'campaign_sold_product', 'reviews', 'inventory', 'badge', 'uom')->where('status_id', 1)
            ->whereIn('id', function ($query) use ($product_id, $product_category) {
                $query->select('product_categories.product_id')
                    ->from(with(new ProductCategory())->getTable())
                    ->where('product_id', '!=', $product_id)
                    ->where('category_id', '=', $product_category)
                    ->get();
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        // (bool) Check logged-in user bought this item (needed for review)
        $user = getUserByGuard('web');


        if (empty($product_inventory_set[0] ?? [])) {
            $product_inventory_set = "";
        }
        $countries = Country::where('status', 'publish')->get();

        return view('muslin.product', compact(
            'product',
            'related_products',
            'product_inventory_set',
            'additional_info_store',
            'productColors',
            'productSizes',
            'countries'
        ));
    }





    public function returnPolicy()
    {
        $data  = Page::where(['slug' => 'pricing-delivery-return-policy', 'status' => 'publish'])->first();

        if (!empty($data)) {

            return view('muslin.general')->with(['data' => $data]);
        }

        abort(404);
    }


    public function privacyPolicy()
    {

        $data  = Page::where(['slug' => 'privacy-policy', 'status' => 'publish'])->first();

        if (!empty($data)) {

            return view('muslin.general')->with(['data' => $data]);
        }

        abort(404);
    }


    public function termsCondition()
    {
        $data  = Page::where(['slug' => 'terms-condition', 'status' => 'publish'])->first();

        if (!empty($data)) {

            return view('muslin.general')->with(['data' => $data]);
        }

        abort(404);
    }





    public function homeSearch(Request $request)
    {
        $request->validate([
            'search_query' => 'nullable|string|max:191'
        ]);

        $all_products = FrontendProductServices::productSearch($request, "frontend.ajax");

        $products = $all_products["items"];
        // unset($all_products["items"]);

        $categories   = Category::where(['status_id' => 1])->get();

        $colors = ProductInventoryDetail::select('color')->distinct()->where('color', '!=', null)->get();
        $sizes = ProductInventoryDetail::select('size')->distinct()->where('size', '!=', null)->get();

        return view('muslin.search', [
            'products' => $products,
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
            'all_products' => $all_products
        ]);
    }


    public function categoryInfo(Request $request)
    {
        $type = $request->post('type');
        $slug = $request->post('ids');

        $html = '';
        $html_2 = '<option value="">Select</option>';

        if ($type == 'category') {

            $category_ids = Category::where('status_id', 1)
                ->whereIn('slug', $slug) // Assuming $slug is an array of slugs
                ->pluck('id');

            $category_ids = $category_ids->toArray();

            if (!empty($category_ids))
                $subcategories = SubCategory::where('status_id', 1)
                    ->whereIn('category_id', $category_ids)
                    ->get();
            if (!empty($subcategories)) {

                foreach ($subcategories as $key => $sub) {

                    $html .= "<li>
                                    <input type='radio' name='subcategory' class='search-param' value=" . $sub->slug . " />
                                    <label>" . $sub->name . "</label>
                              </li>";

                    $html_2 .= "<option value='" . $sub->id  . "'>" . $sub->name . "</option>";
                }
            }
        } else if ($type == 'subcategory') {

            $category_ids = SubCategory::where('status_id', 1)
                ->whereIn('slug', $slug) // Assuming $slug is an array of slugs
                ->pluck('id');

            $category_ids = $category_ids->toArray();

            if (!empty($category_ids))

                $childcategories = ChildCategory::where('status_id', 1)
                    ->whereIn('sub_category_id', $category_ids)
                    ->get();

            if (!empty($childcategories)) {

                foreach ($childcategories as $key => $sub) {

                    $html .= "<li>
                                    <input type='checkbox' name='childcategory'  value=" . $sub->slug . " />
                                    <label>" . $sub->name . "</label>
                              </li>";
                    $html_2 .= "<option value='" . $sub->id  . "'>" . $sub->name . "</option>";
                }
            }
        }

        return response()->json(['type' => 'success', 'html' => $html, 'html_2' => $html_2]);
    }

    public function categoryInfoBackend(Request $request)
    {
        $type = $request->post('type');
        $slug = $request->post('ids');

        // Default value for the options HTML
        $html_2 = '<option value="">Select</option>';

        if ($type === 'category') {
            $category = Category::where('status_id', 1)
                ->where('slug', $slug)
                ->first();

            // Check if the category exists and if subcategories are found
            if ($category) {
                $subcategories = SubCategory::where('status_id', 1)
                    ->where('category_id', $category->id)
                    ->get();

                if ($subcategories->isNotEmpty()) {
                    foreach ($subcategories as $sub) {
                        $html_2 .= "<option value='" . $sub->id . "'>" . $sub->name . "</option>";
                    }
                }
            }
        } elseif ($type === 'subcategory') {
            // Fetch the subcategory where the status is active (1) and matches the category slug
            $subCategory = SubCategory::where('status_id', 1)
                ->where('category_id', $slug)
                ->first();
        
            // Check if the subcategory exists
            if ($subCategory) {
                // Fetch the child categories associated with the subcategory
                $childCategories = ChildCategory::where('status_id', 1)
                    ->where('sub_category_id', $subCategory->id)
                    ->get();
        
                // Check if there are child categories available
                if ($childCategories->isNotEmpty()) {
                    foreach ($childCategories as $child) {
                        $html_2 .= "<option value='" . $child->id  . "'>" . $child->name . "</option>";
                    }
                }
            }
        }
        

        return response()->json(['type' => 'success', 'html_2' => $html_2]);
    }



    public function productSearch(Request $request)
    {
        $all_products = FrontendProductServices::productSearch($request, 'frontend.ajax');

        $html = '';
        $message = '';
        $paginationHtml = '';


        $products = !empty($all_products['items']) ? $all_products['items'] : [];

        if (!empty($products)) {
            foreach ($products as $product) {

                $category = !empty($product->category) ?  $product->category->name : '';
                $image = $product->image;

                $html .= '<div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                <div class="single-item">
                                    <a href="' . route('product.details', $product->slug) . '">
                                        <div class="single-item__img">' .
                    render_image($image, class: 'modify-img lazyloads') .
                    '</div>
                                        <div class="single-item__content">
                                            <span>' . $category . '</span>
                                            <h6>' . $product->name . '</h6>
                                        </div>
                                    </a>
                                </div>
                            </div> ';
            }

            if ($all_products['total_items'] > 0 && $all_products['total_page'] == 1) {

                $message = 'Showing 1 to ' . $all_products['total_items'] . ' of ' . $all_products['total_items'] . ' results';
            } else if ($all_products['total_page'] > 1) {

                $current_page = $all_products['current_page'];
                $total_pages = $all_products['total_page'];
                $next_page = $all_products['next_page'];
                $previous_page = $all_products['previous_page'];
                $links = $all_products['links'];

                $first_item = (int)$current_page > 1 ? (int)$current_page * 30 - 30 + 1 : 1;
                $last_item = (int)$current_page * 30 <= $all_products['total_items'] ? (int)$current_page * 30 : $all_products['total_items'];

                $message = 'Showing ' . $first_item . ' to ' . $last_item . ' of ' . $all_products['total_items'] . ' results';


                // Previous Page Link
                if ($previous_page) {
                    $paginationHtml .= '<li><a class="ajax-search" href="#" data-id="' . $current_page - 1 . '" rel="prev">«</a></li>';
                } else {
                    $paginationHtml .= '<li class="disabled"><span>«</span></li>';
                }

                // Page Number Links
                foreach ($links as $page => $url) {
                    if ($page == $current_page) {
                        $paginationHtml .= '<li class="active"><span>' . $page . '</span></li>';
                    } else {
                        $paginationHtml .= '<li><a class="ajax-search" href="#" data-id="' . $page . '">' . $page . '</a></li>';
                    }
                }

                // Next Page Link
                if ($current_page < $total_pages) {
                    $paginationHtml .= '<li><a class="ajax-search" href="#" data-id="' . $current_page + 1 . '" rel="next">»</a></li>';
                } else {
                    $paginationHtml .= '<li class="disabled"><span>»</span></li>';
                }

                // Wrap the HTML in the pagination container
                $paginationHtml = '<ul class="pagination">' . $paginationHtml . '</ul>';
            } else {
                $html = '<div class="col-lg-4 col-md-6 col-sm-6 col-6"><p>No product found</p></div>';
            }
        } else {

            $html = '<div class="col-lg-4 col-md-6 col-sm-6 col-6"><p>No product found</p></div>';
        }

        return response()->json(['type' => 'success', 'html' => $html, 'pagination' => $paginationHtml, 'message' => $message]);
    }


    public function campaignDetails($slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->with(['products', 'products.product'])
            ->where("status", 'publish')
            ->firstOrFail();

        $products = optional($campaign->products);



        return view('muslin.campaign')->with(['campaign' => $campaign, 'products' => $products]);
    }























    /** ===================================================================
     *                  ADMIN AUTH FUNCTIONS
     * ===================================================================*/
    public function showAdminForgetPasswordForm()
    {
        return view('auth.admin.forget-password');
    }

    public function sendAdminForgetPasswordMail(Request $request)
    {
        $request->validate(['username' => 'required|string:max:191']);

        $user_info = Admin::where('username', $request->username)->orWhere('email', $request->username)->first();

        if (! empty($user_info)) {
            $token_id = Str::random(30);
            $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
            if (empty($existing_token)) {
                DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);
            }
            $message = 'Here is you password reset link, If you did not request to reset your password just ignore this mail. <a class="btn" href="' . route('admin.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '">Click Reset Password</a>';
            $data = [
                'username' => $user_info->username,
                'message' => $message,
            ];

            try {
                Mail::to($user_info->email)->send(new AdminResetEmail($data));
            } catch (Exception $e) {
                return redirect()->back()->with([
                    'msg' => $e->getMessage(),
                    'type' => 'success',
                ]);
            }

            return redirect()->back()->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success',
            ]);
        }

        return redirect()->back()->with([
            'msg' => __('Your Username or Email Is Wrong!!!'),
            'type' => 'danger',
        ]);
    }

    public function showAdminResetPasswordForm($username, $token)
    {
        return view('auth.admin.reset-password')->with([
            'username' => $username,
            'token' => $token,
        ]);
    }

    public function AdminResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user_info = Admin::where('username', $request->username)->first();
        $user = Admin::findOrFail($user_info->id);
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (! empty($token_iinfo)) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('admin.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function lang_change(Request $request)
    {
        session()->put('lang', $request->lang);

        return redirect()->route('homepage');
    }

    /** ======================================================================================
     *                  OTHER PAGE FUNCTIONS
     * ======================================================================================*/


    public function showUserForgetPasswordForm()
    {
        return view('frontend.user.forget-password');
    }

    public function sendUserForgetPasswordMail(Request $request)
    {
        $request->validate([
            'username' => 'required|string:max:191',
        ]);

        $user_info = User::where('username', $request->username)
            ->orWhere('email', $request->username)->first();

        if (! empty($user_info)) {
            $token_id = Str::random(30);
            $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
            if (empty($existing_token)) {
                DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);
            }

            $message = __('Here is you password reset link, If you did not request to reset your password just ignore this mail.') . ' <a class="btn" href="' . route('user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '">' . __('Click Reset Password') . '</a>';
            $data = [
                'username' => $user_info->username,
                'message' => $message,
            ];
            try {
                Mail::to($user_info->email)->send(new AdminResetEmail($data));
            } catch (Exception $e) {
                return redirect()->back()->with([
                    'type' => 'danger',
                    'msg' => $e->getMessage(),
                ]);
            }

            return redirect(route('user.home'))->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success',
            ]);
        }

        return redirect()->back()->with([
            'msg' => __('Your Username or Email Is Wrong!!!'),
            'type' => 'danger',
        ]);
    }

    public function showUserResetPasswordForm($username, $token)
    {
        return view('frontend.user.reset-password')->with([
            'username' => $username,
            'token' => $token,
        ]);
    }

    public function UserResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user_info = User::where('username', $request->username)->first();
        $user = User::findOrFail($user_info->id);
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (! empty($token_iinfo)) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function ajax_login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ], [
            'username.required' => __('username required'),
            'password.required' => __('password required'),
            'password.min' => __('password length must be 6 characters'),
        ]);

        $login_key = 'username';
        // check username is contained valid email than user will log in by usign email and password
        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $login_key = 'email';
        }

        if (Auth::guard('web')->attempt([$login_key => $request->username, 'password' => $request->password], $request->get('remember'))) {
            return response()->json([
                'msg' => __('login Success Redirecting'),
                'type' => 'danger',
                'status' => 'valid',
                'user_identification' => random_int(11111111, 99999999) . auth()->guard('web')->id() . random_int(11111111, 99999999),
            ]);
        }

        return response()->json([
            'msg' => ($login_key == 'email' ? 'Email' : 'Username') . __(' Or Password Doest Not Matched !!!'),
            'type' => 'danger',
            'status' => 'invalid',
        ]);
    }

    public function user_campaign()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.campaign.new');
        }

        return view('frontend.user.login')->with(['title' => __('Login To Create New Campaign')]);
    }

    /** ======================================================================
     *                  USER SHIPPING ADDRESS
     * ======================================================================*/
    public function addUserShippingAddress(Request $request)
    {
        if (! auth('web')->check()) {
            return back()->with(FlashMsg::explain('danger', __('Please login to add new address')));
        }

        $request->validate([
            'name' => 'required|string|max:191',
            'address' => 'required|string|max:191',
        ]);

        $UserShippingAddress = UserShippingAddress::create([
            'user_id' => auth('web')->user()->id,
            'name' => $request->name,
            'address' => $request->address,
        ]);

        $all_user_shipping = UserShippingAddress::where('user_id', auth('web')->user()->id)->get();

        return view('frontend.cart.checkout-user-shipping', compact('all_user_shipping'));
    }

    /** ======================================================================
     *                  FRONTEND PRODUCT FUNCTIONS
     * ======================================================================*/
    public function getProductAttributeHtml(Request $request)
    {
        $product = Product::where('slug', $request->slug)->first();
        if ($product) {
            return view('frontend.partials.product-attributes', compact('product'));
        }
    }

    /** ======================================================================
     *                  CART FUNCTIONS
     * ======================================================================*/
    public function cartPage(Request $request)
    {
        $default_shipping_cost = CartAction::getDefaultShippingCost();

        $all_cart_items = CartHelper::getItems();

        // validate stock count here ...
        CartAction::validateItemQuantity();

        $all_cart_items = CartHelper::getItems();

        $products = Product::whereIn('id', array_keys($all_cart_items))->get();

        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);
        $subtotal_with_tax = $subtotal + $default_shipping_cost;
        $total = CartAction::calculateCoupon($request, $subtotal_with_tax, $products);

        return view('frontend.cart.all', compact('all_cart_items', 'products', 'subtotal', 'default_shipping_cost', 'total'));
    }

    public function checkoutPage(Request $request): Application|Factory|View
    {
        $default_shipping_cost = CartAction::getDefaultShippingCost();
        $default_shipping = CartAction::getDefaultShipping();
        $user = getUserByGuard('web');
        $all_user_shipping = [];

        if (auth('web')->check()) {
            $all_user_shipping = ShippingAddress::with(['get_states', 'country:id,name', 'state:id,name'])->where('user_id', getUserByGuard('web')->id)->get();
        }

        $countries = Country::where('status', 'publish')->get();

        // if not campaign
        $all_cart_items = Cart::content();

        $prd_ids = $all_cart_items?->pluck('id')?->toArray();

        $products = Product::with('category', 'subCategory', 'childCategory')->whereIn('id', $prd_ids)->get();

        $subtotal = Cart::subtotal(2, '.', '');
        $subtotal_with_tax = $subtotal + $default_shipping_cost;
        $coupon_amount = CartService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT', $default_shipping_cost);

        $tax_data = CartService::getDefaultTax($subtotal);
        $tax = $tax_data['tax'];
        $tax_percentage = $tax_data['tax_percentage'];

        $total = CartService::calculateCoupon($request, $subtotal_with_tax, $products);

        $setting_text = StaticOption::select('option_name', 'option_value')->whereIn('option_name', [
            'checkout_page_no_product_text',
            'returning_customer_text',
            'toggle_login_text',
            'checkout_username',
            'checkout_password',
            'checkout_remember_text',
            'checkout_forgot_password',
            'checkout_login_btn_text',
            'have_coupon_text',
            'enter_coupon_text',
            'coupon_placeholder',
            'apply_coupon_btn_text',
            'checkout_billing_section_title',
            'checkout_billing_city',
            'checkout_billing_zipcode',
            'checkout_billing_address',
            'checkout_billing_email',
            'checkout_billing_phone',
            'checkout_order_note',
            'create_account_text',
            'create_account_username',
            'create_account_password',
            'create_account_confirmed_password',
            'ship_to_another_text',
            'shipping_state',
            'shipping_state',
            'shipping_state',
            'shipping_city',
            'shipping_zipcode',
            'shipping_address',
            'shipping_email',
            'shipping_phone',
            'order_summary_title',
            'subtotal_text',
            'discount_text',
            'vat_text',
            'shipping_text',
            'total_text',
            'checkout_place_order',
            'checkout_return_cart',
            'checkout_page_terms_text',
            'checkout_page_terms_link_url',
        ])->pluck('option_value', 'option_name')->toArray();

        return view('frontend.cart.checkout', compact(
            'all_cart_items',
            'all_user_shipping',
            'products',
            'subtotal',
            'countries',
            'default_shipping_cost',
            'default_shipping',
            'total',
            'user',
            'coupon_amount',
            'tax',
            'tax_percentage',
            'setting_text'
        ));
    }


    public function cartCheckoutPage(Request $request): View|Factory|RedirectResponse|Application
    
        $cart = Cart::instance('default')->content();

        $default_shipping_cost = CartAction::getDefaultShippingCost();
        $default_shipping = CartAction::getDefaultShipping();
        $user = getUserByGuard('web');
        $all_user_shipping = [];

        if (auth('web')->check()) {
            $all_user_shipping = ShippingAddress::with(['get_states', 'country:id,name', 'state:id,name'])->where('user_id', getUserByGuard('web')->id)->get();
        }

        $all_country = Country::where('status', 'publish')
            ->where('id', 1)
            ->get()->toArray();
        $all_country = array_column($all_country, 'name', 'id');

        // if not campaign
        $all_cart_items = Cart::content();

        //if product is out of stock remove it from cart
        $removeAbleProductId = [];

        foreach ($cart as $item) {

            $product = Product::where('id', $item->id)->first();
            $product_inventory = ProductInventory::where('product_id', $item->id)->first();

            if ($product_inventory->stock_count < $item->qty) {
                $removeAbleProductId[] = $item->id;
            }


            // if ($item->options->variant_id) {
            //     $product_inventory_details = ProductInventoryDetail::where('id', $item->options->variant_id)->first();
            // }

            // if ($product_inventory_details) {

            //     // if ($product_inventory_details->stock_count < $item->qty) {
            //     //     $removeAbleProductId[] = $item->id;
            //     //     dd($removeAbleProductId);
            //     //     exit();
            //     // }
            // } 
            // else {
            //     if ($product_inventory->stock_count < $item->qty) {
            //         $removeAbleProductId[] = $item->id;
            //     }
            // }

        }


        if ($cart && isset($removeAbleProductId)) {

            $removedItemsCount = 0;

            foreach ($cart as $item) {
                if (in_array($item->id, $removeAbleProductId)) {
                    Cart::instance('default')->remove($item->rowId); // Remove item using rowId
                    $removedItemsCount++;
                }
            }

            // Set a flash message if any items were removed
            if ($removedItemsCount > 0) {
                session()->flash('error', $removedItemsCount . ' Item(s) have been removed due to stock unavailability.');
            }

            if ($cart->count() < 1) {

                Cart::instance('default')->destroy();
                return redirect()->route('homepage');
            }
        }


        //if product is out of stock remove it from cart end

        $prd_ids = $all_cart_items?->pluck('id')?->toArray();

        $products = Product::with('category', 'subCategory', 'childCategory')->whereIn('id', $prd_ids)->get();

        $subtotal = Cart::subtotal(2, '.', '');
        $subtotal_with_tax = $subtotal + $default_shipping_cost;
        $coupon_amount = CartService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT', $default_shipping_cost);
//        dd($products);
        // $tax_data = CartService::getDefaultTax($subtotal);
        // $tax = $tax_data['tax'];
        // $tax_percentage = $tax_data['tax_percentage'];

        $total = CartService::calculateCoupon($request, $subtotal_with_tax, $products);


        return view('muslin.checkout', compact(
            'all_cart_items',
            'all_user_shipping',
            'products',
            'subtotal',
            'all_country',
            'default_shipping_cost',
            'default_shipping',
            'total',
            'user',
            'coupon_amount',
            // 'tax',
            // 'tax_percentage',
        ));
    }

    /**
     * @throws NotArrayObjectException
     * @throws Throwable
     */
    public function cartItemsBasedOnBillingAddress(Request $request)
    {
        $carts = Cart::instance('default')->content();
        $itemsTotal = null;
        $enableTaxAmount = ! CalculateTaxServices::isPriceEnteredWithTax();
        $shippingTaxClass = TaxClassOption::where('class_id', get_static_option('shipping_tax_class'))->sum('rate');
        $tax = CalculateTaxBasedOnCustomerAddress::init();
        $uniqueProductIds = $carts->pluck('id')->unique()->toArray();

        $country_id = $request->country_id ?? 0;
        $state_id = $request->state_id ?? 0;
        $city_id = $request->city_id ?? 0;

        if (empty($uniqueProductIds)) {
            $taxProducts = collect([]);
        } else {
            if (CalculateTaxBasedOnCustomerAddress::is_eligible()) {
                $taxProducts = $tax
                    ->productIds($uniqueProductIds)
                    ->customerAddress($country_id, $state_id, $city_id)
                    ->generate();
            } else {
                $taxProducts = collect([]);
            }
        }

        $carts = $carts->groupBy('options.vendor_id');

        $vendors = Vendor::with('shippingMethod', 'shippingMethod.zone')
            ->whereIn('id', array_keys($carts?->toArray() ?? []))->get();

        $cartItems = view('frontend.cart.cart-items.cart-items-wrapper', compact('enableTaxAmount', 'itemsTotal', 'carts', 'vendors', 'taxProducts', 'shippingTaxClass'))->render();

        $id = null;
        $type = null;

        if (empty($state_id) && empty($city_id)) {
            $id = $country_id;
            $type = 'country';
        } elseif (empty($city_id)) {
            $id = $state_id;
            $type = 'state';
        }

        // prepare data for send response
        $data = ShippingZoneServices::getMethods($id, $type);
        $taxAmount = get_static_option('tax_system') == 'zone_wise_tax_system' ? ['tax_amount' => $data?->tax_amount] : [];
        $states = $type == 'country' ? ['states' => $data?->states] : [];
        $cities = $type == 'state' ? ['cities' => $data?->cities] : [];

        return response()->json([
            'cart_items' => $cartItems,
        ] + $taxAmount + $states + $cities);
    }

    /** ======================================================================
     *                  WISHLIST FUNCTIONS
     * ======================================================================*/
    public function wishlistPage(Request $request)
    {
        $all_wishlist_items = WishlistHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_wishlist_items))->get();

        return view('frontend.wishlist.all', compact('all_wishlist_items', 'products'));
    }

    /** ======================================================================
     *                  COMPARE FUNCTIONS
     * ======================================================================*/
    public function productsComparePage()
    {
        $all_compare_items = CompareHelper::getItems();
        $all_compare_items = [
            array_pop($all_compare_items),
            array_pop($all_compare_items),
        ];

        $products = Product::with('additionalInfo', 'category', 'inventory')
            ->whereIn('id', $all_compare_items)
            ->get();
        $product_ids = $products->pluck('id')->toArray();

        $categories = CompareAction::getCategories($products);
        $all_attributes = CompareAction::getAllProductsAttributes($products);

        return view('frontend.compare.all', compact(
            'all_compare_items',
            'products',
            'product_ids',
            'categories',
            'all_attributes'
        ));
    }

    /** ======================================================================
     *                  PRODUCTS FILTER FUNCTIONS
     * ======================================================================*/
    public function topRatedProducts(): View|Factory|string|Application
    {
        $products = Product::where('status_id', 1)
            ->withAvg('ratings', 'rating')
            ->with('campaign_product', 'inventoryDetail', 'inventory', 'campaign_sold_product')
            ->orderBy('ratings_avg_rating', 'DESC')
            ->take(request()->limit ?? 5)
            ->get();

        if (\request()->isMethod('post')) {
            if (\request()->style == 'two') {
                return view('frontend.partials.product_filter_style_two', compact('products'))->render();
            }
        }

        return view('frontend.partials.filter-item', compact('products'));
    }

    public function topSellingProducts()
    {
        $products = Product::where('status_id', 1)
            ->withAvg('ratings', 'rating')
            ->with('campaign_product', 'inventoryDetail', 'inventory', 'campaign_sold_product')
            ->orderBy('sold_count', 'DESC')
            ->take(request()->limit ?? 5)
            ->get();

        if (\request()->isMethod('post')) {
            if (\request()->style == 'two') {
                return view('frontend.partials.product_filter_style_two', compact('products'))->render();
            }
        }

        return view('frontend.partials.filter-item', compact('products'));
    }

    public function newProducts()
    {
        $products = Product::where('status_id', 1)
            ->withAvg('ratings', 'rating')
            ->with('campaign_product', 'inventoryDetail', 'inventory', 'campaign_sold_product')
            ->orderBy('created_at', 'DESC')
            ->take(request()->limit ?? 5)
            ->get();

        if (\request()->isMethod('post')) {
            if (\request()->style == 'two') {
                return view('frontend.partials.product_filter_style_two', compact('products'))->render();
            }
        }

        return view('frontend.partials.filter-item', compact('products'));
    }

    public function campaignProduct(Request $req)
    {
        $limit = $this->validated_item_count($req);
        $products = Product::where('status', 'publish')
            ->withAvg('rating', 'rating')
            ->join('campaign_products', 'campaign_products.product_id', '=', 'products.id')
            ->orderBy('campaign_products.id', 'DESC')
            ->where('campaign_products.end_date', '>', date('Y-m-d H:i:s'))
            ->take($limit)
            ->get();

        return view('frontend.partials.product_filter_style_two', compact('products'))->render();
    }

    public function discountedProduct(Request $req)
    {
        $limit = $this->validated_item_count($req);

        $products = Product::where('status', 'publish')
            ->withAvg('rating', 'rating')
            ->with('inventory')
            ->where('price', '>', '0')
            ->orderBy('products.id', 'DESC')
            ->take($limit)
            ->get();

        return view('frontend.partials.product_filter_style_two', compact('products'))->render();
    }

    private function validated_item_count($req)
    {
        if ($req->limit ?? false) {
            $data = Validator::make($req->all(), ['limit' => 'required']);

            return $data->safe()->only('limit')['limit'];
        }

        return null;
    }

    public function filterCategoryProducts(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_categories',
            'item_count' => 'required|numeric',
        ]);

        $products = Product::where('status', 'publish')
            ->where('category_id', $request->id)
            ->withAvg('rating', 'rating')
            ->with('rating')
            ->take($request->item_count)
            ->get();

        return view('frontend.partials.filter-item', compact('products'));
    }

    /** ======================================================================
     *                          CAMPAIGN PAGE
     * ======================================================================*/
    public function campaignPage($id, $any = '')
    {
        $campaign = Campaign::with(['products', 'products.product'])->findOrFail($id);
        $products = optional($campaign->products);

        return view('frontend.campaign.index', compact('campaign'));
    }

    /** ======================================================================
     *                          FRONTEND ACTION FUNCTIONS
     * ======================================================================*/
    public function changeSiteCurrency(Request $request)
    {
        $request->validate(['currency' => 'required|string|max:191']);
        if (array_key_exists($request->currency, getAllCurrency())) {
            update_static_option('site_global_currency', $request->currency);
        }

        return true;
    }

    public function changeSiteLanguage(Request $request)
    {
        $language = Language::where('slug', $request->language)->first();
        session()->put('lang', $request->language);

        return response()->json(
            FlashMsg::explain(
                'success',
                sprintf(__('Language changed to %s'), $language->name)
            )
        );
    }

    /** =====================================================================
     *                          AJAX FUNCTIONS
     * ===================================================================== */
    public function getCountryInfo(Request $request)
    {
        $request->validate(['id' => 'required|exists:countries']);

        //        $country_tax = CountryTax::where('country_id', $request->id)->first();
        $country_tax = 0;
        $shipping_options = getCountryShippingCost('country', $request->id);
        $default_shipping = CartAction::getDefaultShipping();
        $default_shipping_cost = CartAction::getDefaultShippingCost();
        $states = State::select('id', 'name')->where('country_id', $request->id)->get();
        $tax = $country_tax ? $country_tax->tax_percentage : 0;

        return response()->json([
            'tax' => $tax,
            'states' => $states,
            'shipping_options' => $shipping_options,
            'default_shipping' => $default_shipping,
            'default_shipping_cost' => $default_shipping_cost,
        ], 200);
    }

    public function getCountryStateInfo(Request $request)
    {
        $request->validate(['id' => 'required']);

        $states = State::select('id', 'name')->where('country_id', $request->id)->get();
        $html = "<option value=''>Select State</option>";
        foreach ($states as $state) {
            $html .= "<option value='" . $state->id . "'>" . $state->name . '</option>';
        }

        return $html;
    }

    public function getCountryCityInfo(Request $request)
    {
        $request->validate(['id' => 'required']);

        $cities = City::select('id', 'name')->where('state_id', $request->id)->get();
        $html = "<option value=''>" . __('Select City') . '</option>';
        foreach ($cities as $city) {
            $html .= "<option value='" . $city->id . "'>" . $city->name . '</option>';
        }

        return $html;
    }

    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->get();

        $html = "<option value=''>" . __('Select State') . '</option>';
        foreach ($states as $state) {
            $html .= "<option value='" . $state->id . "'>" . $state->name . '</option>';
        }

        $list = "<li data-value='' class='option'>" . __('Select State') . '</li>';
        foreach ($states as $state) {
            $list .= "<li data-value='" . $state->id . "' class='option'>" . $state->name . '</option>';
        }

        return response()->json(['success' => true, 'data' => $html, 'list' => $list]);
    }

    public function getStateInfo(Request $request)
    {
        $request->validate(['id' => 'required|exists:states']);

        $state_tax = StateTax::where('state_id', $request->id)->first();
        $default_shipping = CartAction::getDefaultShipping();
        $default_shipping_cost = CartAction::getDefaultShippingCost();
        $shipping_options = getCountryShippingCost('state', $request->id);
        $tax = $state_tax ? $state_tax->tax_percentage : 0;

        return response()->json([
            'tax' => $tax,
            'shipping_options' => $shipping_options,
            'default_shipping' => $default_shipping,
            'default_shipping_cost' => $default_shipping_cost,
        ], 200);
    }

    /**
     * @return Application|Factory|View
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function fallbackProductPage($page_post = null, $vendor = null)
    {
        $page_name = $page_post->name ?? 'Product';
        $display_item_count = request()->get('count') ?? 15;
        $all_category = Category::where('status_id', '1')->with('subcategory', 'subcategory.childcategory')->withCount('product')->get();
        $all_attributes = ProductAttribute::all();
        $all_tags = [];
        $all_units = Unit::all();
        $all_colors = Color::whereHas('product')->get();
        $all_sizes = Size::whereHas('product')->get();
        $all_brands = Brand::whereHas('product')->get();

        $maximum_available_price = Product::query()->max('price');

        $min_price = request()->get('pr_min') ?? Product::query()->min('sale_price');
        $max_price = request()->get('pr_max') ?? $maximum_available_price;

        $item_style = request()->get('s') ?? 'grid';
        $sort_by = request()->get('sort');

        $request = request();
        if (! empty($vendor)) {
            $request->vendor_username = $vendor->username;
        }

        if ($request->count ?? true) {
            $request->count = 16;
        }

        $all_products = FrontendProductServices::productSearch($request, 'frontend.ajax');

        if (count($all_products['items'] ?? []) <= $display_item_count) {
            request()->page = 1;
        }

        return view('frontend.dynamic-redirect.product', compact(
            'all_category',
            'all_attributes',
            'all_tags',
            'all_colors',
            'all_sizes',
            'all_units',
            'all_products',
            'all_brands',
            'min_price',
            'max_price',
            'display_item_count',
            'sort_by',
            'maximum_available_price',
            'item_style',
            'page_post',
            'page_name',
            'vendor'
        ));
    }

    private function fallbackBlogPage($page_post = null)
    {
        $page_name = $page_post->name ?? 'Blog';
        $all_blogs = Blog::with('category')->where('status', 'publish')->paginate();

        return view('frontend.dynamic-redirect.blog', [
            'padding_top' => 100,
            'padding_bottom' => 100,
            'all_blogs' => $all_blogs,
            'readMoreBtnText' => __('Read More'),
            'page_post' => $page_post,
            'page_name' => $page_name,
        ]);
    }

    /**
     * @throws Exception
     */
    public function search(Request $request)
    {
        $all_products = FrontendProductServices::productSearch($request, 'frontend.ajax');

        $selected_search = view('product::frontend.search.selected-search-item')->render();
        $grid = view('product::frontend.search.grid', compact('all_products'))->render();
        $list = view('product::frontend.search.list', compact(['all_products']))->render();
        $paginationList = view("components.search-product-list-pagination", compact('all_products'))->render();
        $showing_items = ' Showing ' . $all_products['from'] . '-' . $all_products['to'] . ' of  ' . $all_products['total_items'] . ' results ';

        return [
            'pagination_list' => $paginationList,
            'grid' => $grid,
            'list' => $list,
            'selected_search' => $selected_search,
            'showing_items' => $showing_items
        ];
    }


    public function dynamic_single_page($slug)
    {

        abort(404);
    }

    public function setCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|in:BDT,USD'
        ]);

        session(['currency_browser' => $request->currency]);
        
        return response()->json([
            'success' => true,
            'message' => 'Currency set successfully'
        ]);
    }
}
