@extends('backend.admin-master')

@section('site-title')
    {{__('Check Update')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"> {{__("Check Update")}}</h4>
                        <button type="button" class="btn btn-primary mt-4 pr-4 pl-4" id="click_for_check_update"> <i class="fas fa-spinner fa-spin d-none"></i> {{__('Click to check For Update')}}</button>

                        <div id="update_notice_wrapper" class="d-none text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('script')
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                //todo write code
                $("body").on("click","#update_download_and_run_update",function (e){
                    e.preventDefault();

                    var el = $(this);
                    el.children().removeClass('d-none');

                    if(el.attr("disabled") != undefined && el.attr("disabled") === "disabled"){
                        return;
                    }
                    el.attr("disabled",true);
                    $.ajax({
                        url: el.attr("data-action"),
                        type: "POST",
                        data: {
                            _token : "{{csrf_token()}}",
                            version: el.attr("data-version")
                        },
                        success: function (data){
                            console.log(data);
                            el.children().addClass('d-none');
                            if(data.msg != undefined && data.msg != ""){
                                el.text(data.msg).removeClass("btn-warning").addClass("btn-"+data.type);
                            }
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });

                });


                $(document).on("click","#click_for_check_update",function (e){
                    e.preventDefault();
                    var el = $(this);
                    el.children().removeClass('d-none');
                    el.attr("disabled",true);
                    $.ajax({
                        url: "{{route('admin.general.update.version.check')}}",
                        type: "GET",
                        success: function (data){
                            el.children().addClass('d-none');
                            if(data.markup != ""){
                                $("#update_notice_wrapper").append(data.markup);
                            }else if(data.msg != ""){
                                $("#update_notice_wrapper").append("<div class='alert alert-"+data.type+"'>"+data.msg+"</div>");
                            }
                            $("#update_notice_wrapper").removeClass('d-none');
                            el.hide();
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });
                });

            });
        }(jQuery));
    </script>
@endsection