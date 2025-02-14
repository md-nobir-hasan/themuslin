@extends('vendor.vendor-master')
@section('site-title')
    {{ __('Vendor Create') }}
@endsection
@section('style')
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Your wallet page') }}</h4>
                        @if ($current_balance >= get_static_option('minimum_withdraw_amount'))
                            <div class="btn-wrapper">
                                <a href="{{ route('vendor.wallet.withdraw') }}" id="withdraw-button"
                                    class="cmn_btn btn_bg_profile">
                                    {{ __('Withdraw') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="row g-4 justify-content-center">
                            <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                                <div class="single-orders">
                                    <div class="orders-shapes">
                                    </div>
                                    <div class="orders-flex-content">
                                        <div class="contents">
                                            <span class="order-para ff-rubik"> {{ __('Current Balance') }} </span>
                                            <h2 class="order-titles">
                                                {{ float_amount_with_currency_symbol($current_balance) }} </h2>
                                        </div>
                                        <div class="icon">
                                            <i class="las la-tasks"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                                <div class="single-orders">
                                    <div class="orders-shapes">
                                    </div>
                                    <div class="orders-flex-content">
                                        <div class="contents">
                                            <span class="order-para"> {{ __('Pending Balance') }} </span>
                                            <h2 class="order-titles">
                                                {{ float_amount_with_currency_symbol($pending_balance) }} </h2>
                                        </div>
                                        <div class="icon">
                                            <i class="las la-file-invoice-dollar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                                <div class="single-orders">
                                    <div class="orders-shapes">

                                    </div>
                                    <div class="orders-flex-content">
                                        <div class="contents">
                                            <span class="order-para ff-rubik"> {{ __('Order Completed Balance') }} </span>
                                            <h2 class="order-titles">
                                                {{ float_amount_with_currency_symbol($total_complete_order_amount) }} </h2>
                                        </div>
                                        <div class="icon">
                                            <i class="las la-handshake"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                                <div class="single-orders">
                                    <div class="orders-shapes">
                                    </div>
                                    <div class="orders-flex-content">
                                        <div class="contents">
                                            <span class="order-para ff-rubik"> {{ __('Total Earning') }} </span>
                                            <h2 class="order-titles">
                                                {{ float_amount_with_currency_symbol($total_order_amount) }} </h2>
                                        </div>
                                        <div class="icon">
                                            <i class="las la-dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-1">
            <div class="col-md-7">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__left">
                            <h2 class="dashboard__card__title">
                                {{ __('Yearly Income Statement') }}</h2>
                            <h3 class="dashboard-earning-price mt-2">
                                {{ float_amount_with_currency_symbol(array_sum($yearly_income_statement->toArray())) }}
                            </h3>
                        </div>
                        <span class="seller-title-right chart-icon radius-5"> <i class="las la-chart-bar"></i> </span>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="bar-charts chart-height">
                            <canvas id="bar-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="dashboard__card">
                    <div class="dashboard__card__header d-block text-center">
                        <div class="dashboard__card__header__left">
                            <span class="dashboard__card__title dashboard-week-earning"> {{ __('This Week Earnings') }}
                            </span>
                            <h3 class="dashboard-earning-price mt-2">
                                {{ float_amount_with_currency_symbol(array_sum($weekly_statement->toArray())) }}
                            </h3>
                        </div>

                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="line-charts">
                            <canvas id="line-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-overlay-desktop"></div>
    <x-media.markup />
@endsection
@section('script')
    @php
        $monthName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $weekName = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $weekArray = [0, 0, 0, 0, 0, 0, 0];
        
        foreach ($yearly_income_statement as $month => $value) {
            $monthArray[array_search($month, $monthName, true)] = (float) $value;
        }
        
        foreach ($weekly_statement as $week => $value) {
            $weekArray[array_search($week, $weekName, true)] = (float) $value;
        }
    @endphp
    <script>
        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', "Mar", 'Apr', 'May', "Jun", "July", 'Aug', "Sep", 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: "Statement",
                    backgroundColor: "#e9edf7",
                    data: [
                        @foreach ($monthArray as $value)
                            {{ $value }},
                        @endforeach
                    ],
                    barThickness: 15,
                    hoverBackgroundColor: '#05cd99',
                    hoverBorderColor: '#05cd99',
                    borderRadius: 5,
                    minBarLength: 0,
                    indexAxis: "x",
                    pointStyle: 'star',
                }, ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById("line-chart"), {
            type: 'line',
            data: {
                labels: ['Sun', 'Mon', "Tue", 'Wed', 'Thu', "Fri", "Sat"],
                datasets: [{
                    data: [
                        @foreach ($weekArray as $value)
                            {{ $value }},
                        @endforeach
                    ], //[265, 270, 268, 272, 270, 267, 270],
                    label: "Earnings",
                    borderColor: "#05cd99",
                    borderWidth: 2,
                    fill: true,
                    backgroundColor: 'rgba(5, 205, 153,.08)',
                    fillBackgroundColor: "#f9503e",
                    pointBorderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointRadius: 3,
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "#05cd99",
                }, ]
            },
        });
    </script>
@endsection
