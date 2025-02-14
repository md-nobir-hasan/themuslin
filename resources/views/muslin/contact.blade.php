
@extends('muslin.layout')
 
@section('title', 'Contact Us')

@section('content')

	<!-- breadcrumb section start -->
    <section class="breadcrumb-area">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li>Contact Us</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- breadcrumb section end -->


    <!-- contact area start -->
    <section class="contact-area">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="subtitle">
              <h2>Contact Us</h2>
            </div>
          </div>
        </div>
        <div class="row contact-reverse">
          <div class="col-md-6">

			<form action="{{ route('contact.submit') }}" method="POST">

				@csrf

				<input type="hidden" name="spam_protector" />

              	<div class="form-row">
	                <div class="form-group col-md-6">
	                  	<label>First Name*</label>
	                  	<input
		                    type="text"
		                    class="form-control"
		                    required
		                    placeholder="Enter your first name"
		                    name="first_name"
	                  	/>
	                </div>
	                <div class="form-group col-md-6">
	                  <label for="city">Last Name*</label>
	                  <input
	                    type="text"
	                    class="form-control"
	                    required
	                    placeholder="Enter your last name"
		                name="last_name"

	                  />
	                </div>
              	</div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="phoneNumber">Phone Number*</label>
                  <input
                    type="number"
                    class="form-control"
                    required
                    placeholder="Enter your phone number"
                    style="width: 100%"
		            name="phone"

                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="city">Email Address*</label>
                  <input
                    required
                    type="email"
                    class="form-control"
                    placeholder="Enter your email address"
		            name="email"

                  />
                </div>
                <div class="form-group col-md-12">
                  <label for="message">Message*</label>
                  <textarea
                    class="form-control"
                    name="message"
                    rows="4"

                  ></textarea>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <button type="submit" class="btn-submit">
                    <span>Submit Message</span>
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-5 offset-md-1 contact-area__wrap">
            <div class="contact-area__wrap__img">

            	{!! render_image($data->imageInfo_1, class: 'modify-img lazyloads') !!}  
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- contact area end -->

@endsection
