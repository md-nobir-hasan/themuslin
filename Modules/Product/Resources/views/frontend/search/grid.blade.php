<div class="row mt-4">
    @forelse($all_products["items"] ?? [] as $product)
        <div class="col-xl-3 col-lg-4 col-sm-6 mt-4">
            <x-product::frontend.grid-style-03 :$product :$loop />
        </div>
    @empty
        <div class="col-md-12">
            <div class="w-50 m-auto padding-top-100 padding-bottom-100">
                <x-frontend.page.empty :image="get_static_option('empty_cart_image')" :text="get_static_option('empty_cart_text') ?? __('No products in your cart!')" />
            </div>
            <h3 class="text-warning text-center">
                {{ __("No product found") }}
            </h3>
        </div>
    @endforelse
</div>