@extends('backend.admin-master')
@section('site-title')
    {{ __('Add New User') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        @include('backend/partials/message')
        @include('backend/partials/error')
        <div class="row">
            <!-- basic form start -->
            <div class="col-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('New User') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.frontend.new.user') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ __('Enter name') }}" value="{{old('name')}}">
                            </div>
                            <div class="form-group">
                                <label for="username">{{ __('Username') }}</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="{{ __('Username') }}" value="{{old('username')}}">
                                <small
                                    class="text text-danger">{{ __('Remember this username, user will login using this username') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('Email') }}</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="{{ __('Email') }}" value="{{old('email')}}">
                            </div>
                            <div class="form-group">
                                <label for="phone">{{ __('Phone') }}</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="{{ __('Phone') }}" value="{{old('phone')}}">
                            </div>
                            <div class="form-group">
                                <label for="country">{{ __('Country') }}</label>
                                {!! get_country_field('country', 'country', 'form-control country') !!}
                            </div>
                            <div class="form-group">
                                <label for="state">{{ __('State') }}</label>

                                <select class="form-control state" id="state" name="state">
                                    <option>Select</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">{{ __('City') }}</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    placeholder="{{ __('City') }}" value="{{old('city')}}">
                            </div>
                            <div class="form-group">
                                <label for="zipcode">{{ __('Zipcode') }}</label>
                                <input type="text" class="form-control" id="zipcode" name="zipcode"
                                    placeholder="{{ __('Zipcode') }}" value="{{old('zipcode')}}">
                            </div>
                            <div class="form-group">
                                <label for="address">{{ __('Address') }}</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="{{ __('Address') }}" value="{{old('address')}}">
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="{{ __('Minimum 8 character') }}">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">{{ __('Password Confirm') }}</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="{{ __('Password Confirmation') }}">
                            </div>
                            <button type="submit" class="cmn_btn btn_bg_profile mt-4">{{ __('Add New User') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')
  <script type="text/javascript">

    //- State , Country dropdown

    $(document).on("change", ".country", function() {
        let id = $(this).val().trim();

        $.get('{{ route('country.state.info.ajax') }}', {
            id: id
        }).then(function(data) {
            $('.state').html(data);
        });
    });

  </script>
@endsection