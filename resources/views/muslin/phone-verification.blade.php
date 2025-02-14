@extends('muslin.layout')
@section('title', 'Sign Up')
@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li><a href="{{ route('homepage') }}">Home</a></li>
                        <li class="active-breadcrumb">Sign Up</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb section end -->

    <!-- form area start -->
    <section class="form-area" style="min-height: 90svh;">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="subtitle">
                        <h2>Sign Up</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-area__form">

                    @if(session('validation-error'))
                        <span class="alert-danger">
                            {{ session('validation-error') }}
                        </span>
                    @endif

                    <form id="phone-registration" action="{{ route('check-phone-verification') }}" method="POST">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-12">

                                <label for="email">Username (Email Address or Phone Number)</label>
                                <div class="input-relative">
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="entity_value"
                                            name="entity_value"
                                            required
                                            placeholder="Enter your email address or phone number"
                                    />
                                    <button type="button" id="send_code"
                                            data-action="{{ route('submit-phone-verification') }}">Send Code
                                    </button>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="city">Verification Code</label>
                                <input
                                        type="number"
                                        class="form-control"
                                        id="lName"
                                        required
                                        name="otp_code"
                                        placeholder="Enter your verification code"
                                />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">

                                <button type="submit" class="btn-submit">
                                    <span>Sign Up</span>
                                </button>
                            </div>
                        </div>

                    </form>

                    <div class="form-row">
                        <div class="form-group mb-0 col-md-6">
                            <h6>Already have an account?</h6>
                            <a href="{{ route('sign-in') }}" class="btn-sign">
                                <span>Sign In</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- form area end -->

@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).delegate('#send_code', 'click', function (event, jqXHR, settings) {

            let form = $(this).closest('form'),
                form_id = form.attr('id');

            let actionUrl = $(this).attr('data-action');

            $.ajax({

                url: actionUrl,
                type: 'post',
                data: new FormData(document.getElementById(form_id)),
                processData: false,
                contentType: false,
                success: function (data) {

                    if (data.type == 'success') {
                        showSuccessAlert(data.msg);
                    } else {
                        showErrorAlert(data.msg);
                    }
                },
                error: function (jqXHR, exception) {
                    showErrorAlert('Sorry. Server Error!');
                }
            });

            return false;
        });
    </script>
@endpush