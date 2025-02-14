@extends('backend.admin-master')
@section('site-title')
    {{ __('Dashboard') }}
@endsection
@section('content')
    @php
        $statistics = [['title' => __('Total Admin'), 'value' => $total_admin, 'icon' => 'lar la-user'], ['title' => __('Total Customer'), 'value' => $total_user, 'icon' => 'lar la-user'], ['title' => __('Total Blog'), 'value' => $all_blogs_count, 'icon' => 'lar la-edit'], ['title' => __('Total Products'), 'value' => $all_products_count, 'icon' => 'las la-box'], ['title' => __('Completed Sale'), 'value' => $all_completed_sell_count, 'icon' => 'las la-boxes'], ['title' => __('Pending Sale'), 'value' => $all_pending_sell_count, 'icon' => 'las la-history'], ['title' => __('Sold Amount'), 'value' => $total_sold_amount, 'icon' => 'las la-coins'], ['title' => __('Ongoing Campaign'), 'value' => $total_ongoing_campaign, 'icon' => 'las la-gifts']];
    @endphp

    <div class="dashboard-profile-inner">
        <div class="row g-4 justify-content-center">
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Total Admin') }} </span>
                            <h2 class="order-titles"> {{ $total_admin }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-tasks"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">

                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Total Users') }} </span>
                            <h2 class="order-titles"> {{ $total_user }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-handshake"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Total Blogs') }} </span>
                            <h2 class="order-titles"> {{ $all_blogs_count }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Total Product') }} </span>
                            <h2 class="order-titles"> {{ $all_products_count }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Total Completed Order') }} </span>
                            <h2 class="order-titles"> {{ $all_completed_sell_count }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Total Pending Order') }} </span>
                            <h2 class="order-titles"> {{ $all_pending_sell_count }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Total Sold Amount') }} </span>
                            <h2 class="order-titles"> {{ $total_sold_amount }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Last week earning') }} </span>
                            <h2 class="order-titles"> {{ $last_week_earning }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('This month earning') }} </span>
                            <h2 class="order-titles"> {{ $running_month_earning }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('Last month earning') }} </span>
                            <h2 class="order-titles"> {{ $last_month_earning }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-sm-6 orders-child">
                <div class="single-orders">
                    <div class="orders-shapes">
                    </div>
                    <div class="orders-flex-content">
                        <div class="contents">
                            <span class="order-para"> {{ __('This year earning') }} </span>
                            <h2 class="order-titles"> {{ $this_year_earning }} </h2>
                        </div>
                        <div class="icon">
                            <i class="las la-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-inner-contents-wrapper">
        <div class="dashboard-flex-item-two">
            <div class="single-flex-dashbaord mt-4">
                <div class="dashboard-single-profile dashboard-profile-padding bg-white radius-10">
                    <div class="profile-single-contents">
                        <div class="seller-title-flex-contents">
                            <h2 class="dashboard-common-title"> {{ __('Yearly Income Statement') }} </h2>
                            <span class="seller-title-right chart-icon radius-5"> <i class="las la-chart-bar"></i> </span>
                        </div>
                        <h3 class="dashboard-earning-price mt-3">
                            {{ float_amount_with_currency_symbol(array_sum($yearly_income_statement->toArray())) }} </h3>
                    </div>
                    <div class="bar-charts chart-height mt-4">
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="single-flex-dashbaord">
                <div class="dashboard-flex-row-profile">
                    <div class="single-flex-dashbaord-two dashboard-profile-padding center-text bg-white radius-10 mt-4">
                        <div class="dashboard-single-profile">
                            <div class="profile-single-contents">
                                <span class="dashboard-week-earning"> {{ __('This Week Earnings') }} </span>
                                <h3 class="dashboard-earning-price mt-3">
                                    {{ float_amount_with_currency_symbol(array_sum($weekly_statement->toArray())) }} </h3>
                            </div>
                            <div class="line-charts mt-3">
                                <canvas id="line-chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="single-flex-dashbaord-two dashboard-profile-padding center-text bg-white radius-10 mt-4">
                        <div class="dashboard-single-profile">
                            <div id="custom-color-calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/backend/js/chart.js') }}"></script>

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
