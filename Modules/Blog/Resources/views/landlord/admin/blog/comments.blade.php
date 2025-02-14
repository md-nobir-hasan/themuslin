@extends('backend.admin-master')
@section('site-title')
    {{ __('Blog Comments') }}
@endsection
@section('style')
    <x-datatable.css />

    <style>
        a {
            text-decoration: none;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-error-msg />
                <x-flash-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Comments of : ') }} <span
                                class="text-primary ml-1">{{ count($blog_comments?->comments) ?? 0 }}</span> </h4>
                        <div class="dashboard__card__header__right">
                            <x-bulk-action />
                            <div class="btn-wrapper">
                                <a class="cmn_btn btn_bg_profile btn-sm"
                                    href="{{ route(route_prefix() . 'admin.blog') }}">{{ __('Go Back') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead class="text-white" style="background-color: #b66dff">
                                    <th class="no-sort">
                                        <div class="mark-all-checkbox">
                                            <input type="checkbox" class="all-checkbox">
                                        </div>
                                    </th>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Commented By') }}</th>
                                    <th>{{ __('Comments') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($blog_comments->comments ?? [] as $data)
                                        <tr>
                                            <td>
                                                <div class="bulk-checkbox-wrapper">
                                                    <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]"
                                                        value="{{ $data->id }}">
                                                </div>
                                            </td>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->commented_by }}</td>
                                            @php
                                                $url = route(route_prefix() . 'frontend.blog.single', $data->blog?->slug) . '#comment-area';
                                            @endphp
                                            <td>
                                                <a href="{{ $url }}"
                                                    target="_blank">{{ $data->comment_content }}</a>
                                            </td>

                                            <td>
                                                <x-delete-popover
                                                    url="{{ route(route_prefix() . 'admin.blog.comments.delete.all.lang', $data->id) }}" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <
                x - bulk - action - js: url = "route(route_prefix().'admin.blog.comments.bulk.action')" / >

            });
        })(jQuery)
    </script>
    <x-datatable.js />
@endsection
