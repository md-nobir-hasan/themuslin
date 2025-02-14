<div class="form-group">
    <label for="site_global_currency">{{__('Site Global Currency')}}</label>
    <select name="site_global_currency" class="form-control"
            id="site_global_currency">
  @foreach(script_currency_list() as $cur => $symbol)
  <option value="{{$cur}}"
  @if(get_static_option('site_global_currency') == $cur) selected @endif>{{$cur.' ( '.$symbol.' )'}}</option>
  @endforeach
    </select>
</div>
<div class="form-group">
    <label for="site_currency_symbol_position">{{__('Currency Symbol Position')}}</label>
    @php $all_currency_position = ['left','right']; @endphp
    <select name="site_currency_symbol_position" class="form-control"
            id="site_currency_symbol_position">
        @foreach($all_currency_position as $cur)
            <option value="{{$cur}}"
                    @if(get_static_option('site_currency_symbol_position') == $cur) selected @endif>{{ucwords($cur)}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="site_default_payment_gateway">{{__('Default Payment Gateway')}}</label>
    <select name="site_default_payment_gateway" class="form-control">
        @php
            $all_gateways = \App\PaymentGateway::select("id","name")->get()->pluck("name");
        @endphp
        @foreach($all_gateways as $gateway)
                <option value="{{$gateway}}" @if(get_static_option('site_default_payment_gateway') == $gateway) selected @endif>{{ucwords(str_replace('_',' ',$gateway))}}</option>
        @endforeach
    </select>
</div>
@php $global_currency = get_static_option('site_global_currency');@endphp


@if($global_currency != 'USD')
    <div class="form-group">
        <label for="site_{{strtolower($global_currency)}}_to_usd_exchange_rate">{{__($global_currency.' to USD Exchange Rate')}}</label>
        <input type="text" class="form-control"
               name="site_{{strtolower($global_currency)}}_to_usd_exchange_rate"
               value="{{get_static_option('site_'.$global_currency.'_to_usd_exchange_rate')}}">
        <span class="info-text">{{sprintf(__('enter %1$s to USD exchange rate. eg: 1 %2$s = ? USD'),$global_currency,$global_currency) }}</span>
    </div>
@endif

@if($global_currency != 'IDR')
    <div class="form-group">
        <label for="site_{{strtolower($global_currency)}}_to_idr_exchange_rate">{{__($global_currency.' to IDR Exchange Rate')}}</label>
        <input type="text" class="form-control"
               name="site_{{strtolower($global_currency)}}_to_idr_exchange_rate"
               value="{{get_static_option('site_'.$global_currency.'_to_idr_exchange_rate')}}">
        <span class="info-text">{{sprintf(__('enter %1$s to USD exchange rate. eg: 1 %2$s = ? IDR'),$global_currency,$global_currency) }}</span>
    </div>
@endif


@if($global_currency != 'INR')
    <div class="form-group">
        <label for="site_{{strtolower($global_currency)}}_to_inr_exchange_rate">{{__($global_currency.' to INR Exchange Rate')}}</label>
        <input type="text" class="form-control"
               name="site_{{strtolower($global_currency)}}_to_inr_exchange_rate"
               value="{{get_static_option('site_'.$global_currency.'_to_inr_exchange_rate')}}">
        <span class="info-text">{{__('enter '.$global_currency.' to INR exchange rate. eg: 1'.$global_currency.' = ? INR')}}</span>
    </div>
@endif

@if($global_currency != 'NGN')
    <div class="form-group">
        <label for="site_{{strtolower($global_currency)}}_to_ngn_exchange_rate">{{__($global_currency.' to NGN Exchange Rate')}}</label>
        <input type="text" class="form-control"
               name="site_{{strtolower($global_currency)}}_to_ngn_exchange_rate"
               value="{{get_static_option('site_'.$global_currency.'_to_ngn_exchange_rate')}}">
        <span class="info-text">{{__('enter '.$global_currency.' to NGN exchange rate. eg: 1'.$global_currency.' = ? NGN')}}</span>
    </div>
@endif

@if($global_currency != 'ZAR')
    <div class="form-group">
        <label for="site_{{strtolower($global_currency)}}_to_zar_exchange_rate">{{__($global_currency.' to ZAR Exchange Rate')}}</label>
        <input type="text" class="form-control"
               name="site_{{strtolower($global_currency)}}_to_zar_exchange_rate"
               value="{{get_static_option('site_'.$global_currency.'_to_zar_exchange_rate')}}">
        <span class="info-text">{{sprintf(__('enter %1$s to USD exchange rate. eg: 1 %2$s = ? ZAR'),$global_currency,$global_currency) }}</span>
    </div>
@endif


@if($global_currency != 'BRL')
    <div class="form-group">
        <label for="site_{{strtolower($global_currency)}}_to_brl_exchange_rate">{{__($global_currency.' to BRL Exchange Rate')}}</label>
        <input type="text" class="form-control"
               name="site_{{strtolower($global_currency)}}_to_brl_exchange_rate"
               value="{{get_static_option('site_'.$global_currency.'_to_brl_exchange_rate')}}">
        <span class="info-text">{{__('enter '.$global_currency.' to BRL exchange rate. eg: 1'.$global_currency.' = ? BRL')}}</span>
    </div>
@endif
