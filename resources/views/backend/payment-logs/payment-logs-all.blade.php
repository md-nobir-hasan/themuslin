@extends('backend.admin-master')
@section('style')
    @include('backend.partials.datatable.style-enqueue')
@endsection
@section('site-title')
    {{__('All Payment Logs')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
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
                        <h4 class="dashboard__card__title">{{__('All Payment Logs')}}</h4>
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
                        <div class="data-tables datatable-primary table-responsive table-wrap">
                            <table id="all_user_table" >
                                <thead class="text-capitalize">
                                <tr>
                                    <th class="no-sort">
                                        <div class="mark-all-checkbox">
                                            <input type="checkbox" class="all-checkbox">
                                        </div>
                                    </th>
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Payer Name')}}</th>
                                    <th>{{__('Payer Email')}}</th>
                                    <th>{{__('Package Name')}}</th>
                                    <th>{{__('Package Price')}}</th>
                                    <th>{{__('Package Gateway')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payment_logs as $data)
                                    <tr>
                                        <td>
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                            </div>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->email}}</td>
                                        <td>{{$data->package_name}}</td>
                                        <td>{{amount_with_currency_symbol($data->package_price)}}</td>
                                        <td><strong>{{ucwords(str_replace('_',' ',$data->package_gateway))}}</strong></td>
                                        <td>
                                            @if($data->status == 'pending')
                                                <span class="alert alert-warning text-capitalize">{{$data->status}}</span>
                                            @else
                                                <span class="alert alert-success text-capitalize">{{$data->status}}</span>
                                            @endif
                                        </td>
                                        <td>{{date_format($data->created_at,'d M Y')}}</td>
                                        <td>

                                            <x-delete-popover :url="route('admin.payment.delete',$data->id)"/>
                                            <a href="{{route('admin.payment.view',$data->id)}}"
                                            class="btn btn-lg btn-primary btn-sm mb-2 me-1"
                                            >
                                                <i class="ti-eye"></i>
                                            </a>
                                            @if($data->package_gateway == 'manual_payment' && $data->status == 'pending')
                                            <a tabindex="0" class="btn btn-lg btn-success btn-sm mb-2 me-1" role="button" data-bs-toggle="popover" data-trigger="focus" data-html="true" title="" data-content="
                                        <h6>{{__('Are you sure to approve this payment?')}}</h6>
                                        <form method='post' action='{{route('admin.payment.approve',$data->id)}}'>
                                        <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                        <br>
                                            <input type='submit' class='btn btn-success btn-sm' value='{{__('Yes,Please')}}'>
                                            </form>
                                            " data-original-title="">
                                                <i class="ti-check"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('backend.partials.datatable.script-enqueue')
    <script>
        (function($){
        "use strict";
        $(document).ready(function($) {

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
                        'url' : "{{route('admin.payment.bulk.action')}}",
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
                var allChek = $('.bulk-checkbox');
                if( value == true){
                    allChek.prop('checked',true);
                }else{
                    allChek.prop('checked',false);
                }
            });


        } );
                
        })(jQuery);
    </script>
@endsection

