<?php

use Modules\Product\Entities\Product;
use Modules\Attributes\Entities\Category;
use Gloudemans\Shoppingcart\Facades\Cart;

$currentRouteAction = \Route::currentRouteAction();
[$controller, $action] = explode('@', $currentRouteAction);
$controllerName = class_basename($controller);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/muslin/images/static/fav.png') }}" />
    <meta name="theme-color" content="#009975" />
    <meta
        content="Established in 2021, The Muslin stands as a cherished brand and proud sister concern of IconX Lifestyle Limited, reflecting a profound dedication to preserving and showcasing the rich heritage of Bangladesh. Situated at the prestigious Le Meridien Dhaka, on the Entresol (Lobby floor) of 79/A Commercial Area, Airport Road, Nikunja-2, Dhaka-1229, The Muslin is not merely a brand but a tribute to the artistry, culture, and history of the nation. IconX Lifestyle Limited is a part Best Holdings Ltd. which stands as a trailblazer in Bangladesh's dynamic construction sector, playing a pivotal role in the country's overarching infrastructure development."
        name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
   <script>
      // code for geolocation 
   </script>
    <title> @yield('title') | The Muslin</title>
    <!-- font -->
    <link rel="preload" href="{{ asset('assets/muslin/fonts/FuturaStd-ExtraBold.woff') }}" as="font"
        type="font/woff" crossorigin />
    <link rel="preload" href="{{ asset('assets/muslin/fonts/FuturaStd-Book.woff') }}" as="font" type="font/woff"
        crossorigin />
    <link rel="preload" href="{{ asset('assets/muslin/fonts/FuturaStd-Bold.woff') }}" as="font" type="font/woff"
        crossorigin />
    <link rel="preload" href="{{ asset('assets/muslin/fonts/FuturaStd-Heavy.woff') }}" as="font" type="font/woff"
        crossorigin />
    <link rel="preload" href="{{ asset('assets/muslin/fonts/FuturaStd-Light.woff') }}" as="font" type="font/woff"
        crossorigin />
    <link rel="preload" href="{{ asset('assets/muslin/fonts/FuturaStd-Medium.woff') }}" as="font" type="font/woff"
        crossorigin />
    {{-- css links start --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('assets/muslin/lib/bootstrap/bootstrap.min.css') }}" />

    @if ($controllerName == 'FrontendController' && $action == 'index')
        <link rel="stylesheet" href="{{ asset('assets/muslin/lib/layer-slider/layerslider.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/muslin/lib/dc/dc-animation.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/muslin/css/home-bundle.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/muslin/css/home.css') }}" />
    @else
        <link rel="stylesheet" href="{{ asset('assets/muslin/lib/slick-slider/slick.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/muslin/lib/light-gallery/lightgallery.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/muslin/lib/nice-select/nice-select.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/muslin/css/inner-bundle.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/muslin/css/inner.css') }}" />
    @endif
    {{-- css links end --}}
</head>

<body>

    <!-- messenger icon start -->
    <div class="messanger-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M3.75 20C3.63958 19.9997 3.53377 19.9557 3.45578 19.8775C3.3778 19.7993 3.334 19.6934 3.334 19.583V16.717C2.29506 15.8403 1.45888 14.7484 0.883249 13.5168C0.307616 12.2853 0.00625753 10.9434 0 9.584C0.0585006 7.02316 1.11652 4.58682 2.94785 2.79585C4.77918 1.00488 7.23849 0.00142121 9.8 0L10 0H10.2C12.7616 0.00116608 15.221 1.00454 17.0524 2.79556C18.8838 4.58659 19.9418 7.02308 20 9.584C19.9415 12.1449 18.8835 14.5814 17.0522 16.3725C15.2209 18.1636 12.7616 19.1673 10.2 19.169H10H9.98C8.71998 19.1699 7.47054 18.939 6.294 18.488L3.97 19.936C3.90413 19.9776 3.82789 19.9997 3.75 20ZM8.75 6.668C8.6952 6.6678 8.64091 6.67848 8.59027 6.69942C8.53963 6.72037 8.49365 6.75115 8.455 6.79L3.455 11.79C3.38517 11.8611 3.34311 11.9549 3.33644 12.0543C3.32977 12.1537 3.35894 12.2523 3.41866 12.3321C3.47838 12.4119 3.56471 12.4676 3.66199 12.4893C3.75928 12.5109 3.8611 12.497 3.949 12.45L8.282 10.088L10.982 12.4C11.0618 12.4683 11.1644 12.5039 11.2693 12.4998C11.3742 12.4958 11.4738 12.4523 11.548 12.378L16.548 7.378C16.6202 7.30678 16.664 7.21176 16.6713 7.11064C16.6786 7.00952 16.6489 6.90919 16.5877 6.82834C16.5266 6.74748 16.4381 6.69161 16.3388 6.67113C16.2395 6.65065 16.1362 6.66695 16.048 6.717L11.715 9.08L9.02 6.769C8.94461 6.70408 8.84849 6.66826 8.749 6.668H8.75Z"
                fill="white" />
        </svg>
    </div>
    <!-- messenger icon end -->
    <!-- header area start-->
    <header>
        <!-- header area top -->
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 header-top__left">
                        <ul>
                            <li>
                                <a href="mailto:support@themuslinbd.com">support@themuslinbd.com</a>
                            </li>
                            <li>
                                <a href="tel:+8801777774324">+8801777 774324</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 header-top__right">
                        <ul>
                            <li>
                                <a href="{{ route('about') }}">About</a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- header area bottom -->
        <div class="header-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 desktop-search">
                        <form action="{{ route('home.search') }}" method="POST">
                            @csrf
                            <input type="text" name="name" placeholder="Search product title here"
                                maxlength="100" />
                            <button type="submit" class="search-icon hover-32">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.8291 14.004L10.8481 10.023C11.7424 8.91747 12.1783 7.51098 12.0662 6.0935C11.9541 4.67601 11.3023 3.35557 10.2454 2.40442C9.18843 1.45326 7.80683 0.943879 6.3854 0.981295C4.96398 1.01871 3.61108 1.60007 2.60564 2.60552C1.60019 3.61096 1.01883 4.96385 0.981417 6.38528C0.944001 7.8067 1.45338 9.18831 2.40454 10.2453C3.3557 11.3022 4.67613 11.9539 6.09362 12.0661C7.51111 12.1782 8.91759 11.7422 10.0231 10.848L14.0001 14.829C14.1095 14.9384 14.2579 14.9999 14.4126 14.9999C14.5673 14.9999 14.7157 14.9384 14.8251 14.829C14.9345 14.7196 14.996 14.5712 14.996 14.4165C14.996 14.2618 14.9345 14.1134 14.8251 14.004H14.8291ZM6.54212 10.917C5.67683 10.917 4.83097 10.6604 4.1115 10.1797C3.39204 9.69895 2.83128 9.01566 2.50015 8.21624C2.16901 7.41681 2.08237 6.53714 2.25118 5.68848C2.41999 4.83981 2.83667 4.06026 3.44853 3.44841C4.06038 2.83655 4.83993 2.41987 5.6886 2.25106C6.53727 2.08225 7.41693 2.16889 8.21636 2.50002C9.01579 2.83116 9.69907 3.39191 10.1798 4.11138C10.6605 4.83084 10.9171 5.6767 10.9171 6.542C10.9158 7.70191 10.4544 8.81394 9.63425 9.63413C8.81406 10.4543 7.70204 10.9157 6.54212 10.917Z"
                                        fill="black" />
                                </svg>
                            </button>
                        </form>

                    </div>
                    <div class="offset-lg-2 col-lg-2 header-bottom__logo">
                        <a href="{{ route('homepage') }}">
                            <img src="{{ asset('assets/muslin/images/static/logo-black.svg') }}" alt="logo" />
                        </a>
                    </div>
                    <div class="col-lg-5 header-bottom__info text-right">
                        <ul>
                            @if (auth()->check())
                                <li>
                                    <a href="{{ route('sign-out') }}" method="get">Logout</a>
                                </li>
                                <li>
                                    <a class="hover" href="{{ route('my-profile') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20"
                                            viewBox="0 0 21 20" fill="none">
                                            <path
                                                d="M17.9694 12.929C16.9005 11.8558 15.6009 11.0404 14.1694 10.545C15.187 9.84673 15.9544 8.84113 16.3593 7.67535C16.7643 6.50957 16.7856 5.2448 16.42 4.06607C16.0545 2.88733 15.3213 1.85652 14.3278 1.12443C13.3343 0.392341 12.1325 -0.00259399 10.8984 -0.00259399C9.66433 -0.00259399 8.4626 0.392341 7.46908 1.12443C6.47557 1.85652 5.7424 2.88733 5.37686 4.06607C5.01131 5.2448 5.03257 6.50957 5.43753 7.67535C5.84248 8.84113 6.60988 9.84673 7.62744 10.545C5.66308 11.2275 3.95985 12.5044 2.75406 14.1987C1.54828 15.8929 0.899742 17.9205 0.898438 20H2.46044C2.46044 17.7622 3.34939 15.6161 4.93172 14.0338C6.51406 12.4515 8.66017 11.5625 10.8979 11.5625C13.1357 11.5625 15.2818 12.4515 16.8642 14.0338C18.4465 15.6161 19.3354 17.7622 19.3354 20H20.8984C20.902 18.6863 20.6449 17.3849 20.1422 16.1711C19.6394 14.9574 18.9009 13.8554 17.9694 12.929ZM10.8984 10C10.064 10 9.2483 9.75257 8.55449 9.28898C7.86068 8.82539 7.31992 8.16647 7.00059 7.39555C6.68126 6.62463 6.59771 5.77633 6.7605 4.95792C6.9233 4.13952 7.32512 3.38776 7.91515 2.79773C8.50519 2.20769 9.25695 1.80587 10.0754 1.64308C10.8938 1.48029 11.7421 1.56384 12.513 1.88316C13.2839 2.20249 13.9428 2.74325 14.4064 3.43706C14.87 4.13087 15.1174 4.94657 15.1174 5.78101C15.1161 6.89955 14.6712 7.9719 13.8803 8.76283C13.0893 9.55376 12.017 9.99869 10.8984 10Z"
                                                fill="black" />
                                        </svg>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('sign-in') }}">Login</a>
                                </li>
                            @endif


                            <li id="show-addToFav">
                                <a class="hover" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="18"
                                        viewBox="0 0 21 18" fill="none">
                                        <path
                                            d="M10.8984 17.794C10.614 17.7939 10.3392 17.6905 10.1254 17.503C9.31738 16.797 8.53838 16.133 7.85138 15.547C6.07345 14.1294 4.41274 12.5706 2.88538 10.886C1.65137 9.55727 0.944823 7.82379 0.898378 6.01101C0.871463 4.43873 1.43812 2.91407 2.48538 1.74101C2.9935 1.18749 3.61197 0.746585 4.30092 0.446718C4.98987 0.146852 5.73401 -0.00531942 6.48538 1.33974e-05C7.62573 -0.00498111 8.73396 0.37759 9.62838 1.08501C10.113 1.46398 10.5407 1.91053 10.8984 2.41101C11.2563 1.91011 11.6844 1.46322 12.1694 1.08401C13.0637 0.377163 14.1715 -0.00503875 15.3114 1.33974e-05C16.0627 -0.00531942 16.8069 0.146852 17.4958 0.446718C18.1848 0.746585 18.8033 1.18749 19.3114 1.74101C20.3586 2.91407 20.9253 4.43873 20.8984 6.01101C20.8526 7.82259 20.1476 9.55524 18.9154 10.884C17.3883 12.5683 15.728 14.1267 13.9504 15.544C13.2624 16.131 12.4814 16.796 11.6724 17.504C11.4581 17.6913 11.183 17.7944 10.8984 17.794ZM6.48538 1.17201C5.89597 1.16733 5.31213 1.2862 4.77148 1.52096C4.23082 1.75572 3.74536 2.10116 3.34638 2.53501C2.49895 3.49199 2.04302 4.73303 2.06938 6.01101C2.11428 7.54995 2.72446 9.01842 3.78338 10.136C5.2688 11.7671 6.88154 13.2775 8.60638 14.653C9.29638 15.241 10.0784 15.907 10.8924 16.619C11.7114 15.906 12.4924 15.238 13.1854 14.65C14.9102 13.2748 16.5229 11.7648 18.0084 10.134C19.0672 9.01637 19.6774 7.54792 19.7224 6.00901C19.7488 4.7315 19.2932 3.49088 18.4464 2.53401C18.0475 2.09998 17.5621 1.75435 17.0214 1.51942C16.4807 1.28448 15.8969 1.16546 15.3074 1.17001C14.4269 1.16709 13.5715 1.46326 12.8814 2.01001C12.3389 2.44433 11.8807 2.97443 11.5294 3.57401C11.4637 3.6838 11.3707 3.77467 11.2594 3.83778C11.1481 3.90088 11.0223 3.93405 10.8944 3.93405C10.7664 3.93405 10.6407 3.90088 10.5294 3.83778C10.4181 3.77467 10.3251 3.6838 10.2594 3.57401C9.90927 2.97506 9.45239 2.44532 8.91138 2.01101C8.22113 1.46455 7.36575 1.16872 6.48538 1.17201Z"
                                            fill="black" />
                                    </svg>
                                </a>
                                @if (auth()->check())
                                    @php
                                        $all_wishlist_items = \Gloudemans\Shoppingcart\Facades\Cart::instance(
                                            'wishlist',
                                        )->content();
                                        $wishlist = true;

                                    @endphp

                                    <!-- <span>{{ count($all_wishlist_items) ? count($all_wishlist_items) : 0 }}</span> -->
                                @endif
                            </li>

                            @php
                                $cartData = Cart::instance('default')->content();
                            @endphp

                            <li id="show-addToCart" class="hover">
                                <a href="#">

                                    <svg width="21" height="18" viewBox="0 0 21 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.34244 11.719H17.9684C18.0957 11.7189 18.2195 11.6774 18.321 11.6007C18.4226 11.524 18.4965 11.4164 18.5314 11.294L20.8754 3.094C20.9003 3.00683 20.9047 2.91506 20.8881 2.82592C20.8716 2.73678 20.8345 2.65271 20.78 2.5803C20.7254 2.5079 20.6548 2.44914 20.5736 2.40866C20.4925 2.36818 20.4031 2.34707 20.3124 2.347H5.99044L5.57244 0.459003C5.54347 0.328498 5.47074 0.211821 5.36634 0.128333C5.26193 0.0448447 5.13212 -0.000436373 4.99844 3.17004e-06H1.48444C1.32902 3.17004e-06 1.17997 0.0617423 1.07007 0.171639C0.960177 0.281535 0.898438 0.430586 0.898438 0.586003C0.898438 0.74142 0.960177 0.890471 1.07007 1.00037C1.17997 1.11026 1.32902 1.172 1.48444 1.172H4.53044L6.64544 10.692C6.27445 10.8528 5.97023 11.1366 5.78405 11.4955C5.59786 11.8544 5.54109 12.2665 5.62329 12.6624C5.7055 13.0583 5.92167 13.4138 6.23536 13.6689C6.54905 13.924 6.9411 14.0632 7.34544 14.063H17.9684C18.1239 14.063 18.2729 14.0013 18.3828 13.8914C18.4927 13.7815 18.5544 13.6324 18.5544 13.477C18.5544 13.3216 18.4927 13.1725 18.3828 13.0626C18.2729 12.9527 18.1239 12.891 17.9684 12.891H7.34344C7.18802 12.891 7.03897 12.8293 6.92907 12.7194C6.81918 12.6095 6.75744 12.4604 6.75744 12.305C6.75744 12.1496 6.81918 12.0005 6.92907 11.8906C7.03897 11.7807 7.18802 11.719 7.34344 11.719H7.34244ZM19.5354 3.519L17.5264 10.55H7.81344L6.25144 3.516L19.5354 3.519Z"
                                            fill="black" />
                                        <path
                                            d="M6.75781 15.821C6.75781 16.1687 6.86092 16.5086 7.05409 16.7977C7.24726 17.0868 7.52182 17.3121 7.84305 17.4452C8.16428 17.5782 8.51776 17.613 8.85878 17.5452C9.1998 17.4774 9.51305 17.3099 9.75891 17.0641C10.0048 16.8182 10.1722 16.505 10.24 16.164C10.3079 15.823 10.2731 15.4695 10.14 15.1482C10.0069 14.827 9.78161 14.5524 9.49251 14.3593C9.20341 14.1661 8.86351 14.063 8.51581 14.063C8.04972 14.0635 7.60287 14.2489 7.2733 14.5785C6.94373 14.9081 6.75834 15.3549 6.75781 15.821ZM8.51581 15.235C8.63171 15.235 8.745 15.2693 8.84137 15.3337C8.93774 15.3981 9.01285 15.4897 9.05721 15.5967C9.10156 15.7038 9.11316 15.8216 9.09055 15.9353C9.06793 16.049 9.01213 16.1534 8.93018 16.2354C8.84822 16.3173 8.74382 16.3731 8.63014 16.3957C8.51647 16.4183 8.39864 16.4067 8.29156 16.3624C8.18449 16.318 8.09295 16.2429 8.02856 16.1465C7.96417 16.0502 7.92981 15.9369 7.92981 15.821C7.93007 15.6656 7.99191 15.5168 8.10175 15.4069C8.21158 15.2971 8.36047 15.2352 8.51581 15.235Z"
                                            fill="black" />
                                        <path
                                            d="M15.0391 15.821C15.0391 16.1687 15.1422 16.5086 15.3353 16.7977C15.5285 17.0868 15.8031 17.3121 16.1243 17.4452C16.4455 17.5782 16.799 17.613 17.14 17.5452C17.4811 17.4774 17.7943 17.3099 18.0402 17.0641C18.286 16.8182 18.4534 16.505 18.5213 16.164C18.5891 15.823 18.5543 15.4695 18.4212 15.1482C18.2882 14.827 18.0628 14.5524 17.7737 14.3593C17.4846 14.1661 17.1448 14.063 16.7971 14.063C16.331 14.0635 15.8841 14.2489 15.5546 14.5785C15.225 14.9081 15.0396 15.3549 15.0391 15.821ZM16.7971 15.235C16.913 15.235 17.0263 15.2693 17.1226 15.3337C17.219 15.3981 17.2941 15.4897 17.3385 15.5967C17.3828 15.7038 17.3944 15.8216 17.3718 15.9353C17.3492 16.049 17.2934 16.1534 17.2114 16.2354C17.1295 16.3173 17.025 16.3731 16.9114 16.3957C16.7977 16.4183 16.6799 16.4067 16.5728 16.3624C16.4657 16.318 16.3742 16.2429 16.3098 16.1465C16.2454 16.0502 16.2111 15.9369 16.2111 15.821C16.2113 15.6656 16.2732 15.5168 16.383 15.4069C16.4928 15.2971 16.6417 15.2352 16.7971 15.235Z"
                                            fill="black" />
                                    </svg>
                                </a>
                                <span>{{ count($cartData) ? count($cartData) : 0 }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
        $categories = Category::where(['status_id' => 1])
            ->orderBy('sort_order')
            ->get();
        
        ?>
        <!-- main menu -->
        <div class="header-menu">
            <div class="container">
                <div class="row justify-content-center">
                    <nav>
                        @if ($categories)
                            <ul>
                                @foreach ($categories as $category)
                                    @if (count($category->subcategory) > 0)
                                        <li>
                                            <a
                                                href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
                                            <ul class="submenu">
                                                @foreach ($category->subcategory as $subcategory)
                                                    @if (count($subcategory->childcategory) > 0)
                                                        <li>
                                                            <a href="{{ route('category', $subcategory->slug) }}">{{ $subcategory->name }}
                                                            </a>
                                                            <ul class="submenu">
                                                                @foreach ($subcategory->childcategory as $child_category)
                                                                    <li>
                                                                        <a
                                                                            href="{{ route('category', $child_category->slug) }}">{{ $child_category->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a
                                                                href="{{ route('category', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li>
                                            <a
                                                href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
                                        </li>
                                    @endif
                                @endforeach
                                <li>
                                    <a href="{{ route('blogs') }}">Muslin Blog</a>
                                </li>
                            </ul>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
        <!-- mobile menu -->
        <div class="mobile-menu">
            <div class="mobile-menu-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-2 col-xs-2 hamburger">
                            <img id="hamburger-icon" src="{{ asset('assets/muslin/images/static/hamburger.svg') }}"
                                alt="hamburger" />
                        </div>
                        <div class="col-sm-10 col-xs-10 mobile-search" id="search">
                            <form action="{{ route('home.search') }}" method="POST">
                                @csrf
                                <input type="text" placeholder="Search your product here" />
                                <button type="submit" class="search-icon">
                                    <img src="{{ asset('assets/muslin/images/static/search-icon.svg') }}"
                                        alt="search-icon" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- mobile menu content -->
        <div id="menu-to-toggle" class="mobile-menu-content">
            <div class="container">


                <div class="row">
                    <div class="col clos-wrap justify-content-between">
                        <img id="close-icon" src="{{ asset('assets/muslin/images/static/close.svg') }}"
                            alt="close" />

                        @if (auth()->check())
                            <a href="{{ route('sign-out') }}">Logout</a>
                        @else
                            <a href="{{ route('sign-in') }}">Login</a>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col mobile-menu-content__wrap">
                        @if ($categories)
                            @foreach ($categories as $category)
                                @php
                                    $i = 0;
                                @endphp
                                
                                <div class="single-category">
                                    @if (count($category->subcategory) > 0)
                                        <h4>{{ $category->name }}</h4>

                                        @foreach ($category->subcategory as $keyy => $subcategory)
                                            @if (count($subcategory->childcategory) > 0)
                                                @php
                                                    $sanitizedId = str_replace(' ', '_', $subcategory->name);
                                                @endphp

                                                <div class="accordion" id="accordion{{ $sanitizedId }}">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h2 class="mb-0">
                                                                <button
                                                                    class="btn btn-link btn-block text-left collapsed"
                                                                    type="button" data-toggle="collapse"
                                                                    data-target="#{{ $sanitizedId }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="{{ $sanitizedId }}">
                                                                    <span>{{ $subcategory->name }}</span>
                                                                    <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                                                        alt="{{ $subcategory->name }}"
                                                                        class="img-fluid" />
                                                                </button>
                                                            </h2>
                                                        </div>
                                                        <div id="{{ $sanitizedId }}" class="collapse"
                                                            aria-labelledby="{{ $sanitizedId }}"
                                                            data-parent="#accordion{{ $sanitizedId }}">
                                                            <div class="card-body">
                                                                <ul>
                                                                    @foreach ($subcategory->childcategory as $child_category)
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('category', $child_category->slug) }}">{{ $child_category->name }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                @if ($i == 0)
                                                    <ul>
                                                @endif

                                                <li>
                                                    <a
                                                        href="{{ route('category', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                </li>

                                                @php
                                                    $i++;
                                                @endphp
                                            @endif
                                        @endforeach

                                        @if ($i > 0)
                                            </ul> {{-- Closing the list if it was opened --}}
                                        @endif
                                    @else
                                        <h4>
                                            <a
                                                href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
                                        </h4>
                                    @endif
                                </div>
                            @endforeach

                            <div class="single-category">
                                <h4>
                                    <a href="{{ route('blogs') }}">Muslin Blog</a>
                                </h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header area end -->


    @yield('content')

    @stack('styles')


    <!-- add to cart popup start -->
    <div class="cart-popup" id="cartPopup">
        <div class="el">
            <div class="cart-popup__wrap">
                <div class="cart-popup__wrap__top">
                    <p>Your cart</p>
                    <div id="close-addToCart">
                        <a class="close-hover" href="#">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L11 11" stroke="#221F1F" stroke-linecap="round" />
                                <path d="M1 11L11 1" stroke="#221F1F" stroke-linecap="round" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="cart-popup__bottom">

                    @if (!auth()->check())
                        {{ 'Please login first' }}
                    @else
                        @php
                            $totalPrice = 0;
                        @endphp

                        @if (!empty($cartData) && count($cartData) > 0)
                            @foreach ($cartData as $key => $cartDataItem)
                                <div class="cart-popup__bottom__item">
                                    <div class="cart-popup__bottom__item__thumb">
                                        <img src="{{ render_image($cartDataItem?->options['image'] ?? 0, render_type: 'path') }}"
                                            alt="{{ $cartDataItem->name }}" />
                                    </div>
                                    <div class="cart-popup__bottom__item__info">
                                        <p>
                                            <a href="{{ route('product.details', $cartDataItem?->options['slug']) }}">
                                                {{ $cartDataItem->name }}
                                            </a>
                                        </p>
                                        <ul>
                                            @if (!empty($cartDataItem?->options['color_name'] ?? null))
                                                <li>
                                                    Color: {{ $cartDataItem?->options['color_name'] }}
                                                </li>
                                            @endif
                                            @if (!empty($cartDataItem?->options['size_name'] ?? null))
                                                <li>
                                                    Size: {{ $cartDataItem?->options['size_name'] ?? null }}
                                                </li>
                                            @endif
                                        </ul>
                                        @php
                                            $currentItemPrice = $cartDataItem->price * $cartDataItem->qty;
                                            $totalPrice += $currentItemPrice;
                                        @endphp

                                        <h3 class="item-price">Tk
                                            {{ number_format($currentItemPrice, 2, '.', ',') }}</h3>
                                    </div>
                                    <div class="cart-popup__bottom__item__btns">
                                        <a class="hover-calc remove-cart-item" href="#"
                                            data-product_hash_id="{{ $cartDataItem->rowId }}">
                                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.87891 2.87868L7.12155 7.12132" stroke="#F9F9F9"
                                                    stroke-linecap="round" />
                                                <path d="M7.12109 2.87868L2.87845 7.12132" stroke="#F9F9F9"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </a>
                                        <div class="cart-popup__bottom__item__btns__qty cart-btns"
                                            data-product_hash_id="{{ $cartDataItem->rowId }}"
                                            data-product-id="{{ $key }}"
                                            data-product-price="{{ $cartDataItem->price }}"
                                            data-varinat-id="{{ $cartDataItem?->options?->variant_id ?? 'admin' }}">
                                            <ul>
                                                <li class="hover-plus-minus cart-decrease">
                                                    <svg width="8" height="2" viewBox="0 0 8 2"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1 1H7" stroke="#F9F9F9" stroke-linecap="round" />
                                                    </svg>
                                                </li>
                                                <li class="cart-qty">{{ $cartDataItem->qty }}</li>
                                                <li class="hover-plus-minus cart-increase">
                                                    <svg width="8" height="8" viewBox="0 0 8 8"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1 4H7" stroke="#F9F9F9" stroke-linecap="round" />
                                                        <path d="M4 1V7" stroke="#F9F9F9" stroke-linecap="round" />
                                                    </svg>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{ 'No product added yet.' }}

                        @endif

                    @endif

                </div>
            </div>
            @if (auth()->check() && !empty($cartData) && count($cartData) > 0)
                <div class="cart-popup__total">
                    <div class="cart-popup__total__title">
                        <p>Total</p>
                        <div class="cart-popup__total__title__content">
                            <div class="cart-popup__total__title__content__left">
                                <h4 id="cart-total-price">BDT {{ number_format($totalPrice, 2, '.', ',') }}</h4>
                            </div>
                            <a class="checkout-btn" href="{{ route('frontend.cart-checkout') }}">
                                <span>Checkout</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- add to cart end -->
    <!-- add to cart popup start -->
    <div class="fav-popup" id="favPopup">
        <div class="el">
            <div class="fav-popup__wrap">
                <div class="fav-popup__wrap__top">
                    <p>Your Favorite</p>
                    <div id="close-addToFav">
                        <a class="close-hover" href="#">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L11 11" stroke="#221F1F" stroke-linecap="round" />
                                <path d="M1 11L11 1" stroke="#221F1F" stroke-linecap="round" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="fav-popup__bottom">
                    @if (auth()->check())

                        @if (empty($all_wishlist_items->count()))
                            {{ 'No products added yet' }}
                        @else
                            @foreach ($all_wishlist_items as $key => $wishlist_item)
                                @php
                                    $productData = Product::where('id', $wishlist_item->id)->first();
                                @endphp

                                <div class="fav-popup__bottom__item">
                                    <div class="fav-popup__bottom__item__thumb">
                                        <img src="{{ render_image($wishlist_item?->options['image'] ?? 0, render_type: 'path') }}"
                                            alt="" />
                                    </div>
                                    <div class="fav-popup__bottom__item__info">
                                        <a
                                            href="{{ route('product.details', $productData->slug) }}">{{ $wishlist_item->name }}</a>
                                    </div>
                                    <div class="fav-popup__bottom__item__btns">
                                        <a class="hover-calc remove-wishlist" href="#"
                                            data-product_hash_id="{{ $wishlist_item->rowId }}">
                                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.87891 2.87868L7.12155 7.12132" stroke="#F9F9F9"
                                                    stroke-linecap="round" />
                                                <path d="M7.12109 2.87868L2.87845 7.12132" stroke="#F9F9F9"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                        @endif
                    @else
                        {{ 'Please login first.' }}

                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- add to favorite cart end -->
    <!-- footer start -->
    <footer>
        <div class="footer-desktop">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 logo">
                            <a href="{{ route('homepage') }}">
                                <img src="{{ asset('assets/muslin/images/static/footer-logo.svg') }}"
                                    alt="footer logo" />
                            </a>
                            <ul>
                                <li>
                                    The Muslin, Est. 2021, is a beloved brand under IconX Lifestyle Ltd., epitomizing a
                                    deep commitment to preserving Bangladesh's cultural legacy.
                                </li>
                            </ul>
                            <div class="socials desktoppp">
                                <a class="icon" target="_blank" href="https://www.facebook.com/themuslinbd">
                                    <svg width="8" height="12" viewBox="0 0 8 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4.92879 12V6.52697H6.76479L7.04079 4.39297H4.92879V3.03097C4.92879 2.41297 5.09979 1.99297 5.98579 1.99297H7.11479V0.0839667C6.56836 0.0264652 6.01923 -0.00157539 5.46979 -3.3339e-05C5.09586 -0.0272934 4.7205 0.0275896 4.37003 0.160766C4.01956 0.293942 3.70247 0.502186 3.44101 0.77089C3.17955 1.03959 2.98004 1.36225 2.85649 1.71623C2.73293 2.0702 2.68833 2.44692 2.72579 2.81997V4.39297H0.883789V6.52697H2.72579V12H4.92879Z"
                                            fill="black" />
                                    </svg>
                                </a>
                                <a class="icon" target="_blank" href="https://www.instagram.com/the_muslin">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.752 0H3.252C2.39021 -6.51914e-07 1.56366 0.342071 0.953902 0.951074C0.344148 1.56008 0.00106001 2.38621 0 3.248L0 8.748C-6.51914e-07 9.60979 0.342071 10.4363 0.951074 11.0461C1.56008 11.6559 2.38621 11.9989 3.248 12H8.748C9.60979 12 10.4363 11.6579 11.0461 11.0489C11.6559 10.4399 11.9989 9.61379 12 8.752V3.252C12 2.39021 11.6579 1.56366 11.0489 0.953902C10.4399 0.344148 9.61379 0.00106001 8.752 0ZM11.3 8.752C11.3 9.08661 11.2341 9.41794 11.106 9.72708C10.978 10.0362 10.7903 10.3171 10.5537 10.5537C10.3171 10.7903 10.0362 10.978 9.72708 11.106C9.41794 11.2341 9.08661 11.3 8.752 11.3H3.252C2.91706 11.3005 2.58529 11.235 2.27569 11.1072C1.96609 10.9794 1.68473 10.7918 1.44771 10.5551C1.21068 10.3185 1.02264 10.0374 0.894337 9.728C0.766038 9.4186 0.7 9.08694 0.7 8.752V3.252C0.699474 2.91706 0.764992 2.58529 0.892806 2.27569C1.02062 1.96609 1.20822 1.68473 1.44488 1.44771C1.68153 1.21068 1.9626 1.02264 2.272 0.894337C2.5814 0.766038 2.91305 0.7 3.248 0.7H8.748C9.08294 0.699474 9.41471 0.764992 9.72431 0.892806C10.0339 1.02062 10.3153 1.20822 10.5523 1.44488C10.7893 1.68153 10.9774 1.9626 11.1057 2.272C11.234 2.5814 11.3 2.91305 11.3 3.248V8.752Z"
                                            fill="black" />
                                        <path
                                            d="M6.00024 2.71484C5.35132 2.71484 4.71697 2.90727 4.17741 3.26779C3.63785 3.62831 3.21732 4.14073 2.96899 4.74026C2.72066 5.33978 2.65569 5.99948 2.78228 6.63593C2.90888 7.27238 3.22136 7.85701 3.68022 8.31586C4.13907 8.77472 4.72369 9.0872 5.36014 9.2138C5.99659 9.3404 6.65629 9.27542 7.25582 9.02709C7.85534 8.77876 8.36777 8.35823 8.72829 7.81867C9.08881 7.27911 9.28124 6.64476 9.28124 5.99584C9.28018 5.12599 8.93417 4.29207 8.31909 3.67699C7.70401 3.06192 6.87009 2.7159 6.00024 2.71484ZM6.00024 8.57384C5.49036 8.57384 4.99192 8.42265 4.56797 8.13937C4.14402 7.8561 3.8136 7.45347 3.61847 6.9824C3.42335 6.51133 3.3723 5.99299 3.47177 5.4929C3.57125 4.99282 3.81678 4.53346 4.17732 4.17292C4.53786 3.81238 4.99721 3.56685 5.4973 3.46738C5.99738 3.3679 6.51573 3.41896 6.9868 3.61408C7.45787 3.80921 7.86049 4.13963 8.14376 4.56358C8.42704 4.98753 8.57824 5.48596 8.57824 5.99584C8.5785 6.68002 8.30711 7.33631 7.8237 7.82047C7.34028 8.30463 6.68442 8.57705 6.00024 8.57784V8.57384Z"
                                            fill="black" />
                                        <path
                                            d="M9.36016 1.55322C9.16831 1.55322 8.98076 1.61011 8.82125 1.71669C8.66173 1.82328 8.5374 1.97477 8.46398 2.15202C8.39056 2.32926 8.37135 2.52429 8.40877 2.71246C8.4462 2.90062 8.53859 3.07346 8.67425 3.20911C8.8099 3.34477 8.98276 3.43715 9.17092 3.47458C9.35908 3.51201 9.5541 3.4928 9.73134 3.41938C9.90859 3.34596 10.0601 3.22163 10.1667 3.06212C10.2733 2.9026 10.3301 2.71506 10.3301 2.52322C10.3299 2.26604 10.2276 2.01947 10.0457 1.83762C9.86388 1.65576 9.61734 1.55349 9.36016 1.55322ZM9.36016 2.78922C9.30755 2.78922 9.25611 2.77362 9.21236 2.74439C9.16862 2.71516 9.13453 2.67362 9.1144 2.62502C9.09427 2.57641 9.089 2.52293 9.09926 2.47133C9.10953 2.41973 9.13485 2.37233 9.17205 2.33513C9.20925 2.29793 9.25665 2.27259 9.30825 2.26233C9.35985 2.25207 9.41333 2.25733 9.46194 2.27747C9.51054 2.2976 9.55209 2.33169 9.58132 2.37543C9.61055 2.41918 9.62615 2.47061 9.62615 2.52322C9.62615 2.55815 9.61925 2.59274 9.60589 2.62502C9.59252 2.65729 9.57294 2.68661 9.54824 2.71131C9.52354 2.73601 9.49421 2.75561 9.46194 2.76897C9.42966 2.78234 9.39509 2.78922 9.36016 2.78922Z"
                                            fill="black" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 contact-us-mobile">
                            <div class="menu-category">
                                <div class="menu-category__single">
                                    <h4>Contact Us</h4>
                                    <ul>
                                        <li>
                                            <a href="#">THE MUSLIN at Le Meridien, Dhaka Entresol 79/A Commercial
                                                Area,
                                                Airport Road, Khilkhet, Nikunja 2, Dhaka-1229</a>
                                        </li>
                                        <li>
                                            <a href="tel:+8801777774324">+8801777 774324</a>
                                        </li>
                                        <li>
                                            <a href="mailto:support@themuslinbd.com">support@themuslinbd.com</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="offset-lg-1 col-lg-5 menu">
                            <div class="menu-category">
                                <div class="menu-category__single">
                                    <h4>Menu</h4>
                                    <ul>
                                        <li>
                                            <a href="{{ route('registration') }}">Open Account</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('about') }}">About Us</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('contact') }}">Contact Us</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('faq') }}">FAQs</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('blogs') }}">Muslin Bolg</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('return.policy') }}">Pricing, Delivery & Return
                                                Policy</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('terms.condition') }}">Terms & Conditions</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="menu-category__single">
                                    <h4>Product Categories</h4>
                                    @if ($categories)
                                        <ul>
                                            @foreach ($categories as $category)
                                                <li>
                                                    <a
                                                        href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                            <div class="menu-category__single payment-mobile">
                                <h4>Accepted Payments</h4>
                                <img src="{{ asset('assets/img/payment.png') }}" alt="gateway">
                            </div>
                        </div>
                        <div class="col-lg-3 contact-us">
                            <div class="menu-category">
                                <div class="menu-category__single">
                                    <h4>Contact Us</h4>
                                    <ul>
                                        <li>
                                            <a target="_blank" href="https://maps.app.goo.gl/PPMtk8abNtEtaerX6"
                                                rel="noreferrer">THE MUSLIN at Le Meridien, Dhaka Entresol 79/A
                                                Commercial
                                                Area, Airport Road, Khilkhet, Nikunja 2, Dhaka-1229</a>
                                        </li>
                                        <li>
                                            <a href="tel:+8801777774324">+8801777 774324</a>
                                        </li>
                                        <li>
                                            <a href="mailto:support@themuslinbd.com">support@themuslinbd.com</a>
                                        </li>
                                    </ul>
                                    <div class="payment-desktop">
                                        <h4>Accepted Payments</h4>
                                        <img src="{{ asset('/assets/img/payment.png') }}" alt="gateway">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="container">
                    <div class="row justify-spance-between">
                        <div class="col-md-6">
                            <div class="left">
                                <p>© {{ date('Y') }} The Muslin. All Rights Reserved</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="right">
                                <p>
                                    <a target="_blank" href="https://www.dcastalia.com" rel="noreferrer">Designed &
                                        Developed by Dcastalia</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer area end -->


    {{-- Success Error Template --}}
    <!-- Success Message Alert -->
    <div class="alert alert-success bg-success text-white alert-dismissible fade" role="alert" id="SuccessToast">
        <strong>Success!</strong> This is a hidden success alert.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>


    <!-- Error Message Alert -->
    <div class="alert bg-danger text-white alert-dismissible fade" role="alert" id="ErrorToast">
        <strong>Success!</strong> This is a hidden success alert.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>


    {{-- js links  --}}
    <script src="{{ asset('assets/muslin/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @if ($controllerName == 'FrontendController' && $action == 'index')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"
            integrity="sha512-EZI2cBcGPnmR89wTgVnN3602Yyi7muWo8y1B3a8WmIv1J9tYG+udH4LvmYjLiGp37yHB7FfaPBo8ly178m9g4Q=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('assets/muslin/lib/bootstrap/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/light-gallery/lightgallery.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/layer-slider/layerslider.transitions.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/layer-slider/layerslider.kreaturamedia.jquery.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/slick-slider/slick.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/gsap/tweenmax.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/anim/jquery.blast.min.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/nice-select/jquery.nice-select.min.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/dc/image-preloader.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/dc/dc-animation.js') }}"></script>
        <script src="{{ asset('assets/muslin/js/global.js') }}"></script>
        <script src="{{ asset('assets/muslin/js/home.js') }}"></script>
    @else
        <script src="{{ asset('assets/muslin/lib/bootstrap/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/light-gallery/lightgallery.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/slick-slider/slick.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/gsap/tweenmax.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/dc/image-preloader.js') }}"></script>
        <script src="{{ asset('assets/muslin/lib/nice-select/jquery.nice-select.min.js') }}"></script>
        <script src="{{ asset('assets/muslin/js/global.js') }}"></script>
        <script src="{{ asset('assets/muslin/js/md5.js') }}"></script>
        <script src="{{ asset('assets/muslin/js/inner.js') }}"></script>
    @endif

    <script>
        @if ($message = session('success'))
            showSuccessAlert("{{ $message }}");
        @elseif ($message = session('error'))
            showErrorAlert("{{ $message }}");
        @endif
    </script>

    <script>
        function send_ajax_request(request_type, request_data, url, before_send, success_response, errors) {
            $.ajax({
                url: url,
                type: request_type,
                headers: {
                    'X-CSRF-TOKEN': "4Gq0plxXAnBxCa2N0SZCEux0cREU7h4NHObiPH10",
                },
                beforeSend: (typeof before_send !== "undefined" && typeof before_send === "function") ?
                    before_send : () => {
                        return "";
                    },
                processData: false,
                contentType: false,
                data: request_data,
                success: (typeof success_response !== "undefined" && typeof success_response === "function") ?
                    success_response : () => {
                        return "";
                    },
                error: (typeof errors !== "undefined" && typeof errors === "function") ? errors : () => {
                    return "";
                }
            });
        }

        function formatNumber(number, decimalPlaces = 2, decimalSeparator = '.', thousandsSeparator = ',') {
            // Ensure the input is a valid number
            if (isNaN(number)) {
                return "Invalid number";
            }

            // Round the number to the specified decimal places
            const roundedNumber = parseFloat(number).toFixed(decimalPlaces);

            // Split the number into integer and fractional parts
            const parts = roundedNumber.split('.');

            // Format the integer part with thousands separator
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

            // Join the integer and fractional parts with the decimal separator
            return parts.join(decimalSeparator);
        }


        function cartCalculation() {
            let totalPrice = 0;
            $(".cart-popup__bottom__item").each(function(index) {
                let itemData = $(this);
                let itemUnitPrice = Number(itemData.find('.cart-btns').data('product-price'));
                let itemPrice = itemUnitPrice * Number(itemData.find('.cart-qty').text().replace(/[^\d.-]/g, ''));
                itemData.find('.item-price').text('Tk ' + formatNumber(Math.round(itemPrice), 2, '.', ','))
                totalPrice += itemPrice;

            })
            $("#cart-total-price").text("Tk " + formatNumber(Math.round(totalPrice), 2, '.', ','));

        }
    </script>

    @stack('scripts')
    <script type="text/javascript">
        //redirect to muslin messenger profile
        $('.messanger-icon').on('click', function() {
            window.open('https://m.me/107549388090790', '_blank');
        });

        $(document).on("click", ".remove-wishlist", function(e) {
            $.ajax({
                url: '{{ route('product.remove-from-wish') }}',
                type: 'POST',
                data: {
                    rowId: $(this).attr("data-product_hash_id"),
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    showSuccessAlert(data.msg);
                    location.reload();
                },
                erorr: function(err) {
                    showErrorAlert('{{ __('An error occurred') }}');
                }
            });
        });


        $(document).on("click", ".remove-cart-item", function(e) {
            $.ajax({
                url: '{{ route('product.remove-from-cart') }}',
                type: 'POST',
                data: {
                    rowId: $(this).attr("data-product_hash_id"),
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    showSuccessAlert(data.msg);
                    location.reload();
                },
                erorr: function(err) {
                    showErrorAlert('{{ __('An error occurred') }}');
                }
            });
        });

        $(document).on("click", ".cart-increase, .cart-decrease", function(e) {
            let parentElement = $(this).closest('.cart-btns');
            let quantity = Number(parentElement.find(".cart-qty").text());

            if ($(this).hasClass('cart-increase')) {
                quantity += 1;
                // parentElement.find(".cart-qty").text(quantity);
            } else if ($(this).hasClass('cart-decrease')) {
                if (quantity > 1) {
                    quantity -= 1;
                    // parentElement.find(".cart-qty").text(quantity);
                }
            }

            let data = new FormData();
            data.append("_token", "{{ csrf_token() }}")
            data.append("rowId[]", parentElement.data("product_hash_id"))
            data.append("quantity[]", quantity)
            data.append("product_id[]", parentElement.data("product-id"))
            data.append("variant_id[]", parentElement.data("varinat-id"))

            send_ajax_request('post', data, "{{ route('product.update-cart') }}", () => {}, (data) => {

                if (data.type == 'success') {
                    parentElement.find(".cart-qty").text(quantity);
                    showSuccessAlert(data.msg);
                    cartCalculation();
                } else {

                    cartCalculation();

                    showErrorAlert(data.msg)
                }
            }, (err) => {
                let messages = err.responseJSON.error_messages;
                for (let i = 0; i < messages.length; i++) {
                    setTimeout(() => toastr.error(messages[i]), i * 550)
                }
                showErrorAlert(messages)

            })
        });
    </script>

    <script>
        // Bangladesh boundaries (approximate)
        const BD_BOUNDS = {
            north: 26.634, // Northernmost point
            south: 20.743, // Southernmost point
            east: 92.673,  // Easternmost point
            west: 88.028   // Westernmost point
        };

        // Function to check if coordinates are within Bangladesh
        function isWithinBangladesh(lat, lng) {
            return lat >= BD_BOUNDS.south && 
                   lat <= BD_BOUNDS.north && 
                   lng >= BD_BOUNDS.west && 
                   lng <= BD_BOUNDS.east;
        }

        // Function to store currency in session
        async function storeCurrency(currency) {
            try {
                const response = await fetch('/set-currency', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ currency })
                });

                if (response.ok) {
                    // Reload page after currency is set
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error setting currency:', error);
            }
        }

        // Main geolocation function
        function detectLocation() {
            if (!navigator.geolocation) {
                return null;
            }
            let currency_browser = "{{session('currency_browser')}}";
            let currency_ip = "{{session('currency')}}";

            if(currency_browser){
                console.log("{{session('currency_browser')}}");
                return null;
            }

            navigator.geolocation.getCurrentPosition(
                // Success callback
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Determine currency based on location
                    const currency = isWithinBangladesh(lat, lng) ? 'BDT' : 'USD';
                   
                    if(currency_ip && currency_ip == currency){
                        return null;
                    }
                    // Store currency and reload page
                    await storeCurrency(currency);
                },
                // Error callback
                (error) => {
                    console.log('Location access denied or error occurred');
                    return null;
                },
                // Options
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        }

        // Run when page loads
        document.addEventListener('DOMContentLoaded', detectLocation);
    </script>

</body>

</html>
