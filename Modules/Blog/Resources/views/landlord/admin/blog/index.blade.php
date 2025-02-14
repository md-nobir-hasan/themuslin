@extends('backend.admin-master')
@section('site-title')
    {{ __('All Blogs') }}
@endsection

@section('style')
    <x-media-upload.css />
    <x-datatable.css />

    <style>
        .product_status,
        .product_views {
            font-size: 13px;
        }
    </style>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12">
        <div class="dashboard__card">
            <div class="dashboard__card__header">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="dashboard__card__title">{{ __('All Blog Items') }} </h4>
                        <x-bulk-action />
                    </x-slot>

                    <x-slot name="right" class="d-flex">
                        <x-link-with-popover permissions="blog-create" url="{{ route(route_prefix() . 'admin.blog.new') }}"
                            extraclass="ml-3">
                            {{ __('Add New') }}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>
            </div>
            <div class="dashboard__card__body">
                <div class="msg-content">
                    <x-error-msg />
                    <x-flash-msg />
                </div>
                <div class="table-wrap table-responsive ">
                    <table class="table table-default table-striped table-bordered" id="all_blog_table">
                        <thead class="text-white" style="background-color: #b66dff">
                            <th class="no-sort">
                                <div class="mark-all-checkbox">
                                    <input type="checkbox" class="all-checkbox">
                                </div>
                            </th>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Title & Info') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Action') }}</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-media-upload.markup />
@endsection

@section('script')
    @include('components.datatable.yajra-scripts', ['only_js' => true])
    <script>
        (function($) {
            "use strict"; <
            x - bulk - action - js: url = "route(route_prefix().'admin.blog.bulk.action')" / >
                $(document).ready(function() {
                    $(document).on('change', 'select[name="lang"]', function(e) {
                        $(this).closest('form').trigger('submit');
                        $('input[name="lang"]').val($(this).val());
                    });

                    $('.table-wrap > table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route(route_prefix() . 'admin.blog', ['lang' => $default_lang]) }}",
                        columns: [{
                                data: 'checkbox',
                                name: '',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'title_info',
                                name: '',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'image',
                                name: '',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'category_id',
                                name: ''
                            },
                            {
                                data: 'date',
                                name: ''
                            },
                            {
                                data: 'action',
                                name: '',
                                orderable: false,
                                searchable: true
                            },
                        ]
                    });
                });
        })(jQuery);
    </script>
    <x-media-upload.js />
@endsection
