@extends('backend.admin-master')
@section('site-title')
    {{ __('All Pages') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    @php
        $pages = [];
    @endphp
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Pages') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('page-bulk-action')
                                <x-bulk-action.dropdown />
                            @endcan

                            @can('page-new')
                                <div class="btn-wrapper">
                                    <a href="{{ route('admin.page.new') }}"
                                        class="cmn_btn btn_bg_profile">{{ __('Add New Page') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can('page-bulk-action')
                                        <x-bulk-action.th />
                                    @endcan
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <!-- <th>{{ __('Date') }}</th> -->
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_pages as $page)
                                        <tr>
                                            @can('page-bulk-action')
                                                <x-bulk-action.td :id="$page->id" />
                                            @endcan
                                            <td>{{ $page->id }}</td>
                                            <td>
                                                {{ $page->title }}
                                                @if (isset($dynamic_page_ids[$page->id]))
                                                    @if ($dynamic_page_ids[$page->id] == 'home_page')
                                                        <strong class="text-info"> - {{ __('Home Page') }}</strong>
                                                    @elseif($dynamic_page_ids[$page->id] == 'blog_page')
                                                        <strong class="text-info"> - {{ __('Blog Page') }}</strong>
                                                    @elseif($dynamic_page_ids[$page->id] == 'product_page')
                                                        <strong class="text-info"> - {{ __('Product Page') }}</strong>
                                                    @endif
                                                @endif
                                            </td>
                                            <!-- <td>{{ $page->created_at->diffForHumans() }}</td> -->
                                            <td>
                                                @if ($page->status === 'publish')
                                                    <span class="alert alert-success">{{ __('Publish') }}</span>
                                                @else
                                                    <span class="alert alert-warning">{{ __('Draft') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (empty($dynamic_page_ids[$page->id]))
                                                    @can('page-delete')
                                                        <x-delete-popover :url="route('admin.page.delete', $page->id)" />
                                                    @endcan
                                                    <a class="btn btn-xs btn-info btn-sm mb-2 me-1" target="_blank"
                                                        href="{{ route('frontend.dynamic.page', ['slug' => $page->slug, 'id' => $page->id]) }}">
                                                        <i class="ti-eye"></i>
                                                    </a>
                                                @endif
                                                @can('page-edit')
                                                    <a class="btn btn-xs btn-primary btn-sm mb-2 me-1"
                                                        href="{{ route('admin.page.edit', $page->id) }}">
                                                        <i class="ti-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('page-builder-dynamic-page')
                                                    @if (!empty($page->page_builder_status))
                                                        <a href="{{ route('admin.dynamic.page.builder', ['type' => 'dynamic-page', 'id' => $page->id]) }}" target="_blank" class="btn btn-xs btn-secondary mb-2 me-1">
                                                            {{ __('Open Page Builder') }}
                                                        </a>
                                                    @endif
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
    <x-datatable.js />
    @can('page-bulk-action')
        <x-bulk-action.js :route="route('admin.page.bulk.action')" />
    @endcan
@endsection
