<div class="thumb-top-contents right-side">
    @if($campaign_percentage > 0)
        <span class="percent-box bg-color-two radius-5"> -{{ round($campaign_percentage,0) }}% </span>
    @endif
    @if(!empty($product?->badge))
        <span class="percent-box bg-color-stock radius-5"> {{$product?->badge?->name}} </span>
    @endif
</div>