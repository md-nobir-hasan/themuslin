@extends('muslin.layout')
@section('title', 'Registration')
@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-center">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li class="active-breadcrumb">Registration</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- breadcrumb section end -->

    <section class="form-area" style="min-height: 90svh;">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="subtitle">
              <h2>Registration</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 form-area__form">
            <form method="post" action="{{ route('submit-registration') }}">
              @csrf
              <div class="form-row">
                <div class="form-group col-md-3">
                  <input type="text" hidden name="username" value="{{ session('username') }}">
                  <input type="text" hidden name="otp_code" value="{{ session('otp_code') }}">
                  <label for="fName">First Name*</label>
                  <input
                          type="text"
                          class="form-control"
                          name="first_name"
                          id="fName"
                          required
                          placeholder="Enter your first name"
                          value="{{ old('first_name') }}"
                  />
                </div>
                <div class="form-group col-md-3">
                  <label for="lName">Last Name*</label>
                  <input
                          type="text"
                          class="form-control"
                          id="lName"
                          name="last_name"
                          required
                          placeholder="Enter your last name"
                          value="{{ old('last_name') }}"

                  />
                </div>
                <div class="form-group col-md-3">
                  <label for="phoneNumber">Phone Number*</label>
                  <input
                          type="tel"
                          class="form-control"
                          id="phoneNumber"
                          name="phone"
                          required
                          placeholder="Enter your phone number"
                          value="{{ $requestEntityType == 'phone' ? session('username') : old('phone') }}"
                          {{ $requestEntityType == 'phone' ? 'readonly' : '' }}
                  />
                </div>
                <div class="form-group col-md-3">
                  <label for="email">Email Address*</label>

                   <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            required
            placeholder="Enter your email address"
            value="{{ old('email', $requestEntityType == 'email' ? session('username') : '') }}"
            {{ $requestEntityType == 'email' ? 'readonly' : '' }}
          />

                </div>
                <div class="form-group col-md-6">
                  <label for="address">Address*</label>
                  <input
                          type="text"
                          class="form-control"
                          name="address"
                          id="address"
                          required
                          placeholder="Enter your address"

                          value="{{ old('address') }}"

                  />
                </div>

                <div class="form-group col-md-3">
                  <label for="zipCode">Zip Code</label>

                  <input
                          type="number"
                          class="form-control"
                          id="zipCode"
                          name="zip_code"
                          placeholder="Enter zip code"
                          min="0"

                          value="{{ old('zip_code') }}"

                  />
                </div>
                <div class="form-group col-md-3">
                  <label for="city">City*</label>

                  <input
                          type="text"
                          class="form-control"
                          id="city"
                          name="city"
                          required
                          placeholder="Enter city"

                          value="{{ old('city') }}"

                  />
                </div>

                

                <div class="form-group col-md-4">
                  <label for="city">Country*</label>

                <select class="form-control country" required style="width: 100%" name="country">
          <option value="">Select</option>
          @foreach ($all_country as $key => $country)
            <option value="{{ $key }}" {{ old('country') == $key ? 'selected' : '' }}>
              {{ $country }}
            </option>
          @endforeach
        </select>
                </div>

                <div class="form-group col-md-4">
                  <label for="city">State</label>
                  <select class="form-control state" style="width: 100%" name="state">
                      <option value="">Select</option>
                  </select>
                </div>

              </div>

              <div class="form-row">

                <div class="form-group col-md-3">
                  <label for="password">Password*</label>

                  <input
                          type="password"
                          class="form-control"
                          id="password"
                          name="password"
                          required
                          placeholder="Enter your password"
                  />
                </div>
                <div class="form-group col-md-3">
                  <label for="confirmPassword">Confirm Password*</label>

                  <input
                          type="password"
                          class="form-control"
                          id="confirmPassword"
                          name="password_confirmation"
                          required
                          placeholder="Enter confirm password"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group mb-0 col-md-3">
                  <button id="forgot-password" type="submit" class="btn-submit">
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
    $(document).ready(function() {
    
        let oldCountry = "{{ old('country') }}";
    let oldState = "{{ old('state') }}";
        if (oldCountry) {
            $.get('{{ route('country.state.info.ajax') }}', { id: oldCountry })
            .then(function(data) {
              $('.state').html(data);
                if (oldState) {
                    $('.state').val(oldState);
                }
              });
        }

        // State, Country dropdown handling
        $(document).on("change", ".country", function() {
            let id = $(this).val().trim();

            if (id) { // Proceed only if the country ID is not empty
                $.get('{{ route('country.state.info.ajax') }}', { id: id })
                .then(function(data) {
                    $('.state').html(data);
                });
            }
        });
    });
</script>
@endpush
