@extends('backend.admin-master')

@section('site-title')
    {{ __('Topbar Settings') }}
@endsection

@section('style')
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                @include('backend.partials.message')
                @include('backend.partials.error')
            </div>
            <div class="col-lg-6">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Top-bar Menu') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        @can('appearance-settings-topbar-update-social-item')
                            <form action="{{ route('admin.topbar.select.menu') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="topbar_menu">{{ __('Select Menu') }}</label>
                                    <select class="form-control" name="topbar_menu" id="topbar_menu">
                                        @foreach ($menus as $menu)
                                            <option value="{{ $menu->id }}"
                                                @if (get_static_option('topbar_menu') == $menu->id) selected @endif>
                                                {{ $menu->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="btn-wrapper mt-4">
                                    <button class="cmn_btn btn_bg_profile"
                                        type="submit">{{ __('Update Top-bar Menu') }}</button>
                                </div>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Social Icons') }}</h4>
                        @can('appearance-settings-topbar-new-social-item')
                            <div class="right-cotnent">
                                <a class="cmn_btn btn_bg_profile" data-bs-target="#add_social_icon" data-bs-toggle="modal"
                                    href="#1">
                                    {{ __('Add New Social Item') }}
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="dashboard__card__body mt-4">
                        @can('appearance-settings-topbar-all')
                            <table class="table table-default">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Icon') }}</th>
                                    <th>{{ __('URL') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_social_icons as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td><i class="{{ $data->icon }}"></i></td>
                                            <td>{{ $data->url }}</td>
                                            <td>
                                                @can('appearance-settings-topbar-delete-social-item')
                                                    <x-delete-popover :url="route('admin.delete.social.item', $data->id)" />
                                                @endcan
                                                @can('appearance-settings-topbar-update-social-item')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#social_item_edit_modal"
                                                        class="btn btn-xs btn-primary  mb-2 me-1 social_item_edit_btn"
                                                        data-id="{{ $data->id }}" data-url="{{ $data->url }}"
                                                        data-icon="{{ $data->icon }}">
                                                        <i class="ti-pencil"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('appearance-settings-topbar-new-social-item')
        <div class="modal fade" id="add_social_icon" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add Social Item') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.new.social.item') }}" method="post">
                        <div class="modal-body">
                            @csrf
                            <x-backend.icon-picker />
                            <div class="form-group">
                                <label for="social_item_link">{{ __('URL') }}</label>
                                <input type="text" name="url" id="social_item_link" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button id="submit" type="submit" class="btn btn-primary">{{ __('Add Social Item') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('top-bar-social-icon-edit')
        <div class="modal fade" id="social_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Social Item') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.update.social.item') }}" method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="social_item_id" value="">

                            <x-backend.icon-picker />
                            <div class="form-group">
                                <label for="social_item_edit_url">{{ __('Url') }}</label>
                                <input type="text" class="form-control" id="social_item_edit_url" name="url"
                                    placeholder="{{ __('Url') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button id="update" type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('script')
    <x-repeater />
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <
                x - btn.submit / >
                    <
                    x - btn.update / >

                    $(document).on('click', '.social_item_edit_btn', function() {
                        var el = $(this);
                        var id = el.data('id');
                        var url = el.data('url');
                        var icon = el.data('icon');
                        var form = $('#social_item_edit_modal');
                        form.find('#social_item_id').val(id);
                        form.find('#social_item_edit_icon').val(icon);
                        form.find('#social_item_edit_url').val(url);
                        form.find('.icp-dd').attr('data-selected', el.data('icon'));
                        form.find('.iconpicker-component i').attr('class', el.data('icon'));
                    });


            }) //document ready close
        })(jQuery);
    </script>
@endsection
