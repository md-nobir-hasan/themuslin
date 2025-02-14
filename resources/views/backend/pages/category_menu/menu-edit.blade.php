@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/nestable.css') }}">
@endsection
@section('site-title')
    {{ __('Edit Menu') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.success />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Edit Category Menu') }}</h4>
                        @can('category-menu')
                            <div class="btn-wrapper">
                                <a href="{{ route('admin.category.menu.settings') }}" class="btn btn-primary btn-xs">
                                    {{ __('All Category Menus') }}
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.category.menu.update', $page_post->id) }}" id="menu_update_form"
                            method="post" enctype="multipart/form-data">
                            <input type="hidden" name="menu_id" id="menu_id" value="{{ $page_post->id }}">
                            @csrf
                            @php
                                $menu_content = '';
                                if (!empty($page_post->content)) {
                                    $menu_content = $page_post->content;
                                } else {
                                    $menu_content = '[{"ptype":"custom","pname":"Home","purl":"@url","id":1}]';
                                }
                            @endphp
                            <textarea id="menu_content" name="menu_content" class="form-control d-none">{{ $menu_content }}</textarea>
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ $page_post->title }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="menu-left-side-content">
                                        <h3 class="title">{{ __('Add Category menu items') }}</h3>
                                        <div class="accordion accordion-wrapper" id="add_menu_item_accordion">
                                            {!! render_product_category_list($page_post->lang ?? '') !!}
                                            {!! render_product_category_mega_menu_list($page_post->lang ?? '') !!}
                                            <div class="card">
                                                <div class="card-header" id="custom-links">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#custom-links-content"
                                                            aria-expanded="false" aria-controls="custom-links-content">
                                                            {{ __('Custom Links') }}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="custom-links-content" class="collapse"
                                                    aria-labelledby="custom-links" data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <p>{{ __("If you want to use root url of your site") }} <br>
                                                                {{ __("For that use this") }}
                                                                <b>@url</b>
                                                            </p>
                                                            <br>
                                                            <label
                                                                for="custom_url"><strong>{{ __('URL') }}</strong></label>
                                                            <input type="text" name="custom_url" id="custom_url"
                                                                class="form-control" placeholder="{{ __('https://') }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                for="custom_label_text"><strong>{{ __('Link Text') }}</strong></label>
                                                            <input type="text" name="custom_label_text"
                                                                id="custom_label_text" class="form-control"
                                                                placeholder="{{ __('label text') }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="button" id="add_custom_links"
                                                                class="btn btn-primary btn-xs mt-4 pr-4 pl-4">{{ __('Add To Category Menu') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="menu-structure-wrapper">
                                        <div class="dashboard__card">
                                            <div class="dashboard__card__header">
                                                <h3 class="dashboard__card__title">{{ __('Menu Structure') }}</h3>
                                            </div>
                                            <div class="dashboard__card__body mt-4">
                                                <section id="drop_down_menu_builder_wrapper">
                                                    <div class="dd" id="nestable">
                                                        <ol class="dd-list">
                                                            {{--                                                            @dd($page_post->content) --}}
                                                            @if (!empty($page_post->content))
                                                                {!! render_draggable_category_menu($page_post->id) !!}
                                                            @else
                                                                <li class="dd-item" data-id="1" data-purl="@url"
                                                                    data-pname="{{ __('Home') }}" data-ptype="custom">
                                                                    <div class="dd-handle">
                                                                        {{ __('Home') }}
                                                                    </div>
                                                                    <span class="remove_item">x</span>
                                                                </li>
                                                            @endif
                                                        </ol>
                                                    </div>
                                                </section><!-- END #demo -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" id="menu_structure_submit_btn"
                                            class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/backend/js/jquery.nestable.js') }}"></script>
    <script>
        $(document).ready(function() {

            //sub cateogry js

            $('#nestable').nestable({
                group: 1,
                maxDepth: 5
            }).on('change', function(e) {});

            function removeTags(str) {
                if ((str === null) || (str === '')) {
                    return false;
                }
                str = str.toString();
                return str.replace(/(<([^>]+)>)/ig, '');
            }


            $(document).on('click', '.add_mega_menu_to_menu', function(e) {
                e.preventDefault();

                var allList = $(this).parent().prev().find('input[type="checkbox"]:checked');
                var draggAbleMenuWrap = $('#nestable > ol');

                $.each(allList, function(index, value) {
                    $(this).attr('checked', false);
                    var draggAbleMenuLength = $('#nestable ol li').length + 1;
                    var allDataAttr = '';
                    var menuType = $(this).parent().parent().data('ptype');
                    var itemSelectMarkup = '';
                    allDataAttr += ' data-ptype="' + menuType + '"';
                    var randomID = Math.floor((Math.random() * 99999999) + 1);
                    var oldRandomId = randomID;
                    var AjaxRandomId = randomID;
                    draggAbleMenuWrap.append('<li class="dd-item" data-uniqueid="' + oldRandomId +
                        '" data-id="' + draggAbleMenuLength + '" ' + allDataAttr + '>\n' +
                        ' <div class="dd-handle">' + $(this).parent().text() + '</div>\n' +
                        '<span class="remove_item">x</span>' +
                        '<span class="expand"><i class="ti-angle-down"></i></span>' +
                        '<div class="dd-body hide">' +
                        '<p>{{ __('loading items') }}...</p>' +
                        '</div>' +
                        '</li>');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.mega.category.menu.item.select.markup') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            type: menuType,
                            lang: $('select[name="lang"]').val(),
                            menu_id: $('#menu_id').val(),
                        },
                        success: function(data) {
                            var html = data;
                            setTimeout(function() {
                                $('li[data-uniqueid="' + AjaxRandomId +
                                    '"] .dd-body').html(html);
                            }, 1000);
                        }
                    });

                });

            });
            $(document).on('click', '.add_page_to_menu', function(e) {
                e.preventDefault();
                //nestable
                var allList = $(this).parent().prev().find('input[type="checkbox"]:checked');
                var draggAbleMenuWrap = $('#nestable > ol');
                $.each(allList, function(index, value) {
                    $(this).attr('checked', false);
                    var draggAbleMenuLength = $('#nestable ol li').length + 1;
                    var allDataAttr = '';
                    var menuType = $(this).parent().parent().data('ptype');

                    if (menuType == 'static') {

                        var menuPslug = $(this).parent().parent().data('pslug');
                        var menuPname = $(this).parent().parent().data('pname');

                        allDataAttr += 'data-pname="' + menuPname + '"';
                        allDataAttr += ' data-pslug="' + menuPslug + '"';
                        allDataAttr += ' data-ptype="' + menuType + '"';

                    } else if (menuType == 'dynamic') {

                        var menuPid = $(this).parent().parent().data('pid');

                        allDataAttr += 'data-pid="' + menuPid + '"';
                        allDataAttr += ' data-ptype="' + menuType + '"';

                    } else if (menuType == 'custom') {

                        var menuPurl = $(this).parent().parent().data('purl');
                        var menuPName = $(this).parent().parent().data('pname');

                        allDataAttr += 'data-purl="' + menuPurl + '"';
                        allDataAttr += 'data-pname="' + menuPName + '"';
                        allDataAttr += ' data-ptype="' + menuType + '"';
                    } else {
                        var menuPid = $(this).parent().parent().data('pid');

                        allDataAttr += 'data-pid="' + menuPid + '"';
                        allDataAttr += ' data-ptype="' + menuType + '"';
                    }
                    draggAbleMenuWrap.append('<li class="dd-item" data-id="' + draggAbleMenuLength +
                        '" ' + allDataAttr + '>\n' +
                        ' <div class="dd-handle">' + $(this).parent().text() + '</div>\n' +
                        '<span class="remove_item">x</span>' +
                        '<span class="expand"><i class="ti-angle-down"></i></span>' +
                        '<div class="dd-body hide">' +
                        '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook"/>' +
                        '</div>' +
                        '</li>');
                });
            });

            $(document).on('click', '#add_custom_links', function(e) {
                e.preventDefault();

                var draggAbleMenuWrap = $('#nestable > ol');

                var draggAbleMenuLength = $('#nestable ol li').length + 1;

                var menuType = $(this).parent().parent().data('ptype');
                var menuName = $('#custom_label_text').val(); //custom_label_text
                var menuSlug = $('#custom_url').val(); //custom_url


                draggAbleMenuWrap.append('<li class="dd-item" data-id="' + draggAbleMenuLength +
                    '" data-ptype="custom" data-purl="' + removeTags(menuSlug) + '" data-pname="' +
                    removeTags(menuName) + '">\n' +
                    ' <div class="dd-handle">' + removeTags(menuName) + '</div>\n' +
                    '<span class="remove_item">x</span>' +
                    '<span class="expand"><i class="ti-angle-down"></i></span>' +
                    '<div class="dd-body hide"><input type="text" class="anchor_target" placeholder="eg: _blank"/><input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook"/></div>' +
                    '</li>');
                $('#custom_label_text').val('');
                $('#custom_url').val('');
            });
            $(document).on('input', '.icon_picker', function(e) {
                var el = $(this);
                var value = el.val();

                if (value != '') {
                    el.parent().parent().attr('data-icon', value);
                } else {
                    el.parent().parent().removeAttr('data-icon');
                }
            });
            $(document).on('input', '.anchor_target', function(e) {
                var el = $(this);
                var value = el.val();

                if (value != '') {
                    el.parent().parent().attr('data-antarget', value);
                } else {
                    el.parent().parent().removeAttr('data-antarget');
                }
            });
            $(document).on('input', '.static_pname', function(e) {
                var el = $(this);
                var value = el.val();

                if (value != '') {
                    el.parent().parent().attr('data-pname', value);
                } else {
                    el.parent().parent().removeAttr('data-pname');
                }
            });

            $(document).on('click', '.remove_item', function(e) {
                $(this).parent().remove();
            });

            $('body').on('change', 'select[name="items_id"]', function(e) {
                e.preventDefault();

                var el = $(this);
                var item_id = $(this).val();
                if (item_id != '') {
                    el.parent().parent().attr('data-items_id', item_id);
                } else {
                    el.parent().parent().removeAttr('data-items_id');
                }
            });

            $('body').on('change', 'select[name="sub_cat_items_id"]', function(e) {
                e.preventDefault();
                var el = $(this);
                var item_id = $(this).val();
                if (item_id != '') {
                    el.parent().parent().attr('data-sub_cat_items_id', item_id);
                } else {
                    el.parent().parent().removeAttr('data-sub_cat_items_id');
                }
            });

            $(document).on('click', '#menu_structure_submit_btn', function(e) {
                e.preventDefault();
                var alldata = $('#nestable').nestable('serialize');
                $('#menu_content').val(JSON.stringify(alldata));
                $(this).addClass("disabled")
                $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{ __('Updating') }}');
                $('#menu_update_form').trigger("submit");
            });

        });

        // fetching sub category
        $(document).on("change", ".product_mega_menu_category_selection", function() {
            let data = new FormData();
            data.append("category_id", $(this).val());
            data.append("_token", "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('admin.category.menu.sub_category') }}",
                data: data,
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    $(".sub_category_menus").html(data);
                }
            });
        });
    </script>
@endsection
