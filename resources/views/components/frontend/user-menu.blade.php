<li class="loginSystem">
@if(auth('web')->check())
{{-- user logged in  --}}
    <a href="{{ route('user.home') }}">
        <i class="lar la-user icon"></i>
    </a>
<!-- Show Logged info -->
<ul class="loggedWrapper">
    <li class="single"><a href="{{route('user.home')}}">{{__('Dashboard')}}</a> </li>
    <li class="single"><a href="{{route('user.home.edit.profile')}}">{{__('Edit Profile')}}</a> </li>
    <li class="single"><a href="{{route('user.home.change.password')}}">{{__('Change Password')}}</a> </li>
    <li class="single"><a href="{{route('user.product.order.all')}}">{{__('My Orders')}}</a> </li>
    <li class="single"><a href="{{route('user.shipping.address.all')}}">{{__('Shipping Address')}}</a> </li>
    <li class="single"><a href="{{ route('user.home.support.tickets') }}">{{__('Support Ticket')}}</a> </li>
    <li class="single">
        <a  href="{{ route('user.logout') }}"
           onclick="event.preventDefault();document.getElementById('menu_logout_submit_btn').dispatchEvent(new MouseEvent('click'));">
            {{ __('Logout') }}
        </a>
        <form  action="{{ route('user.logout') }}" method="POST" style="display: none;">
            @csrf
            <button id="menu_logout_submit_btn" type="submit"></button>
        </form>
    </li>
</ul>

@elseif(auth('admin')->check())
{{-- admin logged in  --}}
    <a href="{{ route('admin.home') }}">
        <i class="lar la-user icon"></i>
    </a>
@else
{{-- user not logged in  --}}
    <a href="{{ route('user.login') }}">
        <i class="lar la-user icon"></i>
    </a>
    
    <!-- Show Logged info -->
    <ul class="loggedWrapper">
        <li class="single"><a href="{{route('user.login')}}" >{{__('Sign In')}}</a> </li>
        <li class="single"><a href="{{route('user.register')}}">{{__('Sign Up')}}</a> </li>
    </ul>
@endif
</li>