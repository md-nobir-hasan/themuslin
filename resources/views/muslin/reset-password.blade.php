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
              <h2>Set New Password</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-area__form">
            <form method="post" action="{{ route('reset-password-submit') }}" id="forget-password">
              @csrf

                <input type="text" hidden name="forget_username" value=" {{ session('forget_username') }}">
                <input type="text" hidden name="foget_otp_code" value=" {{ session('foget_otp_code') }}">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="password">New Password</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    placeholder="Enter your email address or phone number"
                    name="password"
                  />
                </div>
                <div class="form-group col-md-12">
                  <label for="confirmPassword">Confirm New Password</label>

                  <input
                    type="password"
                    class="form-control"
                    id="confirmPassword"
                    name="password_confirmation"
                    placeholder="Enter confirm new password"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group mb-0 col-md-6">
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
