@extends('frontend.frontend-page-master')
@section('page-title')
    {{ $campaign->title }}
@endsection
@section('style')
@endsection
@section('content')

    @php
        $item_style = request()->s ?? 'grid';
    @endphp
    <div class="shop-grid-area-wrapper shop-campaing">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="toolbox-wrapper">
                                <div class="toolbox-left">
                                    <div class="toolbox-item">
                                        @php
                                            $pagination_summary = getPaginationSummaryText($campaign_products);
                                        @endphp
                                        <p class="showing">{{ __('Showing') }}
                                            {{ $pagination_summary['start'] }}–{{ $pagination_summary['end'] }}
                                            {{ __('of') }} {{ $pagination_summary['total'] }} {{ __('results') }}</p>
                                    </div>
                                </div>
                                <div class="toolbox-right">
                                    <div class="toolbox-item toolbox-sort">
                                        <select id="item-shows" class="select-box">
                                            <option value="default" @if (isset($sort_by) && $sort_by == 'default') selected @endif>
                                                {{ __('Default sorting') }}</option>
                                            <option value="popularity" @if (isset($sort_by) && $sort_by == 'popularity') selected @endif>
                                                {{ __('Sort by popularity') }}</option>
                                            <option value="rating" @if (isset($sort_by) && $sort_by == 'rating') selected @endif>
                                                {{ __('Sort by rating') }}</option>
                                            <option value="latest" @if (isset($sort_by) && $sort_by == 'latest') selected @endif>
                                                {{ __('Sort by latest') }}</option>
                                            <option value="price_low" @if (isset($sort_by) && $sort_by == 'price_low') selected @endif>
                                                {{ __('Sort by price: low to high') }}</option>
                                            <option value="price_high" @if (isset($sort_by) && $sort_by == 'price_high') selected @endif>
                                                {{ __('Sort by price: high to low') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if (isset($campaign_products) && $campaign_products->count())
                            @foreach ($campaign_products as $campaign_product)
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    @php $end_date = $campaign_product->end_date ?? $campaign->end_date ?? ''; @endphp
                                    <x-frontend.product.product-card-03 :product="$campaign_product->product" :isCampaign="true"
                                        :campaignId="$campaign->id" :campaignProductEndDate="$end_date" />
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12">
                                <div class="text-center">
                                    <h1>{{ __('No products to show') }}</h1>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pagination mt-4">
                                {!! $campaign_products->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            $(document).ready(function() {
                loopcounter('flash-countdown');
            });
        })(jQuery)
    </script>
@endsection
