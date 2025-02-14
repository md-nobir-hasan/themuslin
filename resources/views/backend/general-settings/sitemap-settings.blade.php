@extends('backend.admin-master')
@section('site-title')
    {{ __('Sitemap Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Sitemap Settings') }}</h4>
                        <button type="submit" class="cmn_btn btn_bg_profile" data-bs-toggle="modal"
                            data-bs-target="#user_change_password_modal">{{ __('Generate Sitemap') }}</button>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap">
                            <table class="table table-default">
                                <thead>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Size') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_sitemap as $data)
                                        <tr>
                                            <td>{{ basename($data) }}</td>
                                            <td>{{ date('j F Y - h:m:s', filectime($data)) }}</td>
                                            <td>
                                                @if (trim(formatBytes(filesize($data))) === 'NAN')
                                                    {{ __('0 Byte') }}
                                                @else
                                                    {{ formatBytes(filesize($data)) }}
                                                @endif
                                            </td>
                                            <td>
                                                <a tabindex="0" class="btn btn-lg btn-danger btn-sm mb-2 me-1"
                                                    role="button" data-bs-toggle="popover" data-trigger="focus"
                                                    data-html="true" title=""
                                                    data-content="
                                                <h6>{{ __('Are you sure to delete this sitemap ?') }}</h6>
                                                <form method='post' action='{{ route('admin.general.sitemap.settings.delete') }}'>
                                                <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                                <input type='hidden' name='sitemap_name' value='{{ $data }}'>
                                                <br>
                                                    <input type='submit' class='btn btn-danger btn-sm' value='{{ __('Yes, Please') }}'>
                                                    </form>
                                                    ">
                                                    <i class="ti-trash"></i>
                                                </a>
                                                <a href="{{ asset('sitemap') }}/{{ basename($data) }}" download
                                                    class="btn btn-primary btn-sm mb-2 me-1"> <i class="fa fa-download"></i>
                                                </a>
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

    <div class="modal fade" id="user_change_password_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Generate Sitemap') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{ route('admin.general.sitemap.settings') }}" id="user_password_change_modal_form"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">{{ __('TItle') }}</label>
                            <input type="text" class="form-control" name="title"
                                placeholder="{{ __('Enter title') }}">
                        </div>
                        <div class="form-group">
                            <label for="site_url">{{ __('URL') }}</label>
                            <input type="text" class="form-control" name="site_url" placeholder="{{ __('Enter URL') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
