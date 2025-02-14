<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.home') }}">
                @if (get_static_option('site_admin_dark_mode') == 'off')
                    {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                @else
                    {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                @endif
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="{{ active_menu('admin-home') }}">
                        <a href="{{ route('admin.home') }}" aria-expanded="true">
                            <i class="ti-dashboard"></i>
                            <span>@lang('dashboard')</span>
                        </a>
                    </li>
                    @if (auth('admin')->user()->hasRole('Super Admin'))
                        <li class="main_dropdown @if (request()->is(['admin-home/admin/*'])) active @endif">
                            <a href="#1" aria-expanded="true"><i class="ti-user"></i>
                                <span>{{ __('Admin Manage') }}</span></a>
                            <ul class="collapse">
                                <li class="{{ active_menu('admin-home/admin/all-user') }}"><a
                                            href="{{ route('admin.all.user') }}">{{ __('All Admin') }}</a></li>
                                <li class="{{ active_menu('admin-home/admin/new-user') }}"><a
                                            href="{{ route('admin.new.user') }}">{{ __('Add New Admin') }}</a></li>
                                <li class="{{ active_menu('admin-home/admin/role') }} "><a
                                            href="{{ route('admin.all.admin.role') }}">{{ __('All Admin Role') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @canany(['user-list', 'user-create'])
                        <li
                                class="main_dropdown
                        @if (request()->is(['admin-home/frontend/new-user', 'admin-home/frontend/all-user', 'admin-home/frontend/all-user/role'])) active @endif
                                        ">
                            <a href="#1" aria-expanded="true"><i class="ti-user"></i>
                                <span>{{ __('Users Manage') }}</span></a>
                            <ul class="collapse">
                                @can('user-list')
                                    <li class="{{ active_menu('admin-home/frontend/all-user') }}"><a
                                                href="{{ route('admin.all.frontend.user') }}">{{ __('All Users') }}</a>
                                    </li>
                                @endcan
                                @can('user-create')
                                    <li class="{{ active_menu('admin-home/frontend/new-user') }}"><a
                                                href="{{ route('admin.frontend.new.user') }}">{{ __('Add New User') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['newsletter-list', 'newsletter-mail-send'])
                        <li class="main_dropdown @if (request()->is(['admin-home/newsletter/*', 'admin-home/newsletter'])) active @endif ">
                            <a href="#1" aria-expanded="true"><i class="ti-email"></i>
                                <span>{{ __('Newsletter Manage') }}</span>
                            </a>
                            <ul class="collapse">
                                @can('newsletter-list')
                                    <li class="{{ active_menu('admin-home/newsletter') }}">
                                        <a href="{{ route('admin.newsletter') }}">{{ __('All Subscriber') }}</a>
                                    </li>
                                @endcan
                                @can('newsletter-mail-send')
                                    <li class="{{ active_menu('admin-home/newsletter/all') }}">
                                        <a href="{{ route('admin.newsletter.mail') }}">{{ __('Send Mail To All') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['support-ticket-list', 'support-ticket-create', 'support-ticket-department-list',
                        'support-ticket-page-settings'])
                        <li class="main_dropdown {{ active_menu('admin-home/support-tickets') }} @if (request()->is('admin-home/support-tickets/*')) active @endif">
                            <a href="#1" aria-expanded="true">
                                <i class="ti-headphone-alt"></i>
                                <span>{{ __('Support Tickets') }}</span>
                            </a>
                            <ul class="collapse">
                                @can('support-ticket-list')
                                    <li class="{{ active_menu('admin-home/support-tickets') }}">
                                        <a href="{{ route('admin.support.ticket.all') }}">{{ __('All Tickets') }}</a>
                                    </li>
                                @endcan
                                @can('support-ticket-create')
                                    <li class="{{ active_menu('admin-home/support-tickets/new') }}">
                                        <a href="{{ route('admin.support.ticket.new') }}">{{ __('Add New Ticket') }}</a>
                                    </li>
                                @endcan
                                @can('support-ticket-department-list')
                                    <li class="{{ active_menu('admin-home/support-tickets/department') }}">
                                        <a href="{{ route('admin.support.ticket.department') }}">{{ __('Departments') }}</a>
                                    </li>
                                @endcan
                                @can('support-ticket-page-settings')
                                    <li class="{{ active_menu('admin-home/support-tickets/page-settings') }}">
                                        <a
                                                href="{{ route('admin.support.ticket.page.settings') }}">{{ __('Page Settings') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['country-list', 'state-list'])
                        <li class="main_dropdown @if (request()->is(['admin-home/country', 'admin-home/country/*', 'admin-home/state', 'admin-home/state/*'])) active @endif ">
                            <a href="#1" aria-expanded="true"><i class="ti-settings"></i>
                                <span>{{ __('Country Manage') }}</span></a>
                            <ul class="collapse">
                                @can('country-list')
                                    <li class="{{ active_menu('admin-home/country') }}">
                                        <a href="{{ route('admin.country.all') }}">{{ __('Country') }}</a>
                                    </li>
                                @endcan
                                @can('state-list')
                                    <li class="{{ active_menu('admin-home/state') }}">
                                        <a href="{{ route('admin.state.all') }}">{{ __('State') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['country-tax-list', 'state-tax-list'])
                        <li class="main_dropdown @if (request()->is(['admin-home/tax/*'])) active @endif ">
                            <a href="#1" aria-expanded="true"><i class="ti-settings"></i>
                                <span>{{ __('Tax Settings') }}</span></a>
                            <ul class="collapse">
                                @can('country-tax-list')
                                    <li class="{{ active_menu('admin-home/tax/country') }}">
                                        <a href="{{ route('admin.tax.country.all') }}">{{ __('Country Tax') }}</a>
                                    </li>
                                @endcan
                                @can('state-tax-list')
                                    <li class="{{ active_menu('admin-home/tax/state') }}">
                                        <a href="{{ route('admin.tax.state.all') }}">{{ __('State Tax') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['product-list', 'deleted-product-list', 'product-category-list', 'product-subcategory-list', 'product-tag-list', 'product-unit-list'])
                        <li class="main_dropdown
                            @if (
                                request()->is([
                                    'admin-home/products',
                                    'admin-home/products/*'
                                ])
                                &&
                                !request()->is([
                                    'admin-home/products/coupons',
                                    'admin-home/products/attributes',
                                    'admin-home/products/ratings',
                                    'admin-home/products/product-inventory',
                                    'admin-home/products/product-order',
                                    'admin-home/products/product-order/*',
                                ])
                            )
                                active
                            @endif"
                        >
                            <a href="#1" aria-expanded="true"><i
                                        class="ti-package"></i><span>{{ __('Product Module') }}</span></a>
                            <ul class="collapse">
                                @can('product-list')
                                    <li class="{{ active_menu('admin-home/products/all') }}">
                                        <a href="{{ route('admin.products.all') }}">{{ __('Products') }}</a>
                                    </li>
                                @endcan
                                @can('deleted-product-list')
                                    <li class="{{ active_menu('admin-home/products/deleted') }}">
                                        <a href="{{ route('admin.products.deleted.all') }}">{{ __('Deleted Products') }}</a>
                                    </li>
                                @endcan
                                @can('product-category-list')
                                    <li class="{{ active_menu('admin-home/products/categories') }}">
                                        <a href="{{ route('admin.products.category.all') }}">{{ __('Category') }}</a>
                                    </li>
                                @endcan
                                @can('product-subcategory-list')
                                    <li class="{{ active_menu('admin-home/products/sub-categories') }}">
                                        <a href="{{ route('admin.products.subcategory.all') }}">{{ __('Sub-Category') }}</a>
                                    </li>
                                @endcan
                                @can('product-unit-list')
                                    <li class="{{ active_menu('admin-home/products/units') }}">
                                        <a href="{{ route('admin.products.units.all') }}">{{ __('Units') }}</a>
                                    </li>
                                @endcan
                                @can('product-tag-list')
                                    <li class="{{ active_menu('admin-home/products/tags') }}">
                                        <a href="{{ route('admin.products.tag.all') }}">{{ __('Tag') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @can('product-coupon-list')
                        <li class="{{ active_menu('admin-home/products/coupons') }}">
                            <a href="{{ route('admin.products.coupon.all') }}">
                                <i class="ti-ticket"></i>
                                <span>{{ __('Coupon') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('product-color-list')
                        <li class="{{ active_menu('admin-home/products/colors') }}">
                            <a href="{{ route('admin.products.color.all') }}">
                                <i class="ti-palette"></i>
                                <span>{{ __('Color') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('product-size-list')
                        <li class="{{ active_menu('admin-home/products/sizes') }}">
                            <a href="{{ route('admin.products.size.all') }}">
                                <i class="ti-ruler"></i>
                                <span>{{ __('Size') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('product-attribute-list')
                        <li class="{{ active_menu('admin-home/products/attributes') }}">
                            <a href="{{ route('admin.products.attributes.all') }}">
                                <i class="ti-menu"></i>
                                <span>{{ __('Custom Attribute') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('product-rating-list')
                        <li class="{{ active_menu('admin-home/products/ratings') }}">
                            <a href="{{ route('admin.products.ratings.all') }}">
                                <i class="ti-star"></i>
                                <span>{{ __('Ratings') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('product-inventory-list')
                        <li class="{{ active_menu('admin-home/products/product-inventory') }}">
                            <a href="{{ route('admin.products.inventory.all') }}">
                                <i class="ti-package"></i>
                                <span>{{ __('Inventory') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('product-order-list')
                        <li class="@if (request()->is(['admin-home/products/product-order', 'admin-home/products/product-order/*'])) active @endif">
                            <a href="{{ route('admin.product.order.logs') }}">
                                <i class="ti-notepad"></i>
                                <span>{{ __('Order Log') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('campaign-list')
                        <li class="main_dropdown {{ active_menu('admin-home/campaigns') }}">
                            <a href="{{ route('admin.campaigns.all') }}" aria-expanded="true"><i
                                        class="ti-announcement"></i>
                                <span>{{ __('Campaign Module') }}</span></a>
                        </li>
                    @endcan

                    @canany(['shipping-zone-list', 'shipping-method-list',])
                        <li class="main_dropdown @if (request()->is(['admin-home/shipping/*', 'admin-home/shipping'])) active @endif ">
                            <a href="#1" aria-expanded="true"><i
                                        class="ti-truck"></i><span>{{ __('Shipping Module') }}</span></a>
                            <ul class="collapse">
                                @can('shipping-zone-list')
                                    <li class="{{ active_menu('admin-home/shipping/zone') }}">
                                        <a href="{{ route('admin.shipping.zone.all') }}">{{ __('Shipping Zones') }}</a>
                                    </li>
                                @endcan
                                @can('shipping-method-list')
                                    <li class="{{ active_menu('admin-home/shipping/method') }}">
                                        <a href="{{ route('admin.shipping.method.all') }}">{{ __('Shipping Methods') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['blog-list', 'blog-category-list', 'blog-create', 'blog-page-settings', 'blog-single-page-settings'])
                        <li class="main_dropdown @if (request()->is(['admin-home/blog/*', 'admin-home/blog'])) active @endif ">
                            <a href="#1" aria-expanded="true"><i class="ti-write"></i>
                                <span>{{ __('Blogs') }}</span></a>
                            <ul class="collapse">
                                @can('blog-list')
                                    <li class="{{ active_menu('admin-home/blog') }}"><a
                                                href="{{ route('admin.blog') }}">{{ __('All Blog') }}</a></li>
                                @endcan
                                @can('blog-category-list')
                                    <li class="{{ active_menu('admin-home/blog/category') }}"><a
                                                href="{{ route('admin.blog.category') }}">{{ __('Category') }}</a></li>
                                @endcan
                                @can('blog-create')
                                    <li class="{{ active_menu('admin-home/blog/new') }}"><a
                                                href="{{ route('admin.blog.new') }}">{{ __('Add New Post') }}</a></li>
                                @endcan
                                @can('blog-page-settings')
                                    <li class="{{ active_menu('admin-home/blog/page-settings') }}"><a
                                                href="{{ route('admin.blog.page.settings') }}">{{ __('Blog Page Settings') }}</a>
                                    </li>
                                @endcan
                                @can('blog-single-page-settings')
                                    <li class="{{ active_menu('admin-home/blog/single-settings') }}"><a
                                                href="{{ route('admin.blog.single.settings') }}">{{ __('Blog Single Settings') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    @can('faq-list')
                        <li class="main_dropdown {{ active_menu('admin-home/faq') }}">
                            <a href="{{ route('admin.faq') }}" aria-expanded="true"><i class="ti-control-forward"></i>
                                <span>{{ __('FAQ') }}</span></a>
                        </li>
                    @endcan
                    @canany(['page-list', 'page-create'])
                        <li class="main_dropdown @if (request()->is(['admin-home/page-edit/*', 'admin-home/page/edit/*', 'admin-home/page/all', 'admin-home/page/new'])) active @endif ">
                            <a href="#1" aria-expanded="true"><i class="ti-write"></i>
                                <span>{{ __('Pages') }}</span></a>
                            <ul class="collapse">
                                @can('page-list')
                                    <li class="{{ active_menu('admin-home/page/all') }}"><a
                                                href="{{ route('admin.page') }}">{{ __('All Pages') }}</a></li>
                                @endcan
                                @can('page-create')
                                    <li class="{{ active_menu('admin-home/page/new') }}"><a
                                                href="{{ route('admin.page.new') }}">{{ __('Add New Page') }}</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['appearance-topbar-settings', 'appearance-navbar-settings', 'appearance-home-variant',
                        'appearance-menu-manage-list', 'appearance-widget-manage', 'appearance-form-builder',
                        'appearance-media-image'])
                        <li class="main_dropdown
                    @if (request()->is(['admin-home/appearance-settings/topbar/*', 'admin-home/appearance-settings/navbar/*', 'admin-home/appearance-settings/home-variant/*', 'admin-home/media-upload/page', 'admin-home/menu', 'admin-home/menu-edit/*', 'admin-home/widgets', 'admin-home/widgets/*', 'admin-home/popup-builder/*', 'admin-home/form-builder/*'])) active @endif ">
                            <a href="#1" aria-expanded="true"><i class="ti-settings"></i>
                                <span>{{ __('Appearance Settings') }}</span></a>
                            <ul class="collapse ">
                                
                                @canany(['top-bar-menu-list', 'top-bar-social-icon-list'])
                                    <li class="main_dropdown {{ active_menu('admin-home/appearance-settings/topbar/all') }}">
                                        <a href="{{ route('admin.topbar.settings') }}" aria-expanded="true">
                                            {{ __('Topbar Manage') }}
                                        </a>
                                    </li>
                                @endcanany
                                @can('appearance-menu-manage-list')
                                    <li class="main_dropdown
                                        {{ active_menu('admin-home/menu') }}
                                    @if (request()->is('admin-home/menu-edit/*')) active @endif
                                            "
                                    >
                                        <a href="#1" aria-expanded="true">{{ __('Menus Manage') }}</a>
                                        <ul class="collapse">
                                            <li class="{{ active_menu('admin-home/menu') }}">
                                                <a href="{{ route('admin.menu') }}">{{ __('All Menus') }}</a>
                                            </li>
                                        </ul>
                                    </li>


                                    <li
                                            class="main_dropdown {{ active_menu('admin-home/appearance-settings/category-menu') }}">
                                        <a href="{{ route('admin.category.menu.settings') }}" aria-expanded="true">
                                            {{ __('Category Menu Manage') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('appearance-widget-manage')
                                    <li class="main_dropdown
                                            {{ active_menu('admin-home/widgets') }}
                                    @if (request()->is('admin-home/widgets/*')) active @endif"
                                    >
                                        <a href="#1" aria-expanded="true">
                                            {{ __('Widgets Manage') }}</a>
                                        <ul class="collapse">
                                            <li class="{{ active_menu('admin-home/widgets') }}"><a
                                                        href="{{ route('admin.widgets') }}">{{ __('All Widgets') }}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcan
                                @can('appearance-form-builder')
                                    <li class="main_dropdown @if (request()->is('admin-home/form-builder/*')) active @endif">
                                        <a href="#1" aria-expanded="true">
                                            {{ __('Form Builder') }}
                                        </a>
                                        <ul class="collapse">
                                            <li class="{{ active_menu('admin-home/form-builder/custom/all') }}">
                                                <a href="{{ route('admin.form.builder.all') }}">{{ __('Custom Form') }}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcan
                                @can('appearance-media-image')
                                    <li class="main_dropdown {{ active_menu('admin-home/media-upload/page') }}">
                                        <a href="{{ route('admin.upload.media.images.page') }}" aria-expanded="true">
                                            {{ __('Media Images Manage') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany([
                        'page-settings-error-page-manage', 'page-settings-maintain-page-manage',
                        'page-settings-wishlist-page',
                        'page-settings-cart-page',
                        'page-settings-checkout-page',
                        'page-settings-compare-page',
                        'page-settings-login-register-page',
                        'page-settings-shop-page',
                        'page-settings-product-details-page',
                    ])
                        <li class="main_dropdown
                        @if (request()->is(['admin-home/home-page-01/*', 'admin-home/header', 'admin-home/keyfeatures', 'admin-home/about-page/*', 'admin-home/404-page-manage', 'admin-home/maintains-page/settings', 'admin-home/page-builder/home-page', 'admin-home/page-settings/*', 'admin-home/page-settings/wishlist', 'admin-home/page-settings/cart', 'admin-home/page-settings/compare', 'admin-home/page-builder/contact-page', 'admin-home/page-builder/about-page', 'admin-home/page-builder/faq-page'])) active @endif">
                            <a href="#1" aria-expanded="true"><i class="ti-settings"></i>
                                <span>{{ __('All Page Settings') }}</span>
                            </a>
                            <ul class="collapse">
{{--                                @canany([
                                        'page-settings-wishlist-page',
                                        'page-settings-cart-page',
                                        'page-settings-checkout-page',
                                        'page-settings-compare-page',
                                        'page-settings-login-register-page',
                                        'page-settings-shop-page',
                                        'page-settings-product-details-page',
                                    ])
--}}
                                    <li class="main_dropdown
                                            @if (request()->is([
                                                'admin-home/page-settings/*',
                                                'admin-home/page-settings/wishlist',
                                                'admin-home/page-settings/cart',
                                                'admin-home/page-settings/compare'
                                            ])) active @endif
                                            ">
                                        <a href="#1" aria-expanded="true">
                                            {{ __('Module Page Settings') }}
                                        </a>
                                        <ul class="collapse">
                                            @can('page-settings-wishlist-page')
                                                <li class="{{ active_menu('admin-home/page-settings/wishlist') }}">
                                                    <a href="{{ route('admin.page.settings.wishlist') }}">
                                                        {{ __('Wishlist Page') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('page-settings-cart-page')
                                                <li class="{{ active_menu('admin-home/page-settings/cart') }}">
                                                    <a href="{{ route('admin.page.settings.cart') }}">
                                                        {{ __('Cart Page') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('page-settings-checkout-page')
                                                <li class="{{ active_menu('admin-home/page-settings/checkout') }}">
                                                    <a href="{{ route('admin.page.settings.checkout') }}">
                                                        {{ __('Checkout Page') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('page-settings-compare-page')
                                                <li class="{{ active_menu('admin-home/page-settings/compare') }}">
                                                    <a href="{{ route('admin.page.settings.compare') }}">
                                                        {{ __('Compare Page') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('page-settings-login-register-page')
                                                <li class="{{ active_menu('admin-home/page-settings/login-register') }}">
                                                    <a href="{{ route('admin.page.settings.user.auth') }}">
                                                        {{ __('Login/Register Page') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('page-settings-shop-page')
                                                <li class="{{ active_menu('admin-home/page-settings/shop-page') }}">
                                                    <a href="{{ route('admin.page.settings.shop.page') }}">
                                                        {{ __('Shop Page') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('page-settings-product-details-page')
                                                <li class="{{ active_menu('admin-home/page-settings/product-details-page') }}">
                                                    <a href="{{ route('admin.page.settings.product.detail.page') }}">
                                                        {{ __('Product Details Page') }}
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
{{--                                @endcan--}}
                                @can('page-settings-error-page-manage')
                                    <li class="main_dropdown {{ active_menu('admin-home/404-page-manage') }}">
                                        <a href="{{ route('admin.404.page.settings') }}" aria-expanded="true">
                                            {{ __('404 Page Manage') }}</a>
                                    </li>
                                @endcan
                                @can('page-settings-maintain-page-manage')
                                    <li class="main_dropdown {{ active_menu('admin-home/maintains-page/settings') }}">
                                        <a href="{{ route('admin.maintains.page.settings') }}" aria-expanded="true">
                                            {{ __('Maintain Page Manage') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany([
                        'general-settings-site-identity', 'general-settings-basic-settings',
                        'general-settings-color-settings', 'general-settings-typography', 'general-settings-seo-settings',
                        'general-settings-third-party-script', 'general-settings-email-template',
                        'general-settings-smtp-settings', 'general-settings-page-settings',
                        'general-settings-payment-gateway', 'general-settings-custom-css', 'general-settings-custom-js',
                        'general-settings-cache-settings', 'general-settings-gdpr-settings', 'general-settings-sitemap',
                        'general-settings-rss-feed', 'general-settings-license', 'general-settings-reading-settings',
                        'general-settings-global-navbar-settings', 'general-settings-navbar-category-dropdown',
                    ])
                        <li class="main_dropdown @if (request()->is('admin-home/general-settings/*')) active @endif">
                            <a href="#1" aria-expanded="true"><i class="ti-settings"></i>
                                <span>{{ __('General Settings') }}</span></a>
                            <ul class="collapse ">
                                @can('general-settings-reading-settings')
                                    <li class="{{active_menu('admin-home/general-settings/reading')}}"><a
                                                href="{{route('admin.general.reading')}}">{{__('Reading')}}</a>
                                    </li>
                                @endcan
                                @can('general-settings-global-navbar-settings')
                                    <li class="{{active_menu('admin-home/general-settings/global-variant-navbar')}}"><a
                                                href="{{route('admin.general.global.variant.navbar')}}">{{__('Navbar Global Variant')}}</a>
                                    </li>
                                @endcan
                                @can('general-settings-site-identity')
                                    <li class="{{ active_menu('admin-home/general-settings/site-identity') }}"><a
                                                href="{{ route('admin.general.site.identity') }}">{{ __('Site Identity') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-basic-settings')
                                    <li class="{{ active_menu('admin-home/general-settings/basic-settings') }}"><a
                                                href="{{ route('admin.general.basic.settings') }}">{{ __('Basic Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-color-settings')
                                    <li class="{{ active_menu('admin-home/general-settings/color-settings') }}"><a
                                                href="{{ route('admin.general.color.settings') }}">{{ __('Color Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-typography')
                                    <li class="{{ active_menu('admin-home/general-settings/typography-settings') }}"><a
                                                href="{{ route('admin.general.typography.settings') }}">{{ __('Typography Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-seo-settings')
                                    <li class="{{ active_menu('admin-home/general-settings/seo-settings') }}"><a
                                                href="{{ route('admin.general.seo.settings') }}">{{ __('SEO Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-third-party-script')
                                    <li class="{{ active_menu('admin-home/general-settings/scripts') }}"><a
                                                href="{{ route('admin.general.scripts.settings') }}">{{ __('Third Party Scripts') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-email-template')
                                    <li class="{{ active_menu('admin-home/general-settings/email-template') }}"><a
                                                href="{{ route('admin.general.email.template') }}">{{ __('Email Template') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-smtp-settings')
                                    <li class="{{ active_menu('admin-home/general-settings/smtp-settings') }}"><a
                                                href="{{ route('admin.general.smtp.settings') }}">{{ __('SMTP Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-payment-gateway')
                                    @if (!empty(get_static_option('site_payment_gateway')))
                                        <li class="{{ active_menu('admin-home/general-settings/payment-settings') }}"><a
                                                    href="{{ route('admin.general.payment.settings') }}">{{ __('Payment Gateway Settings') }}</a>
                                        </li>
                                    @endif
                                @endcan
                                @can('general-settings-custom-css')
                                    <li class="{{ active_menu('admin-home/general-settings/custom-css') }}"><a
                                                href="{{ route('admin.general.custom.css') }}">{{ __('Custom CSS') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-custom-js')
                                    <li class="{{ active_menu('admin-home/general-settings/custom-js') }}"><a
                                                href="{{ route('admin.general.custom.js') }}">{{ __('Custom JS') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-cache-settings')
                                    <li class="{{ active_menu('admin-home/general-settings/cache-settings') }}"><a
                                                href="{{ route('admin.general.cache.settings') }}">{{ __('Cache Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-gdpr-settings')
                                    <li class="{{ active_menu('admin-home/general-settings/gdpr-settings') }}"><a
                                                href="{{ route('admin.general.gdpr.settings') }}">{{ __('GDPR Compliant Cookies Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-sitemap')
                                    <li class="{{ active_menu('admin-home/general-settings/sitemap-settings') }}"><a
                                                href="{{ route('admin.general.sitemap.settings') }}">{{ __('Sitemap Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-rss-feed')
                                    <li class="{{ active_menu('admin-home/general-settings/rss-settings') }}"><a
                                                href="{{ route('admin.general.rss.feed.settings') }}">{{ __('RSS Feed Settings') }}</a>
                                    </li>
                                @endcan
                                @can('general-settings-license')
                                    <li class="{{ active_menu('admin-home/general-settings/license-setting') }}"><a
                                                href="{{ route('admin.general.license.settings') }}">{{ __('Licence Settings') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    @can('language-list')
                        <li class="@if (request()->is('admin-home/languages/*') || request()->is('admin-home/languages')) active @endif">
                            <a href="{{ route('admin.languages') }}" aria-expanded="true"><i class="ti-signal"></i>
                                <span>{{ __('Languages') }}</span></a>
                        </li>
                    @endcan
                </ul>
            </nav>
        </div>
    </div>
</div>
