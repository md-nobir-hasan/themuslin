@if(auth()->check())
    @php
        $route = auth()->guest() == 'admin' ? route('admin.home') : route('user.home');
    @endphp
    <li class="volunteer"><a href="{{$route}}">{{__('Dashboard')}}</a>  <span>/</span>
        <a href="{{ route('user.logout') }}"
           onclick="event.preventDefault();document.getElementById('logout_submit_btn__').dispatchEvent(new MouseEvent('click'))">
            {{ __('Logout') }}
        </a>
        <form id="userlogout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
            @csrf
            <button id="logout_submit_btn__" type="submit"></button>
        </form>
    </li>
@else
    <li class="volunteer"><a href="{{route('user.login')}}">{{__('Login')}}</a> <span>/</span> <a href="{{route('user.register')}}">{{__('Register')}}</a></li>
@endif