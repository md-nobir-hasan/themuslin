@extends('backend.admin-master')
@section('site-title')
    {{__('Faq')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <x-media.css/>
    <x-summernote.css/>
    <x-datatable.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-7">
                <x-msg.error/>
                <x-msg.success/>
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{__('Faq Items')}}</h4>
                        <div class="dashboard__card__header__right">
                            @can('faq-faq-bulk-action')
                                <div class="bulk-delete-wrapper">
                                    <div class="select-box-wrap">
                                        <select name="bulk_option" id="bulk_option">
                                            <option value="">{{{__('Bulk Action')}}}</option>
                                            <option value="delete">{{{__('Delete')}}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="btn-wrapper">
                                    <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{__('Apply')}}</button>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can('faq-faq-bulk-action')
                                        <th class="no-sort">
                                            <div class="mark-all-checkbox">
                                                <input type="checkbox" class="all-checkbox">
                                            </div>
                                        </th>
                                    @endcan
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_faqs as $data)
                                    <tr>
                                        @can('faq-faq-bulk-action')
                                            <td>
                                                <div class="bulk-checkbox-wrapper">
                                                    <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                                </div>
                                            </td>
                                        @endcan
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->title}}</td>
                                        <td>@if($data->status == 'publish') <span
                                                    class="alert alert-success">{{__('Publish')}}</span> @else <span
                                                    class="alert alert-warning">{{__('Draft')}}</span> @endif</td>
                                        <td>
                                            @can('faq-delete-faq')
                                                <x-delete-popover :url="route('admin.faq.delete',$data->id)"/>
                                            @endcan
                                            @can('faq-edit-faq')
                                                <a href="#1"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#faq_item_edit_modal"
                                                   class="btn btn-primary btn-xs mb-2 me-1 faq_edit_btn"
                                                   data-id="{{$data->id}}"
                                                   data-group="{{$data->faq_group}}"
                                                   data-title="{{$data->title}}"
                                                   data-lang="{{$data->lang}}"
                                                   data-is_open="{{$data->is_open}}"
                                                   data-description="{{$data->description}}"
                                                   data-status="{{$data->status}}">
                                                    <i class="ti-pencil"></i>
                                                </a>
                                            @endcan
                                           <!--  @can("faq-clone-faq")
                                                <form action="{{route('admin.faq.clone')}}" method="post"
                                                      style="display: inline-block">
                                                    @csrf
                                                    <input type="hidden" name="item_id" value="{{$data->id}}">
                                                    <button type="submit" title="{{__('clone this to new draft')}}"
                                                        class="btn btn-xs btn-secondary btn-sm mb-2 me-1">
                                                        <i class="las la-copy"></i>
                                                    </button>
                                                </form>
                                            @endcan -->
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            @can('faq-create-faq')
                <div class="col-lg-5">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{__('New Faq')}}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{route('admin.faq')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">{{__('Group')}}</label>
                                            <select name="faq_group" class="form-control" required="">
                                                <option>Select</option>
                                                <option value="The Muslin">The Muslin</option>
                                                <option value="Product">Product</option>
                                                <option value="Ordering">Ordering</option>
                                                <option value="Registration">Registration</option>
                                                <option value="My Account">My Account</option>
                                                <option value="Payment">Payment</option>
                                                <option value="Order">Order</option>
                                                <option value="Delivery">Delivery</option>
                                                <option value="Returns">Returns</option>
                                                <option value="Customer Service">Customer Service</option>
                                                <option value="Other General Questions">Other General Questions</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">{{__('Title')}}</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                placeholder="{{__('Title')}}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="is_open">{{__('Is Open')}}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="is_open" id="is_open">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div> -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="description">{{__('Description')}}</label>
                                            <textarea name="description" class="summernote"> </textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="status">{{__('Status')}}</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="publish">{{__('Publish')}}</option>
                                                <option value="draft">{{__('Draft')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button id="submit" type="submit" class="cmn_btn btn_bg_profile">{{__('Add New Faq')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('faq-edit-faq')
        <div class="modal fade" id="faq_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Edit Faq Item')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('admin.faq.update')}}" id="faq_edit_modal_form" enctype="multipart/form-data"
                          method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="faq_id" value="">

                             <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title">{{__('Group')}}</label>
                                    <select name="faq_group" class="form-control" required="" id="edit_group">
                                        <option>Select</option>
                                        <option value="The Muslin">The Muslin</option>
                                        <option value="Product">Product</option>
                                        <option value="Ordering">Ordering</option>
                                        <option value="Registration">Registration</option>
                                        <option value="My Account">My Account</option>
                                        <option value="Payment">Payment</option>
                                        <option value="Order">Order</option>
                                        <option value="Delivery">Delivery</option>
                                        <option value="Returns">Returns</option>
                                        <option value="Customer Service">Customer Service</option>
                                        <option value="Other General Questions">Other General Questions</option>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="edit_title">{{__('Title')}}</label>
                                <input type="text" class="form-control" id="edit_title" name="title"
                                       placeholder="{{__('Title')}}">
                            </div>
                            <!-- <div class="form-group">
                                <label for="edit_is_open">{{__('Is Open')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="is_open" id="edit_is_open">
                                    <span class="slider"></span>
                                </label>
                            </div> -->
                            <div class="form-group">
                                <label for="edit_description">{{__('Description')}}</label>
                                <input type="hidden" id="edit_description" name="description">
                                <div class="summernote"></div>
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{__('Status')}}</label>
                                <select name="status" id="edit_status" class="form-control">
                                    <option value="publish">{{__('Publish')}}</option>
                                    <option value="draft">{{__('Draft')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button id="update" type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <x-media.markup/>
@endsection
@section('script')
    <x-summernote.js/>
    @can('faq-faq-bulk-action')
        <x-bulk-action-js :url="route('admin.faq.bulk.action')" />
    @endcan
    <script>
        (function($){
            $(document).ready(function () {
                <x-btn.submit/>
                <x-btn.update/>

                $(document).on('click', '.faq_edit_btn', function () {
                    var el = $(this);
                    var id = el.data('id');
                    var title = el.data('title');
                    var form = $('#faq_edit_modal_form');
                    form.find('#faq_id').val(id);
                    form.find('#edit_title').val(title);
                    form.find('#edit_description').val(el.data('description'));
                    form.find('#edit_status option[value="' + el.data('status') + '"]').attr('selected', true);
                    form.find('#edit_group option[value="' + el.data('group') + '"]').attr('selected', true);

                    if (el.data('is_open') != '') {
                        form.find('#edit_is_open').attr('checked', true);
                    } else {
                        form.find('#edit_is_open').attr('checked', false);
                    }
                    form.find('.summernote').summernote('code', el.data('description'));
                });

            });
        })(jQuery)
    </script>


    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <x-datatable.js/>
    <x-media.js/>
@endsection
