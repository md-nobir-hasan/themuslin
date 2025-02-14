<div class="dashboard-left-content">
    <div class="dashboard-close-main">
        <div class="close-bars"> <i class="las la-times"></i> </div>
        <div class="dashboard-top">
            <div class="dashboard-logo">
                <a href="{{ route('admin.home') }}">

                    <img src="{{ asset('assets/muslin/images/static/logo-black.svg') }}" alt="">
                    
                    <!-- @if (get_static_option('site_admin_dark_mode') == 'off')
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                    @else
                        {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                    @endif -->
                </a>
            </div>
            <div class="dashboard-top-search mt-4">
            </div>
        </div>
        <div class="dashboard-bottom custom__form mt-4" id="sidbar-menu-wrap">
            <ul class="dashboard-list">
                <li class="{{ active_menu('admin-home') }}">
                    <a href="{{ route('admin.home') }}" aria-expanded="true">
                        <i class="ti-layout-grid2"></i>
                        <span>@lang('Dashboard')</span>
                    </a>
                </li>

                @if (auth('admin')->user()->hasRole('Super Admin'))
                    <li class="main_dropdown @if (request()->is(['admin-home/admin/*'])) active open @endif">
                        <a href="#1" aria-expanded="true"><i class="ti-user"></i>
                            <span>{{ __('Admin Manage') }}</span>
                        </a>
                        <ul class="collapse">
                            <li class="{{ active_menu('admin-home/admin/all-user') }}">
                                <a href="{{ route('admin.all.user') }}">{{ __('All Admin') }}</a>
                            </li>
                            <li class="{{ active_menu('admin-home/admin/new-user') }}">
                                <a href="{{ route('admin.new.user') }}">{{ __('Add New Admin') }}</a>
                            </li>
                            <li class="{{ active_menu('admin-home/admin/roles') }}">
                                <a href="{{ route('admin.roles.index') }}">{{ __('Role Manage') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @canany(['frontend-all-user', 'frontend-new-user', 'frontend-user-update',
                    'frontend-user-password-change', 'frontend-delete-user', 'frontend-all-user-bulk-action',
                    'frontend-all-user-email-status'])
                    <li class="main_dropdown
                        @if (request()->is([
                                'admin-home/frontend/new-user',
                                'admin-home/frontend/all-user',
                                'admin-home/frontend/all-user/role',
                            ])) active open @endif ">
                        <a href="#1" aria-expanded="true"><i class="ti-user"></i>
                            <span>{{ __('Users Manage') }}</span></a>
                        <ul class="collapse">
                            @can('frontend-all-user')
                                <li class="{{ active_menu('admin-home/frontend/all-user') }}">
                                    <a href="{{ route('admin.all.frontend.user') }}">
                                        {{ __('All Users') }}
                                    </a>
                                </li>
                            @endcan
                            @can('frontend-new-user')
                                <li class="{{ active_menu('admin-home/frontend/new-user') }}">
                                    <a href="{{ route('admin.frontend.new.user') }}">
                                        {{ __('Add New User') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

               

                @if (moduleExists('Order'))
                    @canany(['assign-delivery-man-orders', 'orders-vendor-list', 'orders', 'orders-sub-order'])
                        {{-- Order Manage --}}
                        <li class="main_dropdown @if (request()->is([
                                'admin-home/orders/*',
                                'admin-home/orders',
                                'admin-home/assign-delivery-man/orders',
                                'admin-home/assign-delivery-man/orders/*',
                            ])) active open @endif ">
                            <a href="#1" aria-expanded="true">
                                <i class="ti-view-list-alt"></i>
                                <span>{{ __('Orders') }}</span>
                            </a>

                            <ul class="collapse">
                               
                                @can('orders')
                                    <li class="{{ active_menu('admin-home/orders') }}">
                                        <a href="{{ route('admin.orders.list') }}">
                                            {{ __('All Orders') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

              
              

                @canany(['country', 'state', 'city'])
                    <li class="main_dropdown @if (request()->is([
                            'admin-home/country',
                            'admin-home/country/*',
                            'admin-home/state',
                            'admin-home/state/*',
                            'admin-home/city',
                            'admin-home/city/*',
                        ])) active @endif ">
                        <a href="#1" aria-expanded="true"><i class="ti-clipboard"></i>
                            <span>{{ __('Country Manage') }}</span></a>
                        <ul class="collapse">
                            @can('country')
                                <li class="{{ active_menu('admin-home/country') }}">
                                    <a href="{{ route('admin.country.all') }}">{{ __('Country') }}</a>
                                </li>
                            @endcan

                            @can('state')
                                <li class="{{ active_menu('admin-home/state') }}">
                                    <a href="{{ route('admin.state.all') }}">{{ __('State') }}</a>
                                </li>
                            @endcan

                            @can('city')
                                <li class="{{ active_menu('admin-home/city') }}">
                                    <a href="{{ route('admin.city.all') }}">{{ __('City') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                @canany(['categories', 'sub-categories', 'child-categories', 'units', 'tags', 'delivery-option',
                    'brand-manage', 'colors', 'sizes', 'attributes', 'badge'])
                    {{--                  Attribute Manage menu bar                 --}}
                    <li class="main_dropdown @if (request()->is([
                                                        'admin-home/categories',
                                                        'admin-home/sub-categories',
                                                        'admin-home/child-categories',
                                                        'admin-home/units',
                                                        'admin-home/tags',
                                                        'admin-home/delivery-option',
                                                        'admin-home/brand-manage',
                                                        'admin-home/brand-manage',
                                                        'admin-home/colors',
                                                        'admin-home/sizes',
                                                        'admin-home/attributes',
                                                    ])) active open @endif ">
                        <a href="#1" aria-expanded="true"><i class="ti-panel"></i>
                            <span>{{ __('Attributes Manage') }}</span></a>
                        <ul class="collapse">
                            @can('categories')
                                <li class="{{ active_menu('admin-home/categories') }}">
                                    <a href="{{ route('admin.category.all') }}">{{ __('Category') }}</a>
                                </li>
                            @endcan

                            @can('sub-categories')
                                <li class="{{ active_menu('admin-home/sub-categories') }}">
                                    <a href="{{ route('admin.subcategory.all') }}">{{ __('Sub-Category') }}</a>
                                </li>
                            @endcan

                            @can('child-categories')
                                <li class="{{ active_menu('admin-home/child-categories') }}">
                                    <a href="{{ route('admin.child-category.all') }}">{{ __('Child-Category') }}</a>
                                </li>
                            @endcan

                            @can('units')
                                <li class="{{ active_menu('admin-home/units') }}">
                                    <a href="{{ route('admin.units.all') }}">{{ __('Units') }}</a>
                                </li>
                            @endcan

                            @can('tags')
                                <!--<li class="{{ active_menu('admin-home/tags') }}">
                                    <a href="{{ route('admin.tag.all') }}">{{ __('Tag') }}</a>
                                </li> -->
                            @endcan

                            @can('delivery-option')
                                <!--<li class="{{ active_menu('admin-home/delivery-option') }}">
                                    <a href="{{ route('admin.delivery.option.all') }}">{{ __('Delivery Options') }}</a>
                                </li>-->
                            @endcan

                            @can('brand-manage')
                                <!-- <li class="{{ active_menu('admin-home/brand-manage') }}">
                                    <a href="{{ route('admin.brand.manage.all') }}">{{ __('Brand Manage') }}</a>
                                </li> -->
                            @endcan

                            @can('colors')
                                <li class="{{ active_menu('admin-home/colors') }}">
                                    <a href="{{ route('admin.product.colors.all') }}">{{ __('Color Manage') }}</a>
                                </li>
                            @endcan

                            @can('sizes')
                                <li class="{{ active_menu('admin-home/sizes') }}">
                                    <a href="{{ route('admin.product.sizes.all') }}">{{ __('Size Manage') }}</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

              
                {{--        Product Inventory manage        --}}
                {{-- @can('product-inventory')
                    <li class="{{ active_menu('admin-home/product-inventory') }}">
                        <a href="{{ route('admin.products.inventory.all') }}">
                            <i class="ti-package"></i>
                            <span>{{ __('Inventory') }}</span>
                        </a>
                    </li>
                @endcan --}}

                @can('campaigns')
                     <li class="{{ active_menu('admin-home/campaigns') }}">
                        <a href="{{ route('admin.campaigns.all') }}" aria-expanded="true"><i class="ti-announcement"></i>
                            <span>{{ __('Campaign Manage') }}</span></a>
                    </li> 
                @endcan

                @can('shipping-method')
                     <li class="@if (request()->is(['admin-home/shipping-method', 'admin-home/shipping-method/*'])) open active @endif">
                        <a href="{{ route('admin.shipping-method.index') }}" aria-expanded="true"><i class="ti-announcement"></i>
                            <span>{{ __('Shipping Methods') }}</span></a>
                    </li> 
                @endcan

                {{-- Product Manage Sidebar menu list --}}
                @canany(['coupons', 'coupons-new'])
                    <li class="@if (request()->is(['admin-home/coupons', 'admin-home/coupons/*'])) active open @endif">
                        <a href="{{ route('admin.products.coupon.all') }}" aria-expanded="true">
                            <i class="ti-layout-tab"></i>
                            <span>{{ __('Coupon Manage') }}</span>
                        </a>
                    </li>
                @endcanany
                {{-- Product Manage Sidebar menu list --}}
                @canany(['product-all', 'product-create'])
                    <li class="main_dropdown @if (request()->is(['admin-home/product', 'admin-home/product/*'])) active open @endif">
                        <a href="#1" aria-expanded="true"><i
                                    class="ti-layout-tab"></i><span>{{ __('Product Manage') }}</span></a>
                        <ul class="collapse">
                            @can('product-all')
                                <li class="{{ active_menu('admin-home/product/all') }}">
                                    <a href="{{ route('admin.products.all') }}">{{ __('Product List') }}</a>
                                </li>
                            @endcan

                            @can('product-create')
                                <li class="{{ active_menu('admin-home/product/create') }}">
                                    <a href="{{ route('admin.products.create') }}">{{ __('Create Product') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
               
                {{-- Blog Routes Wrapper --}}
                @canany(['blog', 'blog-category', 'blog-new', 'blog-page-settings', 'blog-single-page-settings'])
                    <li class="main_dropdown @if (request()->is(['admin-home/blog/*', 'admin-home/blog'])) active open @endif ">
                        <a href="#1" aria-expanded="true"><i class="ti-write"></i>
                            <span>{{ __('Blogs') }}</span></a>
                        <ul class="collapse">
                            @can('blog')
                                <li class="{{ active_menu('admin-home/blog') }}"><a
                                            href="{{ route('admin.blog') }}">{{ __('All Blog') }}</a></li>
                            @endcan
                            @can('blog-category')
                                <!-- <li class="{{ active_menu('admin-home/blog/category') }}"><a
                                            href="{{ route('admin.blog.category') }}">{{ __('Category') }}</a></li> -->
                            @endcan
                            @can('blog-new')
                                <li class="{{ active_menu('admin-home/blog/new') }}">
                                    <a href="{{ route('admin.blog.new') }}">
                                        {{ __('Add New Post') }}
                                    </a>
                                </li>
                            @endcan
                             
                        </ul>
                    </li>
                @endcanany

                @can('faq')
                    <li class="{{ active_menu('admin-home/faq') }}">
                        <a href="{{ route('admin.faq') }}" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span>{{ __('FAQ') }}</span>
                        </a>
                    </li>
                @endcan

              
                @canany(['page-all', 'page-new'])
                    <li class="main_dropdown @if (request()->is(['admin-home/page-edit/*', 'admin-home/page/edit/*', 'admin-home/page/all', 'admin-home/page/new'])) open active @endif ">
                        <a href="#1" aria-expanded="true"><i class="ti-write"></i>
                            <span>{{ __('Pages') }}</span>
                        </a>

                        <ul class="collapse">
                            @can('page-all')
                                <li class="{{ active_menu('admin-home/page/all') }}"><a
                                            href="{{ route('admin.page') }}">{{ __('All Pages') }}</a></li>
                            @endcan

                            @can('page-new')
                                <li class="{{ active_menu('admin-home/page/new') }}"><a
                                            href="{{ route('admin.page.new') }}">{{ __('Add New Page') }}</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['stock-report', 'sales-report', 'customer-report'])
                    <li class="main_dropdown @if (request()->is(['admin-home/stock','admin-home/sales','admin-home/customer'])) active open @endif">
                        <a href="#1" aria-expanded="true"><i class="ti-agenda"></i>
                            <span>Report</span>
                        </a>
                        <ul class="collapse">
                            <li class="{{ active_menu('admin-home/stock') }}">
                                <a href="{{ route('report.stock') }}">Stock Report</a>
                            </li>
                            <li class="{{ active_menu('admin-home/sales') }}">
                                <a href="{{ route('report.sales') }}">Sales Report</a>
                            </li>
                            <li class="{{ active_menu('admin-home/customer') }}">
                                <a href="{{ route('report.customer') }}">Customer Report</a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                @canany(['appearance-settings-topbar-all', 'menu', 'category-menu', 'widgets-all',
                    'form-builder-custom-all', 'media-upload-page'])
                    <li
                            class="main_dropdown
                                                    @if (request()->is([
                                                            'admin-home/appearance-settings/topbar/*',
                                                            'admin-home/category-menu/*',
                                                            'admin-home/category-menu',
                                                            'admin-home/appearance-settings/navbar/*',
                                                            'admin-home/appearance-settings/home-variant/*',
                                                            'admin-home/media-upload/page',
                                                            'admin-home/menu',
                                                            'admin-home/menu-edit/*',
                                                            'admin-home/widgets',
                                                            'admin-home/widgets/*',
                                                            'admin-home/popup-builder/*',
                                                            'admin-home/form-builder/*',
                                                        ])) active open @endif ">
                        <a href="#1" aria-expanded="true"><i class="ti-stamp"></i>
                            <span>{{ __('Appearance Settings') }}</span></a>
                        <ul class="collapse ">
                           

                            @can('media-upload-page')
                                <li class="{{ active_menu('admin-home/media-upload/page') }}">
                                    <a href="{{ route('admin.upload.media.images.page') }}"
                                       class="{{ active_menu('admin-home/form-builder/custom/all') }}">
                                        {{ __('Media Upload Page') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['page-settings-wishlist', 'page-settings-cart', 'page-settings-checkout', 'page-settings-compare',
                    'page-settings-login-register', 'page-settings-shop-page', 'page-settings-product-page', 'page-all',
                    'page-new', 'page-edit', 'page-update', 'page-delete', 'page-builk-action', 'page-builder-update',
                    'page-builder-new', 'page-builder-delete', 'page-builder-dynamic-page', 'page-builder-update-order',
                    'page-builder-get-admin-markup'])
                   
                @endcanany

                @canany(['shipping-charge-settings', 'general-settings-reading', 'general-settings-global-navbar',
                    'general-settings-site-identity', 'general-settings-basic-settings', 'general-settings-color-settings',
                    'general-settings-typography-settings', 'general-settings-seo-settings', 'general-settings-scripts',
                    'general-settings-email-template', 'general-settings-smtp-settings', 'general-settings-payment-gateway',
                    'general-settings-custom-css', 'general-settings-custom-js', 'general-settings-cache-settings',
                    'general-settings-gdpr-settings', 'general-settings-sitemap-settings', 'general-settings-rss-settings',
                    'general-settings-license-setting'])
                    <li class="main_dropdown @if (request()->is('admin-home/general-settings/*')) active open @endif ">
                        <a href="#1" aria-expanded="true">
                            <i class="ti-new-window"></i>
                            <span>{{ __('General Settings') }}</span>
                        </a>
                        <ul class="collapse">
                            @can('shipping-charge-settings')
                                <li class="{{ active_menu('admin-home/shipping-charge-settings') }}"><a
                                            href="{{ route('admin.shipping-charge-settings') }}">{{ __('Shipping Charge Settings') }}</a>
                                </li>
                            @endcan
                          
                        </ul>
                        <ul class="collapse">
                            @can('currency-rate')
                                <li class="{{ active_menu('admin-home/currency-rate') }}"><a
                                            href="{{ route('admin.currency-rate') }}">{{ __('Currency Rate') }}</a>
                                </li>
                            @endcan
                          
                        </ul>
                    </li>
                @endcanany

            </ul>
        </div>
    </div>
</div>
