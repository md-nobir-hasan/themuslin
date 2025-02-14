<li> {{ __("Selected Filter:") }} </li>
@foreach(request()->all() as $key => $value)
    @if(!empty($value) && $key !== '_token')
        @if(!empty($value) && $key !== 'page')
            <li> <a class="click-hide close-search-selected-item" data-key="{{ $key }}" href="javascript:void(0)"> {{ $value }} </a> </li>
        @endif
    @endif
@endforeach
<li> <a class="click-hide-parent clear-search" href="javascript:void(0)"> {{ __("Clear All") }} </a> </li>