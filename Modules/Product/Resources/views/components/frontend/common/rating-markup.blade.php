@php
    $product = $product ?? null;
    $avg_rattings = $avgRattings ?? null;
    $ratingCount = $ratingCount ?? null;
@endphp

@if(!empty($product))
    @php
        $rating_width = round(($product->reviews_avg_rating ?? 0) * 20);
    @endphp

    <div class="ratings {{ $rating_width == 0 ? "d-none" : "" }}">
        <span class="hide-rating"></span>
        <span class="show-rating" style="width: {{ $rating_width }}%!important"></span>
    </div>
    <p> <span class="total-ratings">{{ $product->reviews_count ? "(". $product->reviews_count .")" : "" }}</span></p>
@elseif(!empty($avg_rattings))
    @php
        $rating_width = round(($avg_rattings) * 20);
    @endphp

    <div class="ratings {{ $rating_width == 0 ? "d-none" : "" }}">
        <span class="hide-rating"></span>
        <span class="show-rating" style="width: {{ $rating_width }}%!important"></span>
    </div>
    @if($ratingCount)
        <p> <span class="total-ratings">{{ $ratingCount ? "(". $ratingCount .")" : "" }}</span></p>
    @endif
@endif