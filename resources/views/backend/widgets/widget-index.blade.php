@extends('backend.admin-master')
@section('site-title')
    {{ __('All Widgets') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/jquery-ui.min.css') }}">
    <x-media.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-6">
                <x-error-message />
                <x-flash-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Widgets') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <ul id="sortable_02" class="available-form-field all-widgets sortable_02">
                            {!! render_admin_panel_widgets_list() !!}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="sidebar-list-wrap">
                    {!! get_admin_sidebar_list() !!}
                </div>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection

@section('script')
    <x-media.js />
    <script src="{{ asset('assets/frontend/js/jquery-ui.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                let body_element = $('body');

                $(".sortable").sortable({
                    axis: "y",
                    placeholder: "sortable-placeholder",
                    receive: function(event, ui) {
                        resetOrder(this.id);
                    },
                    stop: function(event, ui) {
                        resetOrder(this.id);
                    }
                }).disableSelection();

                $(".sortable_02").sortable({
                    connectWith: '.sortable_widget_location',
                    helper: "clone",
                    remove: function(e, li) {
                        let Item = li.item.length > 0 ? li.item[0] : li.item;
                        let widgetName = Item.getAttribute('data-name');
                        let markup = '';
                        $.ajax({
                            'url': "{{ route('admin.widgets.markup') }}",
                            'type': "POST",
                            'data': {
                                '_token': "{!! csrf_token() !!}",
                                'widget_name': widgetName,
                            },
                            async: false,
                            success: function(data) {
                                markup = data;
                            }
                        });
                        // markup += '</div>'; //end content div
                        li.item.clone()
                            .html(markup)
                            .insertAfter(li.item);
                        $(this).sortable('cancel');
                        return markup;
                    }
                }).disableSelection();

                body_element.on('click', '.remove-widget', function(e) {
                    $(this).parent().remove();
                    $(".sortable_02").sortable("refreshPositions");
                    let parent = $(this).parent();
                    let widgetType = parent.find('input[name="widget_type"]').val();
                    resetOrder();

                    if (widgetType === 'update') {
                        let widget_id = parent.find('input[name="id"]').val();
                        $.ajax({
                            'url': "{{ route('admin.widgets.delete') }}",
                            'type': "POST",
                            'data': {
                                '_token': "{!! csrf_token() !!}",
                                'id': widget_id
                            },
                            success: function(data) {}
                        });
                    }
                });

                body_element.on('click', '.expand', function(e) {
                    $(this).parent().find('.content-part').toggleClass('show');
                    let expand = $(this).children('i');
                    if (expand.hasClass('ti-angle-down')) {
                        expand.attr('class', 'ti-angle-up');
                    } else {
                        expand.attr('class', 'ti-angle-down');
                    }
                });

                body_element.on('click', '.widget_save_change_button', function(e) {
                    e.preventDefault();
                    let parent = $(this).parent().find('.widget_save_change_button');
                    parent.text('Saving...').attr('disabled', true);
                    let formClass = $(this).parent();
                    let formData = formClass.serializeArray();
                    let widgetType = $(this).parent().find('input[name="widget_type"]').val();
                    let formAction = $(this).parent().attr('action');
                    let updateId = '';
                    let formContainer = $(this).parent();

                    $.ajax({
                        type: "POST",
                        url: formAction,
                        data: formClass.serializeArray(),
                        success: function(data) {
                            updateId = data.id;
                            if (widgetType === 'new') {
                                formContainer.attr('action',
                                    "{{ route('admin.widgets.update') }}")
                                formContainer.find('input[name="widget_type"]').val(
                                    'update');
                                formContainer.prepend(
                                    '<input type="hidden" name="id" value="' +
                                    updateId + '">');
                            }
                        }
                    });
                    parent.text('saved..');
                    setTimeout(function() {
                        parent.text('Save Changes').attr('disabled', false);
                    }, 1000);
                });

                /**
                 * reset order function
                 **/
                function resetOrder(droppedOn) {
                    let allItems = $('#' + droppedOn + ' li');
                    $.each(allItems, function(index, value) {
                        $(this).find('input[name="widget_order"]').val(index + 1);
                        $(this).find('input[name="widget_location"]').val(droppedOn);
                        let id = $(this).find('input[name="id"]').val();
                        let widget_order = index + 1;
                        if (typeof id != 'undefined') {
                            reset_db_order(id, widget_order);
                        }
                    });
                }

                /**
                 * reorder function
                 **/
                function reset_db_order(id, widget_order) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.widgets.update.order') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            widget_order: widget_order
                        },
                        success: function(data) {
                            //response ok if it saved success
                        }
                    });
                }
            });
            $(document).on('click', '.widget-area-expand', function(e) {
                e.preventDefault();
                let widget_body = $(this).parent().parent().find('.widget-area-body');
                widget_body.toggleClass('hide');
                let expand = $(this).children('i');
                if (expand.hasClass('ti-angle-down')) {
                    expand.attr('class', 'ti-angle-up');
                } else {
                    expand.attr('class', 'ti-angle-down');
                    let allWidgets = $(this).parent().parent().find('.widget-area-body ul li');
                    $.each(allWidgets, function(value) {
                        $(this).find('.content-part').removeClass('show');
                    });
                }
            });
        }(jQuery));
    </script>
@endsection
