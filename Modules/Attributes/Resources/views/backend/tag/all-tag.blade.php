@extends('backend.admin-master')
@section('site-title')
    {{__('Product Tag')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-7">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h3 class="dashboard__card__title">{{ __("All Product Tags") }}</h3>
                        @can('tags-bulk-action')
                            <x-bulk-action.dropdown />
                        @endcan
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can('tags-bulk-action')
                                        <x-bulk-action.th />
                                    @endcan
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                    @foreach($all_tag as $tag)
                                    <tr>
                                        @can('tags-bulk-action')
                                            <x-bulk-action.td :id="$tag->id" />
                                        @endcan
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$tag->tag_text}}</td>
                                        <td>
                                            @can('tags-delete')
                                                <x-table.btn.swal.delete class="margin-bottom-0" :route="route('admin.tag.delete', $tag->id)" />
                                            @endcan

                                            @can('tags-update')
                                                <a href="#1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#tag_edit_modal"
                                                    class="btn btn-sm mt-0 btn-info tag_edit_btn"
                                                    data-id="{{$tag->id}}"
                                                    data-name="{{$tag->tag_text}}"
                                                >
                                                    <i class="ti-pencil"></i>
                                                </a>
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
            @can('tags-new')
            <div class="col-lg-5">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{__('Add New Tag')}}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{route('admin.tag.new')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="name" name="title" placeholder="{{__('Name')}}">
                            </div>
                            <div class="btn-wrapper mt-4">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{__('Add New')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @can('tags-update')
    <div class="modal fade" id="tag_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Update Tag')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.tag.update')}}"  method="post">
                    <input type="hidden" name="id" id="tag_id">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="edit_name">{{__('Name')}}</label>
                            <input type="text" class="form-control"  id="edit_name" name="title" placeholder="{{__('Name')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    @can('tags-bulk-action')
        <x-bulk-action.js :route="route('admin.tag.bulk.action')" />
    @endcan

    <script>
        $(document).ready(function () {
            $(document).on('click','.tag_edit_btn',function(){
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let modal = $('#tag_edit_modal');

                modal.find('#tag_id').val(id);
                modal.find('#edit_name').val(name);

                modal.show();
            });
        });
    </script>

@endsection
