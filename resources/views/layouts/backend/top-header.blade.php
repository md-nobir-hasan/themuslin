<div class="dashboard-top-contents mb-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="top-inner-contents search-area top-searchbar-wrapper">
                <div class="dashboard-flex-contetns">
                    <div class="dashboard-left-flex">
                        <span class="date-text dashboard-left-date"> 20 Jan, 2022 07:20pm </span>
                        <div class="d-flex align-items-center">
                            <h2 class="heading-two dashboard-left-heading mt-2"> Welcome, Happy Hour </h2><h2 class="dashboard-left-heading mt-2 fw-500">&nbsp;- {{auth('admin')->user()->name}}</h2>
                        </div>
                    </div>
                    
                    <div class="dashboard-right-flex">
                        <div class="author-flex-contents">
                            <div class="author-icon">
                                <x-notification.header />
                            </div>
                            <div class="author-thumb-contents">
                                <div class="author-thumb">
                                    @php
                                        $admin = auth()->guard('admin')->user();
                                        $profile_img = get_attachment_image_by_id($admin->image, null, true);
                                    @endphp
                                    @if (!empty($profile_img))
                                        <img src="{{$profile_img['img_url']}}" alt="{{$admin->name}}">
                                    @endif
                                </div>

                                <ul class="author-account-list">
                                    <li class="list"><a href="{{route('admin.profile.update')}}">{{__('Edit Profile')}}</a></li>
                                    <li class="list"><a href="{{route('admin.password.change')}}">{{__('Password Change')}}</a></li>
                                    <li class="list"><a href="{{ route('admin.logout') }}">{{ __('Logout') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Search Bar -->
                <div class="search-bar">
                    <form class="menu-search-form" action="#">
                        <div class="search-close"> <i class="las la-times"></i> </div>
                        <input class="item-search" type="text" placeholder="{{ __("Search Here.....") }}">
                        <button type="submit"> {{ __("Search Now") }} </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>