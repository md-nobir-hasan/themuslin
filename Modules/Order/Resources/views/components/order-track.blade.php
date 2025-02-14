@php
    $disableForm = $disableForm ?? false;
    $orderTracks = \Modules\Order\Services\OrderServices::orderTrackArray();
    $orderTrackIcons = ['', '', '', '', ''];

    $orderTrack = $order->orderTrack->pluck('name')->toArray();
@endphp

<div class="dashboard__card">
    <div class="dashboard__card__header">
        <h4 class="dashboard__card__title">{{ __('Update order track status') }}</h4>
    </div>
    <div class="dashboard__card__body mt-4">
        @if ($disableForm === false)
            <form method="post" action="{{ route('admin.orders.update.order-track') }}" class="">
                @csrf
                @method('PUT')
                <input type="hidden" value="{{ $order->id }}" name="order_id">
        @endif


        <div class="d-flex flex-wrap flex-xl-nowrap gap-3 justify-content-center">
            @foreach ($orderTracks as $track)
                @if (in_array('assigned_delivery_man', $orderTrack) && $track == 'picked_by_courier')
                    <div class="form-group text-center">
                        <label
                            for="{{ 'assigned_delivery_man' }}">{{ ucwords(str_replace(['-', '_'], ' ', 'assigned_delivery_man')) }}</label>
                        @if (!$disableForm)
                            <input {{ 'checked disabled' }} class="order-track-input" id="{{ 'assigned_delivery_man' }}"
                                value="{{ 'assigned_delivery_man' }}" type="checkbox" name="order_track[]" />
                        @endif
                    </div>

                    @continue
                @endif

                <div class="form-group text-center">
                    <label for="{{ $track }}">{{ ucwords(str_replace(['-', '_'], ' ', $track)) }}</label>
                    @if (!$disableForm)
                        <input {{ in_array($track, $orderTrack) ? 'checked disabled' : '' }} class="order-track-input"
                            id="{{ $track }}" value="{{ $track }}" type="checkbox"
                            name="order_track[]" />
                    @endif
                </div>
            @endforeach
        </div>
        <div class="track-wrapper">
            <div class="track">
            @foreach ($orderTracks as $track)
                @if (in_array('assigned_delivery_man', $orderTrack) && $track == 'picked_by_courier')
                    <div class="step active"> <span class="icon"> <i class="las la-check"></i> </span> <small
                            class="text">{{ ucwords(str_replace(['-', '_'], ' ', 'assigned_delivery_man')) }}</small>
                    </div>
                    @continue
                @endif

                <div class="step {{ in_array($track, $orderTrack) ? 'active' : '' }}"> <span class="icon"> <i
                            class="las la-check"></i> </span> <small
                        class="text">{{ ucwords(str_replace(['-', '_'], ' ', $track)) }}</small> </div>
            @endforeach
        </div>
        </div>
        @if ($disableForm === false)
            <div class="form-group">
                <button {{ $orderTracks == $orderTrack ? 'disabled' : '' }}
                    class="cmn_btn btn_bg_profile ">{{ __('Update') }}</button>
            </div>
            </form>
        @endif
    </div>
</div>
