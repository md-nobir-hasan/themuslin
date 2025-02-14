@extends('backend.admin-master')
@section('style')
    <x-media.css/>
    <x-datatable.css/>
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
@endsection
@section('site-title')
    {{__('All Users')}}
@endsection
@section('content')
    <div class="col-12">
        <x-msg.error/>
        <x-msg.success/>
        <div class="dashboard__card">
            <div class="dashboard__card__header">
                <h4 class="dashboard__card__title">{{__('All Users')}}</h4>
                <div class="dashboard__card__header__right">
                    @can('user-delete')
                    <div class="bulk-delete-wrapper">
                        <div class="select-box-wrap">
                            <select name="bulk_option" id="bulk_option">
                                <option value="">{{{__('Bulk Action')}}}</option>
                                <option value="delete">{{{__('Delete')}}}</option>
                            </select>
                            <button class="cmn_btn btn_bg_profile" id="bulk_delete_btn">{{__('Apply')}}</button>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
            <div class="dashboard__card__body mt-4">                                    
                <div class="data-tables datatable-primary">
                    <table id="all_user_table" class="text-center">
                        <thead class="text-capitalize">
                        <tr>
                            <th class="no-sort">
                                <div class="mark-all-checkbox">
                                    <input type="checkbox" class="all-checkbox">
                                </div>
                            </th>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Email')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($all_user as $data)
                            <tr>
                                <td>
                                    <div class="bulk-checkbox-wrapper">
                                        <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                    </div>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>{{$data->name}} ({{$data->username}})</td>
                                <td>{{$data->email}} @if($data->email_verified == 1) <i class="las la-check-circle text-success"></i> @endif</td>

                                <td>
                                    @can('user-delete')
                                    <x-delete-popover :url="route('admin.frontend.delete.user',$data->id)"/>
                                    @endcan
                                    @can('user-edit')
                                    <a href="#1"
                                        data-id="{{$data->id}}"
                                        data-username="{{$data->username}}"
                                        data-name="{{$data->name}}"
                                        data-email="{{$data->email}}"
                                        data-phone="{{$data->phone}}"
                                        data-address="{{$data->address}}"
                                        data-state="{{$data->state}}"
                                        data-city="{{$data->city}}"
                                        data-zipcode="{{$data->zipcode}}"
                                        data-country="{{$data->country}}"
                                        data-email_verified="{{$data->email_verified}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#user_edit_modal"
                                        class="btn btn-primary btn-sm mb-2 me-1 user_edit_btn"
                                    >
                                        <i class="ti-pencil"></i>
                                    </a>
                                    <a href="#1"
                                        data-id="{{$data->id}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#user_change_password_modal"
                                        class="btn btn-info btn-sm mb-2 me-1 user_change_password_btn"
                                    >
                                        {{__("Change Password")}}
                                    </a>
                                    <form action="{{route('admin.all.frontend.user.email.status')}}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{$data->id}}" name="user_id">
                                        <input type="hidden" value="{{$data->email_verified}}" name="email_verified">
                                        <button type="submit" class="btn btn-sm @if($data->email_verified == 1)  btn-dark @else btn-warning @endif">
                                            @if($data->email_verified == 1) {{__('Enable Email Verify')}} @else {{__('Disable Email Verify')}} @endif
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom__form">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('User Details Edit')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.frontend.user.update')}}" id="user_edit_modal_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" class="form-control"  id="name" name="name" placeholder="{{__('Enter name')}}">
                        </div>
                        <div class="form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                        </div>
                        <div class="form-group">
                            <label for="phone">{{__('Phone')}}</label>
                            <input type="text" class="form-control"  id="phone" name="phone" placeholder="{{__('Phone')}}">
                        </div>
                        <div class="form-group">
                            <label for="country">{{__('Country')}}</label>
                            {!! get_country_field('country','country','form-control') !!}
                        </div>
                        <div class="form-group">
                            <label for="state">{{__('State')}}</label>
                            <input type="text" class="form-control"  id="state" name="state" placeholder="{{__('State')}}">
                        </div>
                        <div class="form-group">
                            <label for="city">{{__('City')}}</label>
                            <input type="text" class="form-control"  id="city" name="city" placeholder="{{__('City')}}">
                        </div>
                        <div class="form-group">
                            <label for="zipcode">{{__('Zipcode')}}</label>
                            <input type="text" class="form-control"  id="zipcode" name="zipcode" placeholder="{{__('Zipcode')}}">
                        </div>
                        <div class="form-group">
                            <label for="address">{{__('Address')}}</label>
                            <input type="text" class="form-control"  id="address" name="address" placeholder="{{__('Address')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button id="update" type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_change_password_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Change Admin Password')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('admin.frontend.user.password.change')}}" id="user_password_change_modal_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ch_user_id" id="ch_user_id">
                        <div class="form-group">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" class="form-control" name="password" placeholder="{{__('Enter Password')}}">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">{{__('Confirm Password')}}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button id="update" type="submit" class="btn btn-primary">{{__('Change Password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  <x-media.markup/>
@endsection

@section('script')
    <x-datatable.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                <x-btn.submit/>
                <x-btn.update/>
                $(document).on('click','#bulk_delete_btn',function (e) {
                    e.preventDefault();

                    var bulkOption = $('#bulk_option').val();
                    var allCheckbox =  $('.bulk-checkbox:checked');
                    var allIds = [];
                    allCheckbox.each(function(index,value){
                        allIds.push($(this).val());
                    });
                    if(allIds != '' && bulkOption == 'delete'){
                        $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i>{{__("Deleting")}}');
                        $.ajax({
                            'type' : "POST",
                            'url' : "{{route('admin.all.frontend.user.bulk.action')}}",
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

                $(document).on('click','.user_change_password_btn',function(e){
                    e.preventDefault();
                    var el = $(this);
                    var form = $('#user_password_change_modal_form');
                    form.find('#ch_user_id').val(el.data('id'));
                });
                $('#all_user_table').DataTable( {
                    "order": [[ 1, "desc" ]],
                    'columnDefs' : [{
                        'targets' : 'no-sort',
                        'orderable' : false
                    }]
                } );

                $(document).on('click','.user_edit_btn',function(e){
                    e.preventDefault();
                    var form = $('#user_edit_modal_form');
                    var el = $(this);

                    form.find('#user_id').val(el.data('id'));
                    form.find('#name').val(el.data('name'));
                    form.find('#username').val(el.data('username'));
                    form.find('#email').val(el.data('email'));
                    form.find('#phone').val(el.data('phone'));
                    form.find('#state').val(el.data('state'));
                    form.find('#city').val(el.data('city'));
                    form.find('#zipcode').val(el.data('zipcode'));
                    form.find('#address').val(el.data('address'));
                    form.find('#country option[value="'+el.data('country')+'"]').attr('selected',true);
                });
            } );
        })(jQuery)
    </script>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <x-media.js/>
@endsection
