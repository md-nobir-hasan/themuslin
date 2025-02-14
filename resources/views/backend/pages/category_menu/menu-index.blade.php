@extends('backend.admin-master')
@section('site-title')
    {{ __('All Category Menus') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-6">
                <x-msg.error />
                <x-msg.success />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Category Menus') }}</h4>
                    </div>

                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap">
                            <table class="table table-default">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_menu as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->title }}</td>
                                            <td>{{ $data->created_at->diffForHumans() }}</td>
                                            <td>
                                                @can('category-menu-delete')
                                                    @if ($data->status != 'default')
                                                        <x-delete-popover :url="route('admin.category.menu.delete', $data->id)" />
                                                    @endif
                                                @endcan
                                                @can('category-menu-edit')
                                                    <a class="btn btn-lg btn-primary btn-sm mb-2 me-1"
                                                        href="{{ route('admin.category.menu.edit', $data->id) }}">
                                                        <i class="ti-pencil"></i>
                                                    </a>
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
            @can('category-menu-new-menu')
                <div class="col-lg-6">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Add New Menu') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.category.menu.new') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">{{ __('Title') }}</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                placeholder="{{ __('Title') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button id="submit" type="submit"
                                                class="cmn_btn btn_bg_profile">{{ __('Create Category Menu') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('script')
    <script>

    </script>
@endsection
