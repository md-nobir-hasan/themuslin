@extends('backend.admin-master')
@section('style')
    @include('backend.partials.datatable.style-enqueue')
    <x-media.css />
@endsection
@section('site-title')
    {{ __('Blogs') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.success />
            </div>
            <div class="col-lg-12 mt-2">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Blog Items') }}</h4>
                        <div class="dashboard__card__header__right">
                            <div class="bulk-delete-wrapper">
                                @can('blog-bulk-action')
                                    <x-bulk-action />
                                @endcan
                            </div>
                            @can('blog-new')
                                <div class="btn-wrapper">
                                    <a href="{{ route('admin.blog.new') }}"
                                        class="cmn_btn btn_bg_profile">{{ __('Add New') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default" id="all_blog_table">
                                <thead>
                                    <x-bulk-th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <!-- <th>{{ __('Image') }}</th>
                                    <th>{{ __('Author') }}</th>
                                    <th>{{ __('Category') }}</th> -->
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach($all_blog as $data)
                                        <tr>
                                            <td>
                                                <x-bulk-delete-checkbox :id="$data->id" />
                                            </td>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->title }}</td>
                                            <!-- <td>
                                                {!! render_attachment_preview_for_admin($data->image) !!}
                                            </td>
                                            <td>{{ $data->author }}</td>
                                            <td>
                                                @if (!empty($data->blog_categories_id))
                                                    {{ get_blog_category_by_id($data->blog_categories_id) }}
                                                @endif
                                            </td> -->
                                            <td>
                                                <x-status-span :status="$data->status" />
                                            </td>
                                            <td>{{ date_format($data->created_at, 'd M Y') }}</td>
                                            <td>
                                                @can('blog-delete')
                                                    <x-delete-popover :url="route('admin.blog.delete', $data->id)" />
                                                @endcan
                                                @can('blog-edit')
                                                    <x-edit-icon :url="route('admin.blog.edit', $data->id)" />
                                                @endcan
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
    @can('blog-bulk-action')
        <x-bulk-action.js :route="route('admin.blog.bulk.action')" />
    @endcan

    @include('backend.partials.datatable.script-enqueue')
@endsection
