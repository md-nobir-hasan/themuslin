@extends('muslin.layout')
@section('title', 'Forget Password')
@section('content')

	 <!-- breadcrumb section start -->
    <section class="breadcrumb-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li><a href="{{ route('homepage') }}">Home</a></li>
              			<li class="active-breadcrumb">Forget Password</li>

                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb section end -->


    <!-- form area -->
    <section class="form-area" style="min-height: 90svh;">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="subtitle">
              <h2>Forget Password</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-area__form">
            <form method="post" action="{{ route('forget-password-otp-verify') }}" id="forget-password">
              @csrf
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label>Username (Email Address/Phone Number)</label>
                  <div class="input-relative">
                    <input
                            type="text"
                            class="form-control"
                            id="entity_value"
                            name="entity_value"
                            required
                            placeholder="Enter your email address or phone number"
                            value="{{ old('entity_value') }}"
                    />
                    <button type="button" id="send_code" data-action="{{ route('forget-password-submit') }}">
                    	Send Code
                    </button>
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <label for="city">Verification Code</label>
                  <input
                    type="number"
                    class="form-control"
                    id="lName"
                    placeholder="Enter your verification code"
                    name="otp_code"
                    maxlength="6"
                     pattern="\d{1,6}"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                	<button type="submit" class="btn-submit">
                        <span>Submit</span>
                    </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
@endsection



@push('scripts')
    <script type="text/javascript">
        $(document).delegate('#send_code', 'click', function (event, jqXHR, settings) {

            let form = $(this).closest('form'),
                form_id = form.attr('id');

            let actionUrl = $(this).attr('data-action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
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

