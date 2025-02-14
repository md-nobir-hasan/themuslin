@extends('backend.admin-master')
@section('site-title')
    {{ __('All Menus') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-6">
                <x-msg.error />
                <x-msg.success />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Menus') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap">
                            <table class="table table-default">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_menu as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->title }}</td>
                                            <td>
                                                @if ($data->status == 'default')
                                                    <span class="alert alert-success">{{ __('Default Menu') }}</span>
                                                @else
                                                    @can('menu-default')
                                                        <form action="{{ route('admin.menu.default', $data->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-secondary btn-sm set_default_menu">{{ __('Set Default') }}</button>
                                                        </form>
                                                    @endcan
                                                @endif
                                            </td>
                                            <td>{{ $data->created_at->diffForHumans() }}</td>
                                            <td>
                                                @can('menu-delete')
                                                    @if ($data->status != 'default')
                                                        <x-delete-popover :url="route('admin.menu.delete', $data->id)" />
                                                    @endif
                                                @endcan
                                                @can('menu-edit')
                                                    <a class="btn btn-lg btn-primary btn-sm mb-2 me-1"
                                                        href="{{ route('admin.menu.edit', $data->id) }}">
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
            @can('menu-new-menu')
                <div class="col-lg-6">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Add New Menu') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.menu.new') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">{{ __('Title') }}</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                placeholder="{{ __('Title') }}">
                                        </div>
                                        <div class="form-group mt-4">
                                            <button id="submit" type="submit"
                                                class="cmn_btn btn_bg_profile">{{ __('Create Menu') }}</button>
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
        < x - btn.submit / >
    </script>
@endsection
