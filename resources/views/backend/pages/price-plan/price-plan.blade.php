@extends('backend.admin-master')
@section('site-title')
    {{__('Price Plan')}}
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            padding: 0 !important;
        }
        div.dataTables_wrapper div.dataTables_length select {
            width: 60px;
            display: inline-block;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <!-- basic form start -->
            <div class="col-lg-12">
                @include('backend/partials/message')
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{__('Price Plan Items')}}</h4>
                        <div class="bulk-delete-wrapper">
                            <div class="select-box-wrap">
                                <select name="bulk_option" id="bulk_option">
                                    <option value="">{{{__('Bulk Action')}}}</option>
                                    <option value="delete">{{{__('Delete')}}}</option>
                                </select>
                                <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{__('Apply')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @php $a=0; @endphp
                            @foreach($all_price_plan as $key => $plan)
                                <li class="nav-$all_price_plan">
                                    <a class="nav-link @if($a == 0) active @endif"  data-bs-toggle="tab" href="#slider_tab_{{$key}}" role="tab" aria-controls="home" aria-selected="true">{{get_language_by_slug($key)}}</a>
                                </li>
                                @php $a++; @endphp
                            @endforeach
                        </ul>
                        <div class="tab-content margin-top-40" id="myTabContent">
                            @php $b=0; @endphp
                            @foreach($all_price_plan as $key => $plan)
                                <div class="tab-pane fade @if($b == 0) show active @endif" id="slider_tab_{{$key}}" role="tabpanel" >
                                    <div class="table-wrap table-responsive">
                                        <table class="table table-default">
                                        <thead>
                                        <th class="no-sort">
                                            <div class="mark-all-checkbox">
                                                <input type="checkbox" class="all-checkbox">
                                            </div>
                                        </th>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Title')}}</th>
                                        <th>{{__('Price')}}</th>
                                        <th>{{__('Category')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Type')}}</th>
                                        <th>{{__('Action')}}</th>
                                        </thead>
                                        <tbody>
                                        @foreach($plan as $data)
                                            @php $img_url =''; @endphp
                                            <tr>
                                                <td>
                                                    <div class="bulk-checkbox-wrapper">
                                                        <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                                    </div>
                                                </td>
                                                <td>{{$data->id}}</td>
                                                <td>
                                                    {{$data->title}}
                                                </td>
                                                <td>{{amount_with_currency_symbol($data->price)}}</td>
                                                <td>
                                                   {{get_price_plan_category_by_id($data->categories_id)}}
                                                </td>
                                                <td>
                                                    @if($data->status == 'draft')
                                                        <span class="alert alert-warning" style="margin-top: 20px;display: inline-block;">{{__('Draft')}}</span>
                                                    @else
                                                        <span class="alert alert-success" style="margin-top: 20px;display: inline-block;">{{__('Publish')}}</span>
                                                    @endif
                                                </td>
                                                <td>{{$data->type}}</td>
                                                <td>
                                                    <a tabindex="0" class="btn btn-danger btn-xs mb-2 me-1"
                                                       role="button"
                                                       data-bs-toggle="popover"
                                                       data-trigger="focus"
                                                       data-html="true"
                                                       title=""
                                                       data-content="
                                               <h6>{{__('Are you sure to delete this price plan item?')}}</h6>
                                               <form method='post' action='{{route('admin.price.plan.delete',$data->id)}}'>
                                               <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                               <br>
                                                <input type='submit' class='btn btn-danger btn-sm' value='{{__('Yes, Please')}}'>
                                                </form>
                                                ">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                    <a href="#1"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#price_plan_item_edit_modal"
                                                       class="btn btn-lg btn-primary btn-xs mb-2 me-1 price_plan_edit_btn"
                                                       data-id="{{$data->id}}"
                                                       data-action="{{route('admin.price.plan.update')}}"
                                                       data-title="{{$data->title}}"
                                                       data-icon="{{$data->icon}}"
                                                       data-type="{{$data->type}}"
                                                       data-price="{{$data->price}}"
                                                       data-lang="{{$data->lang}}"
                                                       data-features="{{$data->features}}"
                                                       data-btnText="{{$data->btn_text}}"
                                                       data-btnUrl="{{$data->btn_url}}"
                                                       data-urlStatus="{{$data->url_status}}"
                                                       data-highlight="{{$data->highlight}}"
                                                       data-status="{{$data->status}}"
                                                       data-category="{{$data->categories_id}}"
                                                    >
                                                        <i class="ti-pencil"></i>
                                                    </a>
                                                    <form action="{{route('admin.price.plan.clone')}}" method="post" style="display: inline-block">
                                                        @csrf
                                                        <input type="hidden" name="item_id" value="{{$data->id}}">
                                                        <button type="submit" title="clone this to new draft" class="btn btn-xs btn-secondary btn-sm mb-2 me-1"><i class="far fa-copy"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                @php $b++; @endphp
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="price_plan_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom__form">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Price Plan Item')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="#" id="price_plan_edit_modal_form"  method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="price_plan_id" value="">
                        <div class="form-group">
                            <label for="edit_language">{{__('Languages')}}</label>
                            <select name="lang" id="edit_language" class="form-control">
                                @foreach($all_languages as $lang)
                                    <option value="{{$lang->slug}}">{{$lang->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_title">{{__('Title')}}</label>
                            <input type="text" class="form-control"  id="edit_title"  name="title" placeholder="{{__('Title')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_price">{{__('Price')}}</label>
                            <input type="text" class="form-control"  id="edit_price"  name="price" placeholder="{{__('Price')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_type">{{__('Type')}}</label>
                            <input type="text" class="form-control"  id="edit_type"  name="type" placeholder="{{__('Type')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_features">{{__('Features')}}</label>
                            <textarea class="form-control"  id="edit_features"  name="features" placeholder="{{__('Features')}}" cols="30" rows="10"></textarea>
                            <small class="info=text">{{__('Separate feature by new line')}}</small>
                        </div>
                        <div class="form-group">
                            <label for="edit_btn_text">{{__('Button Text')}}</label>
                            <input type="text" class="form-control"  id="edit_btn_text"  name="btn_text" placeholder="{{__('Button Text')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_highlinght"><strong>{{__('Highlight')}}</strong></label>
                            <label class="switch">
                                <input type="checkbox" name="highlight" id="edit_highlight">
                                <span class="slider onff"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="edit_url_status"><strong>{{__('Plan Details Page')}}</strong></label>
                            <label class="switch">
                                <input type="checkbox" name="url_status" id="edit_url_status">
                                <span class="slider onff"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="edit_btn_url">{{__('Button URL')}}</label>
                            <input type="text" class="form-control"  id="edit_btn_url"  name="btn_url" placeholder="{{__('Button URL')}}">
                        </div>
                        <div class="form-group">
                            <label for="categories_id">{{__('Category')}}</label>
                            <select name="categories_id" class="form-control" id="category">
                                @foreach($all_category as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">{{__('Status')}}</label>
                            <select name="status" class="form-control" id="edit_status">
                                <option value="publish">{{__('Publish')}}</option>
                                <option value="draft">{{__('Draft')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        $(document).ready(function () {

            $(document).on('click','#bulk_delete_btn',function (e) {
                e.preventDefault();

                var bulkOption = $('#bulk_option').val();
                var allCheckbox =  $('.bulk-checkbox:checked');
                var allIds = [];
                allCheckbox.each(function(index,value){
                    allIds.push($(this).val());
                });
                if(allIds != '' && bulkOption == 'delete'){
                    $(this).text('{{__('Deleting...')}}');
                    $.ajax({
                        'type' : "POST",
                        'url' : "{{route('admin.price.plan.bulk.action')}}",
                        'data' : {
                            _token: "{{csrf_token()}}",
                            ids: allIds
                        },
                        success:function (data) {
                            location.reload();
                        }
                    });
                }

            });

            $('.all-checkbox').on('change',function (e) {
                e.preventDefault();
                var value = $('.all-checkbox').is(':checked');
                var allChek = $(this).parent().parent().parent().parent().parent().find('.bulk-checkbox');
                //have write code here fr
                if( value == true){
                    allChek.prop('checked',true);
                }else{
                    allChek.prop('checked',false);
                }
            });

            $(document).on('click','.price_plan_edit_btn',function(){
                var el = $(this);
                var id = el.data('id');
                var title = el.data('title');
                var action = el.data('action');
                var form = $('#price_plan_edit_modal_form');
                form.attr('action',action);
                form.find('#price_plan_id').val(id);
                form.find('#edit_title').val(title);
                form.find('#edit_price').val(el.data('price'));
                form.find('#edit_icon').val(el.data('icon'));
                form.find('#edit_type').val(el.data('type'));
                form.find('#edit_btn_text').val(el.data('btntext'));
                form.find('#edit_btn_url').val(el.data('btnurl'));
                form.find('#edit_features').val(el.data('features'));
                form.find('.icp-dd').attr('data-selected',el.data('icon'));
                form.find('.iconpicker-component i').attr('class',el.data('icon'));
                form.find('#edit_language option[value='+el.data("lang")+']').attr('selected',true);
                form.find('#edit_status option[value='+el.data("status")+']').attr('selected',true);
                form.find('#category option[value='+el.data("category")+']').attr('selected',true);
                if(el.data('urlstatus') != ''){
                    form.find('#edit_url_status').attr('checked',true);
                    form.find('#edit_url_status').parent().parent().next().hide();
                }
                if(el.data('highlight') != ''){
                    form.find('#edit_highlight').attr('checked',true);
                }
                $.ajax({
                    url : "{{route('admin.price.plan.lang.cat')}}",
                    type: "POST",
                    data: {
                        _token : "{{csrf_token()}}",
                        lang: el.data('lang')
                    },
                    success:function (data) {
                        $('#category').html('');
                        $.each(data,function (index,value) {
                            var selected = value.id == el.data('category') ? 'selected' : '';
                            $('#category').append('<option '+selected+' value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            });
            $('.icp-dd').iconpicker();
            $('.icp-dd').on('iconpickerSelected', function (e) {
                var selectedIcon = e.iconpickerValue;
                $(this).parent().parent().children('input').val(selectedIcon);
            });

            $(document).on('change','input[name="url_status"]',function (e) {
                e.preventDefault();
                if($('input[name="url_status"]').is(":checked")){
                    $(this).parent().parent().next().hide();
                }else{
                    $(this).parent().parent().next().show();
                }
            });
            $(document).on('change','#edit_language',function(e){
                e.preventDefault();
                var selectedLang = $(this).val();
                $.ajax({
                    url: "{{route('admin.price.plan.lang.cat')}}",
                    type: "POST",
                    data: {
                        _token : "{{csrf_token()}}",
                        lang : selectedLang
                    },
                    success:function (data) {
                        $('#category').html('<option value="">Select Category</option>');
                        $.each(data,function(index,value){
                            $('#category').append('<option value="'+value.id+'">'+value.name+'</option>')
                        });
                    }
                });
            });
        });
    </script>
    <!-- Start datatable js -->
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="//cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.table-wrap > table').DataTable( {
                "order": [[ 1, "desc" ]],
                'columnDefs' : [{
                    'targets' : 'no-sort',
                    'orderable' : false
                }]
            } );


        } );
    </script>
@endsection
