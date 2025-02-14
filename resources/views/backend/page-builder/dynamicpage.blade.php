@extends('backend.admin-master')
@section('style')
    <x-pagebuilder.css />
@endsection
@section('site-title')
    {{ $page->title }} {{ __('Page Builder') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-msg.flash />
            <x-msg.error />
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Page Builder') }}</h4>
                    <div class="dashboard__card__header__right">
                        @can('page-edit')
                            <a class="btn btn-lg btn-secondary btn-sm mb-2 me-1" href="{{ route('admin.page.edit', $page->id) }}">
                                {{ __('Go Back') }}
                            </a>
                        @endcan

                        @can('page-all')
                            <a class="btn btn-lg btn-primary btn-sm mb-2 me-1" href="{{ route('admin.page') }}">
                                {{ __('All pages') }}
                            </a>
                        @endcan

                        <a class="btn btn-lg btn-info btn-sm mb-2 me-1" target="_blank"
                            href="{{ route('frontend.dynamic.page', ['slug' => $page->slug, 'id' => $page->id]) }}">
                            <i class="fas fa-external-link-alt"></i> {{ __('View Page') }}
                        </a>
                    </div>
                </div>
                <div class="dashboard__card__body mt-4">
                    <div id="page-builder-wrap" class="page-builder-content-wrap custom__form">
                        <div class="row g-4">
                            <div class="col-lg-8" id="parent-container">
                                <div class="page-builder-area-wrapper">
                                    <h4 class="dashboard__card__title">{{ __('Without Sidebar Layout') }}</h4>
                                    <ul id="dynamic_page"
                                        class="sortable available-form-field main-fields sortable_widget_location mt-4">
                                        {!! \App\PageBuilder\PageBuilderSetup::get_saved_addons_for_dynamic_page('dynamic_page', $page->id) !!}
                                    </ul>
                                </div>

                                <div class="row mt-4" id="container_wrapper">
                                    <h4 class="dashboard__card__title mb-3">{{ __('With Sidebar Layout') }}</h4>
                                    <div class="col-md-5">
                                        <div class="page-builder-area-wrapper extra-title">
                                            <ul id="dynamic_page_left_sidebar"
                                                class="sortable available-form-field main-fields sortable_widget_location margin-bottom-15">
                                                {!! \App\PageBuilder\PageBuilderSetup::get_saved_addons_for_dynamic_page('dynamic_page_left_sidebar', $page->id) !!}
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-7 page-builder-area-wrapper extra-title">
                                        <ul id="dynamic_page_right_sidebar"
                                            class="sortable available-form-field main-fields sortable_widget_location margin-bottom-15">
                                            {!! \App\PageBuilder\PageBuilderSetup::get_saved_addons_for_dynamic_page(
                                                'dynamic_page_right_sidebar',
                                                $page->id,
                                            ) !!}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="search-wrap">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="search_addon_field"
                                            placeholder="{{ __('Search Addon') }}" name="s">
                                    </div>
                                </div>
                                <div class="all-addons-wrapper mt-4">
                                    <ul id="sortable_02" class="available-form-field all-widgets sortable_02">
                                        {!! \App\PageBuilder\PageBuilderSetup::get_admin_panel_widgets() !!}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection
@section('script')
    <script>
        /*-----------------------------------
         *  COLOR Picker INIT FUnction
         * ---------------------------------*/

        function colorPickerInit(selector) {

            $.each(selector, function(index, value) {
                var el = $(this);
                el.spectrum({
                    showAlpha: true,
                    showPalette: true,
                    cancelText: '',
                    showInput: true,
                    allowEmpty: true,
                    chooseText: '',
                    maxSelectionSize: 2,
                    color: el.next('input').val(),
                    change: function(color) {
                        el.next('input').val(color ? color.toRgbString() : '');
                        el.css({
                            'background-color': color ? color.toRgbString() : ''
                        });
                    },
                    move: function(color) {
                        el.next('input').val(color ? color.toRgbString() : '');
                        el.css({
                            'background-color': color ? color.toRgbString() : ''
                        });
                    },
                    palette: [
                        [
                            "{{ get_static_option('site_color') }}",
                            "{{ get_static_option('site_main_color_two') }}",
                            "{{ get_static_option('site_secondary_color') }}",
                            "{{ get_static_option('site_heading_color') }}",
                            "{{ get_static_option('site_paragraph_color') }}",
                        ]
                    ]
                });

                el.on("dragstop.spectrum", function(e, color) {
                    el.next('input').val(color.toRgbString());
                    el.css({
                        'background-color': color.toHexString()
                    });
                });
            });
        }
    </script>
    <x-pagebuilder.js />
    <x-pagebuilder.helper />
    <script>

        let summernoteConfig = {
            disableDragAndDrop: true,
            height: 200, //set editable area's height
            codeviewFilter: true,
            codeviewIframeFilter: true,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['Insert', ['link', 'table', 'video', 'picture']],
            ],
            styleTags: [
                'p',
                {
                    title: 'Blockquote',
                    tag: 'blockquote',
                    className: 'blockquote',
                    value: 'blockquote'
                },
                'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
            codemirror: { // codemirror options
                theme: 'monokai'
            },
            callbacks: {
                onPaste: function(e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window
                        .clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        };

        // Function to initialize the icon picker
        function initializeIconPicker(container, defaultIcon = 'lab la-accessible-icon') {
            // check container is empty or not if empty then go back
            if(container.html() == ''){
                container.iconpicker({
                    iconset: 'line-awesome',
                    rows: 4,
                    cols: 5,
                    icon: defaultIcon
                });

                container.find('.iconpicker-item').removeClass('iconpicker-selected');
                container.find('.iconpicker-item[title=".'+ defaultIcon +'"]').addClass('iconpicker-selected');
                container.parent().find('.iconpicker-component i').addClass(defaultIcon);

                container.on("iconpickerSelected", function (e){
                    $(this).parent().parent().children('input').val($(this).find('.iconpicker-selected').attr('title')?.replace('.',''));

                    if (!e.icon) {
                        // Destroy the icon picker instance
                        container.iconpicker('destroy');
                    }
                });
            }else{
                return '';
            }
        }

        $(document).on("click", ".icp.icp-dd", function (){
            // now find the container for this
            const currentEl = $(this);
            const container = currentEl.parent().find('.dropdown-menu');

            // Check if the icon picker is not already initialized
            if (!container.attr('data-iconpicker')) {
                // now initialize the iconpicker here by calling initializeIconPicker function
                initializeIconPicker(container, currentEl.attr('data-selected'))
            }
        })

            /*---------------------------------
             *   PREVIEW IMAGE
             * --------------------------------*/
            $(document).on('mouseover', '.all-addons-wrapper ul.ui-sortable li.widget-handler', function(e) {
                var imgUrl = $(this).find('a').attr('href');
                $(this).append('<div class="imageupshow"><img src="' + imgUrl + '" alt=""></div>');
            });

            $(document).on('mouseout', '.all-addons-wrapper ul.ui-sortable li.widget-handler', function(e) {
                $(this).find('.imageupshow').remove();
            });


            $(document).on('change', '.addon_advertisement_size', function(e) {
                e.preventDefault();
                alert(1);
            });

            $(document).on('click', '#add_two_column_container', function(e) {
                e.preventDefault();
                let appendConMarkup = `
                        <div class="col-md-12 page-builder-area-wrapper extra-title">
                            <ul id="dynamic_page_with_sidebar"
                                class="sortable available-form-field main-fields sortable_widget_location margin-bottom-15">
                                    {!! \App\PageBuilder\PageBuilderSetup::get_saved_addons_for_dynamic_page('dynamic_page_list', $page->id) !!}
                            </ul>
                        </div>
                    `;
                $('#container_wrapper').append(appendConMarkup);

                enable_draggable_addon();
                //
            });

            $(document).on('click', '#add_with_sidebar_container', function(e) {
                e.preventDefault();
                let appendConMarkup = `
                                    <div id="container_wrapper">
                                        <h4 class="main-title">{{ __('With Sidebar Layout') }}</h4>
                                        <div class="row g-4">
                                            <div class="col-md-5">
                                                <div class="page-builder-area-wrapper extra-title">
                                                    <ul id="dynamic_page_left_sidebar"
                                                        class="sortable available-form-field main-fields sortable_widget_location margin-bottom-15">
                                                            {!! \App\PageBuilder\PageBuilderSetup::get_saved_addons_for_dynamic_page('dynamic_page_left_sidebar', $page->id) !!}
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 page-builder-area-wrapper extra-title">
                                                <ul id="dynamic_page_right_sidebar"
                                                    class="sortable available-form-field main-fields sortable_widget_location margin-bottom-15">
                                                        {!! \App\PageBuilder\PageBuilderSetup::get_saved_addons_for_dynamic_page(
                                                            'dynamic_page_right_sidebar',
                                                            $page->id,
                                                        ) !!}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                        `;

                $('#parent-container').append(appendConMarkup);

                enable_draggable_addon();
                //
            });


            /*----------------------------------
             *   SEARCH WIDGETS
             * ---------------------------------*/
            $(document).on('keyup', '#search_addon_field', function() {
                var searchText = $(this).val();
                var allWidgets = $('.available-form-field.sortable_02 li > h4');
                $.each(allWidgets, function(index, value) {
                    var text = $(this).text();
                    var found = text.toLowerCase().match(searchText.toLowerCase().trim());
                    if (!found) {
                        $(this).parent().hide();
                    } else {
                        $(this).parent().show();
                    }
                });
            });

            /*-----------------------------------
             *   PAGE BUILDER CORE SCRIPT
             * ---------------------------------*/
            enable_draggable_addon();

            function enable_draggable_addon() {
                $(".sortable").sortable({
                    handle: "h4.top-part",
                    axis: "y",
                    helper: "clone",
                    placeholder: "sortable-placeholder",
                    receive: function(event, li) {
                        resetOrder(this.id);
                        setAddonLocation(event);
                    },
                    stop: function(event, li) {
                        resetOrder(this.id);
                    }
                });
            }


            function setAddonLocation(event) {
                var addonLocation = event.target.getAttribute('id');
                var allDraggerdAddon = $('#' + event.target.getAttribute('id')).find('li');
                allDraggerdAddon.each(function(index, value) {
                    $(this).find('input[name="addon_location"]').val(addonLocation);
                    $(this).find('input[name="addon_page_type"]').val(addonLocation);
                    $(this).find('input[name="addon_order"]').val(index + 1);
                });
            }

            function renderWidgetMarkup(event, li) {

                var addonClass = li.item.attr('data-name');
                var namespace = li.item.attr('data-namespace');
                var markup = '';
                $.ajax({
                    'url': "{{ route('admin.page.builder.get.addon.markup') }}",
                    'type': "POST",
                    'data': {
                        '_token': "{!! csrf_token() !!}",
                        'addon_class': addonClass,
                        'addon_namespace': namespace,
                        'addon_page_id': '{{ $page->id }}',
                        'addon_page_type': event.target.getAttribute('id'),
                        'addon_location': event.target.getAttribute('id'),
                    },
                    async: false,
                    success: function(data) {
                        markup = data;
                    }
                });

                li.item.clone()
                    .html(markup)
                    .insertAfter(li.item);

                const summerNote = $('.summernote');
                summernoteInit(summerNote);

                return markup;
            }

            $(".sortable_02").sortable({
                handle: "h4.top-part",
                connectWith: '.sortable_widget_location',
                helper: "clone",
                remove: function(e, li) {
                    renderWidgetMarkup(e, li);
                    $(this).sortable('cancel');
                }
            }).disableSelection();


            $('body').on('click', '.remove-widget', function(e) {
                //swal alert
                Swal.fire({
                    title: '{{ __('Are you sure to make this addon?') }}',
                    text: '{{ __('it will remove this addon with all data, you will not able to revert it again.') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, Accept it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $(this).parent().remove();
                        $(".sortable_02").sortable("refreshPositions");
                        var parent = $(this).parent();
                        var widgetType = parent.find('input[name="addon_type"]').val();
                        resetOrder();

                        if (widgetType === 'update') {
                            var widget_id = parent.find('input[name="id"]').val();
                            $.ajax({
                                'url': "{{ route('admin.page.builder.delete') }}",
                                'type': "POST",
                                'data': {
                                    '_token': "{!! csrf_token() !!}",
                                    'id': widget_id
                                },
                                success: function(data) {}
                            });
                        }

                    }
                });
            });

            $('body').on('click', '.expand', function(e) {
                $(this).parent().find('.content-part').toggleClass('show');
                var expand = $(this).children('i');
                var parent = $(this).parent();
                var classname = $(this).parent().data('name');
                if (expand.hasClass('ti-angle-down')) {
                    expand.attr('class', 'ti-angle-up');
                    $('body .nice-select').niceSelect();
                    $('.note-editable').trigger('focus');

                    var colorPickerNode = $('li[data-name="' + classname + '"] .color_picker');
                    colorPickerInit(colorPickerNode);

                    let summerNote = $('li[data-name="' + classname + '"] .summernote');
                    summernoteInit(summerNote);

                    flatpickr('.flatpickr ', {
                        enableTime: true,
                        altInput: true,
                        altFormat: "F j, Y H:i:s",
                        dateFormat: "Y-m-d H:i:s",
                    });

                    $(this).parent().find('.content-part').find('.nice-select').niceSelect();
                } else {
                    expand.attr('class', 'ti-angle-down');
                    $('body .nice-select').niceSelect('destroy');
                    $('li[data-name="' + classname + '"] .summernote').summernote('destroy');
                    // $('body .icp-dd').iconpicker('destroy');
                    $('body .nice-select').niceSelect('destroy');
                }
            });


            $('body').on('click', '.widget_save_change_button', function(e) {
                e.preventDefault();
                var parent = $(this).parent().find('.widget_save_change_button');
                parent.text('{{ __('Saving...') }}').attr('disabled', true);
                var form = $(this).parent();
                var widgetType = $(this).parent().find('input[name="addon_type"]').val();
                var formAction = $(this).parent().attr('action');
                var udpateId = '';
                var formContainer = $(this).parent();
                var sortableId = formContainer.parent().parent().parent().attr('id');

                $.ajax({
                    type: "POST",
                    url: formAction,
                    data: form.serializeArray(),
                    success: function(data) {
                        udpateId = data.id;
                        if (widgetType === 'new') {
                            formContainer.attr('action',
                                "{{ route('admin.page.builder.update') }}")
                            formContainer.find('input[name="addon_type"]').val('update');
                            formContainer.prepend('<input type="hidden" name="id" value="' +
                                udpateId + '">');
                        }
                        if (data === 'ok') {
                            form.append('<span class="text-success">{{ __('data saved success') }}</span>');
                        }
                        if (data.msg != undefined) {
                            form.append('<span class="d-block text-' + data.type + '">' + data.msg + '</span>');
                        }
                        setTimeout(function() {
                            form.find('span.text-success').remove();
                        }, 2000);

                    }
                });

                parent.text('saved..');
                setTimeout(function() {
                    parent.text('{{ __('Save Changes') }}').attr('disabled', false);
                }, 1000);
            });

            /**
             * reset order function
             * */
            function resetOrder(dropedOn) {
                var allItems = $('#' + dropedOn + ' li');
                $.each(allItems, function(index, value) {
                    $(this).find('input[name="addon_order"]').val(index + 1);
                    $(this).find('input[name="addon_location"]').val(dropedOn);
                    var id = $(this).find('input[name="id"]').val();
                    var widget_order = index + 1;
                    if (typeof id != 'undefined') {
                        reset_db_order(id, widget_order);
                    }
                });
            }

            /**
             * reorder function
             * */
            function reset_db_order(id, addon_order) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.page.builder.update.addon.order') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        addon_order: addon_order
                    },
                    success: function(data) {
                        //response ok if it saved success
                    }
                });
            }

            $(document).on('click', '.widget-area-expand', function(e) {
                e.preventDefault();
                var widgetbody = $(this).parent().parent().find('.widget-area-body');
                widgetbody.toggleClass('hide');
                var expand = $(this).children('i');
                if (expand.hasClass('ti-angle-down')) {
                    expand.attr('class', 'ti-angle-up');
                } else {
                    expand.attr('class', 'ti-angle-down');
                    var allWidgets = $(this).parent().parent().find('.widget-area-body ul li');
                    $.each(allWidgets, function(value) {
                        $(this).find('.content-part').removeClass('show');
                    });
                }
            });
    </script>
@endsection
