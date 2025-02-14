@if(get_static_option('home_page_navbar_button_status'))
@php
    $button_url =  !empty(filter_static_option_value('home_page_navbar_button_url',$global_static_field_data)) ? filter_static_option_value('home_page_navbar_button_url',$global_static_field_data) : route('frontend.campaign.user');
@endphp
<a href="{{$button_url}}">
    <div class="info-bar-item
        @if(isset($home) && $home == '02') style-01 @endif
        @if(isset($home) && $home == '03') style-02 @endif
            ">
        <div class="icon">
            <i class="{{filter_static_option_value('home_page_navbar_button_icon',$global_static_field_data)}}"></i>
        </div>
        <div class="content">
            <span class="title">{{filter_static_option_value('home_page_navbar_button_subtitle',$global_static_field_data)}}</span>
            <p class="details"> {{filter_static_option_value('home_page_navbar_button_title',$global_static_field_data)}}</p>
        </div>
    </div>
</a>
@endif