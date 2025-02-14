@extends('muslin.layout')
@section('title', 'Sign In')
@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li><a href="{{ route('homepage') }}">Home</a></li>
                        <li class="active-breadcrumb">Sign In</li>
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
                        <h2>Sign In</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-area__form">
                    <form method="post" action="{{ route('submit-login') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="zipCode">Username (Email Address or Phone Number)</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        id="fName"
                                        name="username"
                                        required
                                        placeholder="Enter your email address or phone number"
                                />
                            </div>
                            <div class="form-group col-md-12">
                                <label for="city">Password</label>
                                <a class="forgot-password" href="{{ route('forget-password') }}"
                                >Forgot Password</a
                                >
                                <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        required
                                        placeholder="Enter your password"
                                />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <button id="sign-in" type="submit" class="btn-sign">
                                    <span>Sign In</span>
                                </button>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group mb-0 col-md-6">
                                <h6>Do not have an account?</h6>

                                <a href="{{route('registration')}}" class="btn-sign">
                                    <span>Sign Up</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
