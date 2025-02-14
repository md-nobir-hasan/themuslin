@extends('vendor.vendor-master')
@section('site-title')
    {{__('Vendor Create')}}
@endsection
@section('style')

@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <x-msg.error/>
        <x-msg.flash/>

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h2 class="title">{{ __("Vendor withdraw request page") }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ "" }}">
                        <div class="form-group">
                            <label>
                                {{ __("Select an gateway") }}
                                <select name="gateway_name" class="form-control gateway-name">
                                    <option value="">{{ __("Select an gateway") }}</option>
                                    @foreach($adminGateways as $gateway)
                                        <option  value="{{ $gateway->id }}" data-fileds="{{ json_encode(unserialize($gateway->filed)) }}">{{ $gateway->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="gateway-information-wrapper">

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-info"> {{ __("Update") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="body-overlay-desktop"></div>
    <x-media.markup/>
@endsection
@section("script")
    <script>
        $(document).on("change",".gateway-name", function (){
            let gatewayInformation = "";
            $(".gateway-information-wrapper").fadeOut(150);

            JSON.parse($(this).find(":selected").attr("data-fileds")).forEach(function (value, index){
                let gateway_name = value.toLowerCase().replaceAll(" ","_").replaceAll("-","_");

                gatewayInformation += `
                    <div class="form-group">
                        <label>
                            ${ value }
                            <input type="text" name="${ gateway_name }" class="form-control" placeholder="Write ${ value.toLowerCase() }" />
                        </label>
                    </div>
                `;
            })

            $(".gateway-information-wrapper").html(gatewayInformation);
            $(".gateway-information-wrapper").fadeIn(250);
        })
    </script>
@endsection