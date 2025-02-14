@extends('muslin.layout') @section('title', 'User Profile') @section('content')
<!-- breadcrumb section start -->
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul>
                    <li>
                        <a href="{{ route('homepage') }}">Home</a>
                    </li>
                    <li class="active-breadcrumb">User Profile</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- breadcrumb section end -->
{{-- <section class="form-area">--}}
{{-- @if (Auth::check())--}}
{{-- <h3>My Profile {{auth()->id()}} </h3>--}} {{-- @endif--}}
{{-- </section>--}}


<section class="account-information desktop">
    <div class="container">
        <div class="row">
            <!-- Tab Items (4 columns) -->
            <div class="col-sm-4">

                <div class="nav flex-column nav-pills show-in-desktop" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Account Information</a>
                    <a class="nav-link" id="address-tab" data-toggle="pill" href="#address" role="tab" aria-controls="address" aria-selected="true">Address</a>
                    <a class="nav-link" id="change-password-tab" data-toggle="pill" href="#change-password" role="tab" aria-controls="change-password" aria-selected="true">Change Password</a>
                    <a class="nav-link" id="orders-tab" data-toggle="pill" href="#orders" role="tab" aria-controls="orders" aria-selected="false">My Order</a>
                </div>

                <div class="show-in-mobile Select">
                   <select id="tabSelect">
                      <option value="profile active">Account Information</option>
                      <option value="address">Address</option>
                      <option value="change-password">Change Password</option>
                      <option value="orders">My Order</option>
                   </select>
                </div>

            </div>
            <!-- Tab Content (8 columns) -->

            <div class="col-lg-8 col-md-12">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <!-- Content for Tab 1 -->
                        <h1>Account Information</h1>
                        <form action="{{ route('my-profile-update') }}" method="post" id="profile-update">
                            @csrf 
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{$userData->first_name}}" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" id="lName" name="last_name" placeholder="Last Name" value="{{$userData->last_name}}" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label>Phone Number <?= $userData->username == $userData->phone ? "(Username)" : ''; ?></label>

                                    <input type="number" class="form-control" placeholder="Phone Number" style="width: 100%" name="phone" value="{{$userData->phone}}" <?= $userData->username == $userData->phone ? "readonly" : ''; ?> />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Email Address <?= $userData->username == $userData->email ? "(Username)" : ''; ?></label>
                                    <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{$userData->email}}" <?= $userData->username == $userData->email ? "readonly" : ''; ?> />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-6">
                                    <button type="button" id="saveChangesBtn" class="btn-submit">
                                        <span>Update</span>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="tab2-tab">


                        <h1 class="address-heading">Address</h1>
                        <div class="address-container">
                          <table>
                            <thead>
                              <tr>
                                <th>Address</th>
                                <th><div class="pr-4"></div></th>
                                <th><div class="pr-4"></div></th>
                                <th><div class="pr-4">Action</div></th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(!empty($all_shipping_address)) {

                                        foreach ($all_shipping_address as $key => $address) {
                                ?>
                                              <tr>
                                                <div class="single-item-ww">
                                                  <td class="info-address">

                                                    <p> {{ $address->name }} </p>
                                                        <p> {{ $address->email }} </p>
                                                        <p> {{ $address->phone }} </p>
                                                        <p> {{ $address->address }}

                                                            {{ 'Zip Code:' . $address->zip_code }}
                                                        </p>
                                                        <p> {{ $address->city}}, {{ !empty($address->state->name) ? $address->state->name : '' }}, {{ !empty($address->country->name) ? $address->country->name : '' }} </p>
                                                  </td>
                                                  <td>
                                                  </td>
                                                  <td>
                                                  </td>
                                                  <td>
                                                    <div class="edit-icons">
                                                        <a  href="{{ route('delete-address', $address->id) }}" onclick="return confirm('{{ __("Are sure about delete this address?") }}')" class="bg-black-icon">

                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1 1L11 11" stroke="white" stroke-linecap="round" />
                                                                <path d="M11 1L1 11" stroke="white" stroke-linecap="round" />
                                                            </svg>
                                                        </a>
                                                        <a href="#" data-url="{{ route('address-info', $address->id) }}" class="bg-black-icon address-edit">
                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M11.5288 3.93603C11.6781 3.7873 11.7966 3.61054 11.8774 3.4159C11.9583 3.22127 11.9999 3.01258 11.9999 2.80182C11.9999 2.59106 11.9583 2.38237 11.8774 2.18773C11.7966 1.9931 11.6781 1.81634 11.5288 1.66761L10.3341 0.472936C10.1853 0.323361 10.0085 0.204664 9.8137 0.123668C9.61891 0.0426728 9.41004 0.000976562 9.19908 0.000976562C8.98812 0.000976562 8.77925 0.0426728 8.58446 0.123668C8.38967 0.204664 8.21282 0.323361 8.06406 0.472936L0.610812 7.92618C0.404259 8.13152 0.243348 8.37812 0.138587 8.64987C0.0338267 8.92163 -0.0124259 9.21243 0.0028504 9.50328V11.4105C0.00263471 11.4875 0.0176422 11.5638 0.0470099 11.635C0.0763777 11.7062 0.119526 11.7708 0.173975 11.8253C0.228424 11.8797 0.293098 11.9229 0.36428 11.9523C0.435462 11.9816 0.511747 11.9966 0.588748 11.9964H2.49598C2.787 12.0125 3.07812 11.9669 3.35031 11.8627C3.62251 11.7585 3.86964 11.598 4.07554 11.3917C4.18585 11.2818 4.24799 11.1326 4.2483 10.9769C4.2486 10.8212 4.18705 10.6718 4.07717 10.5615C3.96729 10.4512 3.81809 10.389 3.6624 10.3887C3.5067 10.3884 3.35726 10.45 3.24694 10.5599C3.15607 10.6504 3.04701 10.7205 2.927 10.7658C2.80698 10.811 2.67873 10.8302 2.55073 10.8222H2.51314L1.17546 10.8271V9.48939C1.17663 9.47689 1.17663 9.4643 1.17546 9.4518C1.16743 9.3238 1.18666 9.19555 1.23188 9.07554C1.27709 8.95552 1.34727 8.84647 1.43777 8.75559L7.64813 2.54523L9.4573 4.35441L4.79054 9.01789C4.73353 9.07177 4.6879 9.13654 4.65635 9.20836C4.6248 9.28018 4.60798 9.3576 4.60687 9.43603C4.60576 9.51447 4.62039 9.59233 4.6499 9.66501C4.67941 9.73769 4.72319 9.80372 4.77866 9.85919C4.83413 9.91466 4.90015 9.95844 4.97284 9.98795C5.04552 10.0175 5.12338 10.0321 5.20182 10.031C5.28025 10.0299 5.35767 10.013 5.42949 9.98149C5.50131 9.94995 5.56607 9.90432 5.61995 9.8473L11.5288 3.93603ZM8.47835 1.71582L8.89347 1.30071C8.97505 1.21972 9.08535 1.17427 9.2003 1.17427C9.31526 1.17427 9.42556 1.21972 9.50715 1.30071L10.7018 2.49539C10.7828 2.57697 10.8283 2.68727 10.8283 2.80223C10.8283 2.91719 10.7828 3.02748 10.7018 3.10907L10.2867 3.52418L8.47835 1.71582Z" fill="white" />
                                                                <path d="M11.4145 10.8251H6.13082C5.97543 10.8251 5.82641 10.8868 5.71653 10.9967C5.60665 11.1065 5.54492 11.2556 5.54492 11.411C5.54492 11.5664 5.60665 11.7154 5.71653 11.8253C5.82641 11.9351 5.97543 11.9969 6.13082 11.9969H11.4145C11.5699 11.9969 11.7189 11.9351 11.8288 11.8253C11.9387 11.7154 12.0004 11.5664 12.0004 11.411C12.0004 11.2556 11.9387 11.1065 11.8288 10.9967C11.7189 10.8868 11.5699 10.8251 11.4145 10.8251Z" fill="white" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                  </td>
                                                </div>
                                              </tr>
                                <?php 
                                        }
                                    }
                                ?>

                            </tbody>
                          </table>
                        </div>

                    
                        <div class="new-address">
                            <form action="{{ route('add-address') }}" method="post" > 
                                
                                @csrf 
                                
                                <div class="form-row">
                                    <div class="form-group col-sm-4">
                                        <label for="phoneNumber">Name*</label>
                                        <input type="text" class="form-control" placeholder="Name" name="name" value="{{old('name')}}"  required="" />
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="phoneNumber">Phone Number* </label>
                                        <input type="number" class="form-control" placeholder="Phone Number" name="phone" value="{{old('phone')}}"  required="" />
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="phoneNumber">Email</label>
                                        <input type="email" class="form-control" placeholder="Email Address"  name="email" value="{{old('email')}}"/>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-4">
                                        <label>Country*</label>
                                        <select class="form-control country" required="" style="width: 100%" name="country_id">
                                            <option>Select</option>
                                            @foreach ($all_country as $key => $country)
                                                <option value="{{ $key }}" {{ old('country_id') && in_array($key, old('country_id')) ? 'selected' : '' }} >{{ $country }}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>State*</label>
                                        <select class="form-control state" required="" style="width: 100%" name="state_id">
                                            <option>Select</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="city">City*</label>
                                        <input type="text" class="form-control" id="city" placeholder="City" name="city" value="{{old('city')}}" required="" />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-8">
                                        <label for="address">Address*</label>
                                        <input type="text" class="form-control" placeholder="Address*" name="address" value="{{old('address')}}" required=""  />
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="zipcode">Zipcode</label>
                                        <input type="number" class="form-control" placeholder="Zipcode" name="zip_code" value="{{old('zip_code')}}"  />
                                    </div>
                                </div>

                                <button type="submit" class="btn-submit">
                                    <span>Save Address</span>
                                </button>

                            </form>
                        </div>


                        <div class="update-address">

                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" id="addNewAddressBtn" class="btn-submit">
                                    <span>Add New Address</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="tab3-tab">
                        <!-- Content for Tab 2 -->

                        <div class="order">
                            <h1>My Order</h1>

                            @if(!empty($all_orders) && $all_orders->count() > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Status</th>
                                        <th>Total Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_orders as $key => $order) 
                                        <tr>
                                            <td>#{{$order->invoice_number}}</td>
                                            <td>
                                                <button class="status-btn">
                                                    @if ($order->order_status == 'complete')
                                                        <span>{{ __('Complete') }}</span>
                                                    @elseif ($order->order_status == 'pending')
                                                        <span>{{ __('Pending') }}</span>
                                                    @elseif ($order->order_status == 'canceled')
                                                        <spa>{{ __('Canceled') }}</span>
                                                    @elseif ($order->order_status == 'processing')
                                                        <span>{{ __('Processing') }}</span>
                                                    @elseif ($order->order_status == 'canceled')
                                                        <span>{{ __('Canceled') }}</span>
                                                    @elseif ($order->order_status == 'rejected')
                                                        <span>{{ __('Rejected') }}</span>
                                                    @endif  
                                                </button>
                                            </td>
                                            <td>{{ float_amount_with_currency_symbol($order->paymentMeta?->total_amount) }}</td>
                                            <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                            <td>
                                                <a href="#" data-url="{{ route('order-details', $order->id) }}" class="order-details">
                                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.5 23.0081 0.5 15C0.5 6.99187 6.99187 0.5 15 0.5C23.0081 0.5 29.5 6.99187 29.5 15Z" stroke="black" />
                                                        <path d="M21.9109 14.6888C21.7855 14.5178 18.8057 10.5 14.9996 10.5C11.1935 10.5 8.21369 14.517 8.08902 14.6881C8.03118 14.7672 8 14.8626 8 14.9606C8 15.0586 8.03118 15.1541 8.08902 15.2332C8.21369 15.4042 11.1942 19.422 15.0003 19.422C18.8064 19.422 21.7862 15.4042 21.9116 15.2332C21.9692 15.1541 22.0001 15.0587 22 14.9609C21.9999 14.863 21.9687 14.7678 21.9109 14.6888ZM14.9996 18.4993C12.1959 18.4993 9.76773 15.8326 9.04942 14.9606C9.76701 14.0908 12.1901 11.4227 14.9996 11.4227C17.809 11.4227 20.2307 14.0894 20.9498 14.9614C20.2322 15.8333 17.809 18.4993 14.9996 18.4993Z" fill="black" />
                                                        <path d="M15.0013 12.1918C14.4537 12.1918 13.9183 12.3542 13.463 12.6584C13.0077 12.9627 12.6528 13.3951 12.4432 13.901C12.2336 14.407 12.1788 14.9637 12.2856 15.5008C12.3925 16.0379 12.6562 16.5313 13.0434 16.9185C13.4306 17.3058 13.924 17.5695 14.4611 17.6763C14.9982 17.7831 15.5549 17.7283 16.0609 17.5187C16.5668 17.3092 16.9993 16.9543 17.3035 16.4989C17.6078 16.0436 17.7702 15.5083 17.7702 14.9606C17.7692 14.2266 17.4772 13.5229 16.9581 13.0038C16.4391 12.4847 15.7354 12.1927 15.0013 12.1918ZM15.0013 16.8068C14.6362 16.8068 14.2792 16.6985 13.9756 16.4957C13.672 16.2928 13.4354 16.0045 13.2957 15.6671C13.1559 15.3298 13.1194 14.9586 13.1906 14.6005C13.2619 14.2424 13.4377 13.9134 13.6959 13.6552C13.9541 13.397 14.283 13.2212 14.6411 13.15C14.9992 13.0787 15.3704 13.1153 15.7078 13.255C16.0451 13.3947 16.3335 13.6314 16.5363 13.935C16.7392 14.2386 16.8474 14.5955 16.8474 14.9606C16.8469 15.4501 16.6522 15.9193 16.3061 16.2654C15.96 16.6115 15.4908 16.8062 15.0013 16.8068Z" fill="black" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else 

                                <p>Not order placed yet</p>

                            @endif
                            
                            <!-- <div class="loadMore-button">
                                <button style="width: 170px" class="btn-submit">
                                    <span>Load More</span>
                                </button>
                            </div> -->
                        </div>

                        <!-- invoice area -->
                        <div class="invoice">
                            
                        </div>
                        <!-- product list  -->
                    </div>

                    <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="tab1-tab">
                        <!-- Content for Tab 1 -->
                        <h1>Change Password</h1>
                        <form action="{{ route('change-password') }}" method="post" id="change-password-form">   
                            @csrf 
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <div class="show-field">
                                        <div id="oldPassId" class="old-password">
                                            <label for="oldPassword">Old Password</label>
                                            <input type="password" class="form-control" name="old_password" placeholder="Old Password" style="width: 100%" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="newPassword">News Password</label>
                                    <input type="password" class="form-control" name="new_password" placeholder="New Password" style="width: 100%" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="confirmPassword">Confirm New Password</label>
                                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm New Password" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-6">
                                    <button type="button" id="savePassword" class="btn-submit">
                                        <span>Update</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</section> 

@endsection


@push('scripts')
    <script type="text/javascript">

        $(document).delegate('#saveChangesBtn', 'click', function (event, jqXHR, settings) {

            let form = $(this).closest('form'),
                form_id = form.attr('id');

            let actionUrl = form.attr('action');

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


        $(document).delegate('#savePassword', 'click', function (event, jqXHR, settings) {

            let form = $(this).closest('form'),
                form_id = form.attr('id');
            let actionUrl = form.attr('action');

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
                error: function (xhr, exception) {
                    // showErrorAlert('Sorry. Server Error!');

                    var msg = '';
                    if (xhr.status === 422) {
                        // Handle validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            msg = msg + value[0];
                        });
                        
                        showErrorAlert(msg);
                    }
                }
            });

            return false;
        });


        $(document).delegate('.address-edit', 'click', function (event, jqXHR, settings) {

            let actionUrl = $(this).attr('data-url');

            $.ajax({
                url: actionUrl,
                type: 'get',
                success: function (data) {

                    if (data.type == 'success') {
                        $('.update-address').html(data.result);
                        $('.address-container').hide();
                        $('.new-address').hide();
                        $('#addNewAddressBtn').hide();
                        
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

        $(document).delegate('.order-details', 'click', function (event, jqXHR, settings) {

            let actionUrl = $(this).attr('data-url');

            $.ajax({
                url: actionUrl,
                type: 'get',
                success: function (data) {

                    if (data.type == 'success') {

                        $(".invoice").html(data.result);
                        $('.order').hide();
                        $('.invoice').show();
                        
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

        $(document).delegate('.invoice-close', 'click', function (event, jqXHR, settings) {
            $('.invoice').hide();
            $('.order').show();
            $(".invoice").html('');
        });

        $(document).on("change", ".country", function() {
            let id = $(this).val().trim();

            $.get('{{ route('country.state.info.ajax') }}', {
                id: id
            }).then(function(data) {
                $('.state').html(data);
            });
        });
        
    </script>
@endpush
















