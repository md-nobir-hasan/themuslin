@if(!empty(filter_static_option_value('language_select_option',$global_static_field_data)))
    <li>
        <div class="language_dropdown" id="languages_selector">
            <div class="selected-language">{{get_language_name_by_slug($user_select_lang_slug)}} <i class="fas fa-caret-down"></i></div>
            <ul>
                @foreach($all_language as $lang)
                    <li data-value="{{$lang->slug}}">{{$lang->name}}</li>
                @endforeach
            </ul>
        </div>
    </li>
@endif