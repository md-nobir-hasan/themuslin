<script>
    (function($){
        "use strict";

        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} });

        $(document).ready(function(){

            let edit_modal_city_state_id;

            $('.select2-country, .select2-state').select2({
                dropdownParent: $('#addModal')
            });

            $('.select22-country, .select22-state').select2({
                dropdownParent: $('#editCityModal')
            });

            //todo: add country
            $(document).on('click','.add_city',function(e){
                let city = $('#city').val();
                let state = $('#state').val();
                let country = $('#country').val();
                if(city == '' || state == '' || country == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }

            });

            //  show city in edit modal
            $(document).on('click','.edit_city_modal',function(){
                let city = $(this).data('city');
                let city_id = $(this).data('city_id');
                let state_id = $(this).data('state_id');
                let country_id = $(this).data('country_id');

                edit_modal_city_state_id = state_id;

                $('#city_name').val(city).trigger("change");
                $('#city_id').val(city_id).trigger("change");
                $('#state_id').val(state_id).trigger("change");
                $('#country_id').val(country_id).trigger("change");
            });

            //todo: update city
            $(document).on('click','.edit_city',function(e){
                let city = $('#city_name').val();
                let state = $('#state_id').val();
                let country = $('#country_id').val();

                if(city == '' || state == '' || country == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }
            });

            //  change country and get state
            $('#country_id').on('change', function() {
                let country = $(this).val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.state.all') }}",
                    data: {
                        country: country
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select State')}}</option>";
                            let all_state = res.states;

                            $.each(all_state, function(index, value) {
                                let isSelected =  edit_modal_city_state_id == value.id ? "selected" : "";
                                all_options += "<option "+ isSelected +" value='" + value.id +
                                    "'>" + value.name + "</option>";
                            });

                            $(".get_country_state").html(all_options);
                            $(".info_msg").html('');
                            if(all_state.length <= 0){
                                $(".info_msg").html('<span class="text-danger"> {{ __('No state found for selected country!') }} <span>');
                            }
                        }
                    }
                })
            })

            //todo: change country and get state
            $('#country').on('change', function() {
                let country = $(this).val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.state.all') }}",
                    data: {
                        country: country
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select State')}}</option>";
                            let all_state = res.states;
                            $.each(all_state, function(index, value) {
                                all_options += "<option value='" + value.id +
                                    "'>" + value.name + "</option>";
                            });

                            $(".get_country_state").html(all_options);
                            if(all_state.length <= 0){
                                $(".info_msg").html('<span class="text-danger"> {{ __('No state found for selected country!') }} <span>');
                            }
                        }
                    }
                })
            })

            //todo: pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });

            function countries(page){
                $.ajax({
                    url:"{{ route('admin.city.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            //todo: search state
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.city.search') }}",
                    method:'GET',
                    data:{string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            })

        });
    }(jQuery));

    //  toastr warning
    function toastr_warning_js(msg){
        Command: toastr["warning"](msg, "Warning !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }
</script>
