@extends('backend.admin-master')
@section('site-title')
    {{__('Shipping Zones')}}
@endsection
@section('site-title')
    {{__('Shipping Zones')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                   <x-flash-msg/>
                    <x-error-msg/>
                </div>
            </div>

            <div class="col-md-12">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/frontend/js/jquery.nice-select.js')}}"></script>
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('admin.shipping.zone.bulk.action')" />

    <script>
    $(document).ready(function () {

        // if ($('.nice-select').length) {
        //     $('.nice-select').niceSelect();
        // }

        //todo: load state data based on selected country

        $('#country').on('change', function () {
            let id = $(this).val();
            $.post('{{ route("admin.state.by.multiple.country") }}', {id:id,_token: "{{csrf_token()}}"}).then(function (data) {
                $('#state').html('');
                for (const state of data) {
                    $('#state').append('<option value="'+state.id+'">'+state.name+'</option>');
                }
                $('.nice-select').niceSelect("update");
            });

        });

        $(document).on('click','.shipping_zone_edit_btn',function(){
            let el = $(this);
            let id = el.data('id');
            let name = el.data('name');
            let country = el.data('country');
            let selectedState = el.data('state');
            let modal = $('#shipping_zone_edit_modal');

            modal.find('#shipping_zone_id').val(id);
            modal.find('#edit_name').val(name);
            modal.find('#edit_country').val(country)

            $('#edit_state_select').html('');
            $.post('{{ route("admin.state.by.multiple.country") }}', {id:country,_token: "{{csrf_token()}}"}).then(function (data) {
                for (const state of data) {
                    let selected =  selectedState != null && selectedState.includes(state.id.toString()) ? 'selected' : '';
                    $('#edit_state_select').append('<option '+selected+' value="'+state.id+'">'+state.name+'</option>');
                }
                $('.nice-select').niceSelect("update");
            });
        });
    });
    </script>
@endsection
