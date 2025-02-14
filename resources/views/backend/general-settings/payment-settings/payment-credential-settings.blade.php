<div class="accordion-wrapper">
    <div id="accordion-payment">
        @foreach($all_gateway as $gateway)
            <div class="card">
                <div class="card-header" id="{{$loop->index}}_settings">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#settings_content_{{$loop->index}}" aria-expanded="false" >
                            <span class="page-title"> {{ str_replace("_"," ",$gateway->name) }}</span>
                        </button>
                    </h5>
                </div>
                <div id="settings_content_{{$loop->index}}" class="collapse"  data-parent="#accordion-payment">
                    <div class="card-body">
                        @if(!empty($gateway->description))
                            <div class="payment-notice alert alert-warning">
                                <p>{{$gateway->description}}</p>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="instamojo_gateway"><strong>{{__('Enable/Disable') }} {{ ucfirst($gateway->name)}}</strong></label>
                            <input type="hidden" name="{{$gateway->name}}_gateway">
                            <label class="switch">
                                <input type="checkbox" name="{{$gateway->name}}_gateway"  @if($gateway->status === 1 ) checked @endif >
                                <span class="slider onff"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="instamojo_test_mode"><strong>{{sprintf(__('Enable Test Mode %s'),ucfirst($gateway->name))}}</strong></label>

                            <label class="switch">
                                <input type="checkbox" name="{{$gateway->name}}_test_mode" @if($gateway->test_mode === 1) checked @endif>
                                <span class="slider onff"></span>
                            </label>
                        </div>

                        <x-media-upload required="true" title="{{ sprintf(__('%s Logo'),ucfirst($gateway->name)) }}" name="{{$gateway->name.'_logo'}}" :oldimage="$gateway->oldImage"/>

                        @php
                            $credentials = !empty($gateway->credentials) ? json_decode($gateway->credentials) : [];
                        @endphp

                        @foreach($credentials as $cre_name =>  $cre_value)
                            <div class="form-group">
                                <label >{{ str_replace('_', ' ' , ucwords($cre_name)) }}</label>
                                <input type="text" name="{{$gateway->name.'_'.$cre_name}}" value="{{$cre_value}}" class="form-control">
                                @if($gateway->name == 'paytabs')
                                    @if($cre_name == 'region')
                                        <small class="text-secondary" style="font-size: 13px">GLOBAL, ARE, EGY, SAU, OMN, JOR</small>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        <div class="card d-none">
                <div class="card-header" id="cash_on_delivery_settings">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#pcash_on_delivery_settings_content"
                                aria-expanded="false">
                            <span class="page-title"> {{__('Cash On Delivery Settings')}}</span>
                        </button>
                    </h5>
                </div>
                <div id="pcash_on_delivery_settings_content" class="collapse" data-parent="#accordion-payment">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cash_on_delivery_gateway"><strong>{{__('Enable Cash On Delivery')}}</strong></label>
                            <label class="switch">
                                <input type="checkbox" name="cash_on_delivery_gateway"
                                       @if(!empty(get_static_option('cash_on_delivery_gateway'))) checked
                                       @endif id="cash_on_delivery_gateway">
                                <span class="slider onff"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <x-media-upload :title="__('Cash On Delivery Logo')" :oldimage="get_static_option('cash_on_delivery_preview_logo')" name="cash_on_delivery_preview_logo" />
                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
