@extends('backend.admin-master')
@section('title')
    {{ __('All Plugins') }}
@endsection

@section('style')
    <style>
        .plugin-grid {
            display: flex;
            flex-wrap: wrap;
            /*justify-content: space-between;*/
            /*padding: 1em;*/
            gap: 1em;  /* space between grid items */
        }

        .plugin-card {
            width: calc((100% - 2em) / 3);  /* for a three column layout */
            box-shadow: 0px 1px 3px 0px rgba(0,0,0,0.2);
            /*padding: 1em;*/
            text-align: center;
        }
        .plugin-card .thumb-bg-color {
            background-color: #009688;
            padding: 40px;
            color: #fff;
        }

        .plugin-card .thumb-bg-color strong {
            font-size: 20px;
            line-height: 26px;
        }

        .plugin-card .thumb-bg-color strong .version {
            font-size: 14px;
            line-height: 18px;
            background-color: #fff;
            padding: 5px 10px;
            display: inline-block;
            color: #333;
            border-radius: 3px;
            margin-top: 15px;
        }

        .plugin-title {
            font-size: 16px;
            font-weight: 500;
            background-color: #03A9F4;
            box-shadow: 0 0 30px 0 rgba(0,0,0,0.2);
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            color: #fff;
            position: relative;
            margin-top: -20px;
        }
        .plugin-title.externalplugin {
            background-color: #3F51B5;
        }
        .plugin-meta {
            font-size: 0.9em;
            color: #666;
            padding: 20px;
        }
        .padding-30{
            padding: 30px;
        }
        .plugin-card .thumb-bg-color.externalplugin {
            background-color: #FF9800;
        }

        .plugin-card .plugin-meta {
            min-height: 50px;
        }
        .plugin-card .btn-group-wrap {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .plugin-card .btn-group-wrap a {
            display: inline-block;
            padding: 8px 25px;
            background-color: #4b4e5b;
            border-radius: 25px;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            transition: all 300ms;
        }

        .plugin-card .btn-group-wrap a.pl_delete {
            background-color: #e13a3a;
        }
        .plugin-card .btn-group-wrap a:hover{
            opacity: .8;
        }
        /* For large screens and above */
        @media (min-width: 900px) {
            .plugin-card {
                width: calc((100% - 3em) / 3);  /* three columns for large screens */
            }
        }

        /* For medium screens and above */
        @media (max-width: 600px) {
            .plugin-card {
                width: calc((100% - 2em) / 2);  /* two columns for medium screens */
            }
            .plugin-card .btn-group-wrap {
                gap: 5px;
            }
            .plugin-card .btn-group-wrap a {
                padding: 7px 15px;
            }
            .plugin-title {
                font-size: 12px;
                line-height: 16px;
            }
        }
        @media (max-width: 500px) {
            .plugin-card {
                width: calc((100% - 2em) / 1);  /* two columns for medium screens */
            }
            .plugin-title {
                font-size: 16px;
                line-height: 20px;
            }
        }



    </style>
@endsection
@section('content')
    <div class="dashboard-recent-order">
        <div class="row">
            <x-flash-msg/>
            <div class="col-md-12">
                <div class="recent-order-wrapper dashboard-table bg-white padding-30">
                    <div class="header-wrap">
                        <h4 class="header-title mb-2">{{__("All Plugins")}}</h4>
                        <p>{{__("Manage all plugins from here, you can active/deactivate plugin or can delete any plugin from here...")}}</p>
                    </div>
                    <div class="plugin-grid">
                    @foreach($pluginList as $plugin)
                        <div class="plugin-card">
                            <div class="thumb-bg-color {{\Illuminate\Support\Str::slug($plugin->category,null,"_")}}">
                                <strong class="{{\Illuminate\Support\Str::slug($plugin->category,null,"_")}}">  {{$plugin->name}}  <p><span class="version">{{$plugin->version}}</span></p></strong>
                            </div>
                            <h3 class="plugin-title {{\Illuminate\Support\Str::slug($plugin->category,null,"_")}}">{{$plugin->category}}</h3>
                            <p class="plugin-meta">
                                @if(!empty($plugin->description))
                                    {{$plugin->description}}
                                @else
                                    {{$plugin->name." ".sprintf(__("is a %s developed by %s to enhance platform features"),$plugin->category,(\Illuminate\Support\Str::slug($plugin->category,null,"_") === "coreplugin" ? __("Core Team") : __("External Developer")))}}
                                @endif
                            </p>
                            @if((\Illuminate\Support\Str::slug($plugin->category,null,"_")) != "coreplugin")
                                <div class="btn-group-wrap">
                                    <a href="#" data-status="{{$plugin->status ? 1 : 0}}" data-plugintype="{{$plugin->category}}" data-plugin="{{$plugin->name}}" class="pl-btn pl_active_deactive">{{$plugin->status ? __("Deactivate") : __("Active") }}</a>
                                    <a href="#" data-plugintype="{{$plugin->category}}" data-plugin="{{$plugin->name}}" class="pl-btn pl_delete">{{__("Delete")}}</a>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function ($){
            "use strict";

            /**
             * handle plugin active deactivate option
             * */
            $(document).on("click",".pl_active_deactive",function(e){
                e.preventDefault();
                var el = $(this);
                let allData = el.data();
                //todo check warning based on plugin type
                var swalDesc =  '{{__("it will disabled the features you are enjoying from")}} ' +allData.plugin + " {{__("plugin!")}}";
                var swalBtnText = allData.status == 1 ? "{{__('Yes, deactivate it!')}}" : "{{__('Yes, active it!')}}";
                var buttonText = allData.status !== 1 ? "{{__('Deactivate')}}" : "{{__('Active')}}";
                if(allData.plugintype === "Core Plugin" ){
                    swalDesc = allData.plugin+" {{__('is a core plugin, after deactivate it you might face issues or error in the website')}}";
                }
                if(allData.status == 0){
                    swalDesc = "{{__("you are activating a new plugin..")}}"
                }
                Swal.fire({
                    title: '{{__("Are you sure to deactivate")}} '+allData.plugin+" {{__("plugin ?")}}",
                    text:swalDesc,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: swalBtnText,
                    cancelButtonText: "{{__('Cancel')}}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        //hide the plugin from the page
                        //todo send ajax request to backend to delete plugin
                        $('.pl_active_deactive[data-plugin="'+allData.plugin+'"]').text(buttonText);
                        $.ajax({
                            url: "{{route('admin.plugin.manage.status.change')}}",
                            type: "post",
                            data: {
                                _token : "{{csrf_token()}}",
                                plugin : allData.plugin,
                                status : allData.status,
                            },
                            success: function (data){
                                location.reload();
                            }
                        })
                    }
                });
            });

            /**
            * handle plugin delete option
            * */
            $(document).on("click",".pl_delete",function(e){
                e.preventDefault();
               var el = $(this);
               let allData = el.data();
                //todo check warning based on plugin type
                if(allData.plugintype === "Core Plugin" ){
                   Swal.fire({
                       icon: 'error',
                       title:"{{__("Oops...")}}",
                       text: "{{__("you can not delete any core plugin")}}",
                       timer: 3000,
                       timerProgressBar: true,
                   })
                   return;
               }
                Swal.fire({
                    title: '{{__("Are you sure to delete")}} '+allData.plugin+" {{__("plugin ?")}}",
                    text: '{{__("You would not be able to restore")}} ' +allData.plugin + " {{__("plugin again!")}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Delete it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        //hide the plugin from the page
                        //todo send ajax request to backend to delete plugin
                        $('.pl_delete[data-plugin="'+allData.plugin+'"]').parent().parent().hide();
                        $.ajax({
                            url: "{{route('admin.plugin.manage.delete')}}",
                            type: "post",
                            data: {
                                _token : "{{csrf_token()}}",
                                plugin : allData.plugin,
                            },
                            success: function (data){
                                location.reload();
                            }
                        })
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
