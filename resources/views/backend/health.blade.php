@extends('backend.admin-master')

@section('site-title', __('Admin Health'))

@section('style')
    <x-media.css/>
    <x-datatable.css/>
@endsection

@section('content')
    @php
        $display_errors =  "ini_get method not allowed";
        $memory_limit =  "ini_get method not allowed";
        $post_max_size =  "ini_get method not allowed";
        $max_execution_time =  "ini_get method not allowed";
        $upload_max_filesize =  "ini_get method not allowed";

        if (function_exists('ini_get')){
            $display_errors =  ini_get("display_errors");
            $memory_limit =  ini_get("memory_limit");
            $post_max_size =  ini_get("post_max_size");
            $max_execution_time =  ini_get("max_execution_time");
            $upload_max_filesize =  ini_get("upload_max_filesize");
        }
    @endphp

    <div class="row">

        <div class="col-sm-6 m-auto text-dark">
            <div class="py-2 px-2 mb-2 d-flex justify-content-between align-items-center bg-white">
                <h3>{{ __("Website health") }}</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    <p class="text-dark font-weight-bold"> {{__('PHP Version')}}
                        <small class="d-block mb-0">{{__('Minimum required PHP Version is v 8.1')}}</small>
                    </p>

                    <span class="badge bg-secondary badge-info badge-pill">
                        @php
                            echo "V"." ".phpversion();
                        @endphp
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    <p class="text-dark font-weight-bold"> {{__('MySQL version')}}
                        <small class="d-block mb-0">{{__('Minimum required MySQL version is v 8')}}</small>
                    </p>

                    <span class="badge bg-secondary badge-info badge-pill">
                        @php
                            echo "V"." ". DB::select("SELECT VERSION() as version")[0]->version;
                        @endphp
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    Laravel version
                    <span class="badge bg-secondary badge-info badge-pill">
                        @php
                            echo "V"." ".app()->version();
                        @endphp
                    </span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    <p class="text-dark font-weight-bold"> {{__('Memory Limit')}} <small class="d-block">{{__('recommended memory limit is 512MB')}}</small></p>
                    <span class="badge bg-secondary badge-success badge-pill">{{$memory_limit}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    <p class="text-dark font-weight-bold"> {{__('Maximum Execution Time')}} <small
                                class="d-block">{{__('recommended maximum execution time is 300')}}</small></p>

                    <span class="badge bg-secondary badge-success badge-pill">{{$max_execution_time}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    {{__('Display Errors')}}
                    <span
                            class="badge bg-secondary @if($display_errors == 'Off') badge-danger @else badge-success @endif badge-pill">{{$display_errors}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    <p class="text-dark font-weight-bold"> {{__('Max File Upload Size')}} <small class="d-block">{{__('recommended post size is 128M')}}</small></p>
                    <span class="badge bg-secondary badge-success badge-pill">{{$upload_max_filesize}}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    <p class="text-dark font-weight-bold"> {{__('Post Max Size')}} <small class="d-block">{{__('recommended post size is 128M')}}</small></p>
                    <span class="badge bg-secondary badge-success badge-pill">{{$post_max_size}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                    {{__('Database engine')}}
                    <span class="badge bg-secondary badge-info badge-pill">{{\Config::get('database.connections.mysql.engine')}}</span>
                </li>

                <li class="list-group-item d-flex  justify-content-start align-items-center flex-wrap text-dark">
                    <p class="d-inline-block mb-3 text-dark font-weight-bold mr-5">{{__('Php Extension list')}}</p>
                    <div class="extension-list">
                        @php
                            $colors = ["badge-success",'badge-primary','badge-secondary','badge-danger','badge-warning'];
                        @endphp
                        @foreach(get_loaded_extensions() ?? [] as $ext)
                            <span class="badge bg-secondary badge-pill m-1 extension">{{$ext}}</span>
                        @endforeach
                    </div>
                </li>
            </ul>
        </div>
    </div>
    {{--end --}}
@endsection

@section('script')
    <!-- Start datatable js -->
    <x-datatable.js/>
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                $(document).on('click','.user_change_password_btn',function(e){
                    e.preventDefault();
                    var el = $(this);
                    var form = $('#user_password_change_modal_form');
                    form.find('#ch_user_id').val(el.data('id'));
                });
                $('#all_user_table').DataTable( {
                    "order": [[ 0, "desc" ]]
                } );

            } );

        })(jQuery);
    </script>
@endsection
