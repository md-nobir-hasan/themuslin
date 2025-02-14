@extends('backend.admin-master')
@section('site-title')
    {{ __('Product Child-Category') }}
@endsection
@section('style')
    <x-media.css />
    <x-datatable.css />
    <x-bulk-action.css />
@endsection

@php
    $statuses = \App\Status::all();
@endphp

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h3 class="dashboard__card__title">{{ __('All Products Child-Categories') }}</h3>
                        <div class="dashboard__card__header__right">
                            @can('child-categories-bulk-action')
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('child-categories-new')
                                <a href="#1" data-bs-toggle="modal" data-bs-target="#child-category_create_modal"
                                    class="cmn_btn btn_bg_profile">{{ __('New Child Category') }}</a>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can('child-categories-bulk-action')
                                        <x-bulk-action.th />
                                    @endcan
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Category Name') }}</th>
                                    <th>{{ __('Sub Category Name') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Show Home') }}</th>
                                    <th>{{ __('Sort Order') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_child_category as $child_category)
                                        @php
                                            $category = $child_category->category?->name;
                                            $sub_category = $child_category->sub_category?->name;
                                        @endphp
                                        <tr>
                                            @can('child-categories-bulk-action')
                                                <x-bulk-action.td :id="$child_category->id" />
                                            @endcan
                                            <td>{{ (($all_child_category->perPage() * ($all_child_category->currentPage() - 1)) + $loop->iteration) }}</td>
                                            <td>{{ $category }}</td>
                                            <td>{{ $sub_category }}</td>
                                            <td>{{ $child_category->name }}</td>
                                            <td>
                                                <x-status-span :status="$child_category->status?->name" />
                                            </td>
                                            <td>
                                                <div class="attachment-preview">
                                                    <div class="img-wrap">
                                                        {!! \App\Http\Services\Media::render_image($child_category->image, 'thumb', class: 'w-100') !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $child_category->show_home }}
                                            </td>
                                            <td>
                                                {{ $child_category->sort_order }}
                                            </td>
                                            
                                            <td>
                                                @can('child-categories-delete')
                                                    <x-table.btn.swal.delete :route="route(
                                                        'admin.child-category.delete',
                                                        $child_category->id,
                                                    )" />
                                                @endcan
                                                @can('child-categories-update')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#child-category_edit_modal"
                                                        class="btn btn-sm btn-primary btn-xs mb-2 me-1 child-category_edit_btn"
                                                        data-id="{{ $child_category->id }}"
                                                        data-name="{{ $child_category->name }}"
                                                        data-slug="{{ $child_category->slug }}"
                                                        data-status="{{ $child_category->status_id }}"
                                                        data-imageid="{!! $child_category->image_id !!}"
                                                        data-bannerid="{!! $child_category->banner_id !!}"
                                                        data-mbannerid="{!! $child_category->m_banner_id !!}"
                                                        data-image="{{ \App\Http\Services\Media::render_image($child_category->image, render_type: 'path') }}"
                                                        data-banner="{{ \App\Http\Services\Media::render_image($child_category->bannerImage, render_type: 'path') }}"
                                                        data-mbanner="{{ \App\Http\Services\Media::render_image($child_category->mobileBannerImage, render_type: 'path') }}"
                                                        data-category-id="{{ $child_category->category_id }}"
                                                        data-sub-category-id="{{ $child_category->sub_category_id }}"
                                                        data-show-home="{{ $child_category->show_home }}"
                                                        data-featured="{{ $child_category->featured }}"
                                                        data-sort-order="{{ $child_category->sort_order }}"
                                                        >
                                                        <i class="ti-pencil"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="child-category-pagination">{{ $all_child_category->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('child-categories-update')
        <div class="modal fade" id="child-category_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Child-Category') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.child-category.update') }}" method="post">
                        <input type="hidden" name="id" id="child-category_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_slug">{{ __('Slug') }}</label>
                                <input type="text" class="form-control" id="edit_slug" name="slug"
                                    placeholder="{{ __('Slug') }}">
                            </div>
                            <div class="form-group edit-category-wrapper">
                                <label for="category">{{ __('Category') }}</label>
                                <select class="form-control" id="edit_category_id" name="category_id">
                                    <option>{{ __('Select Category') }}</option>
                                    @foreach ($all_category as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group edit-sub-category-wrapper">
                                <label for="category">{{ __('Sub Category') }}</label>
                                <select class="form-control" id="edit_sub_category" name="sub_category_id">
                                    <option>{{ __('Select Sub Category') }}</option>
                                    {{--                                    @foreach ($sub_categories as $sub_category) --}}
                                    {{--                                        <option value="{{ $category->id }}">{{ $category->name }}</option> --}}
                                    {{--                                    @endforeach --}}
                                </select>
                            </div>

                            <x-media-upload :title="__('Thumb Image')" :name="'image_id'" :dimentions="'570 X 400'" :id="'thumb_img'" />
                            <x-media-upload :title="__('Banner Image')" :name="'banner_id'" :dimentions="'1366 X 300'" :id="'banner_img'" />
                            <x-media-upload :title="__('Mobile Banner Image')" :name="'m_banner_id'" :dimentions="'375 X 250'" :id="'m_banner_img'" />


                            <div class="form-group">
                                <label>{{ __('Sort Order') }}</label>
                                <input type="text" class="form-control" name="sort_order" placeholder="{{ __('Sort Order') }}" id="edit_sort_order">
                            </div>

                            <div class="form-group edit-home-wrapper">
                                <label>{{ __('Show in Home Page') }}</label>
                                <select name="show_home" class="form-control" id="edit_show_home">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>


                            <div class="form-group edit-featured-wrapper">
                                <label>{{ __('Home Page Featured') }}</label>
                                <select name="featured" class="form-control" id="edit_featured">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="form-group edit-status-wrapper">
                                <label for="edit_status">{{ __('Status') }}</label>
                                <select name="status_id" class="form-control" id="edit_status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('child-categories-new')
        <div class="modal fade" id="child-category_create_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add Child-Category') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.child-category.new') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="create-name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="create-name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>

                            <div class="form-group">
                                <label for="create-slug">{{ __('Slug') }}</label>
                                <input type="text" class="form-control" id="create-slug" name="slug"
                                    placeholder="{{ __('Slug') }}">
                            </div>

                            <div class="form-group category-wrapper">
                                <label for="category_id">{{ __('Category') }}</label>
                                <select class="form-control" id="create_category_id" name="category_id">
                                    <option>{{ __('Select Category') }}</option>
                                    @foreach ($all_category as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group create-sub-category-wrapper">
                                <label for="category">{{ __('Sub Category') }}</label>
                                <select class="form-control" id="create_sub_category" name="sub_category_id">
                                    <option>{{ __('Select Sub Category') }}</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label>{{ __('Sort Order') }}</label>
                                <input type="text" class="form-control" name="sort_order" placeholder="{{ __('Sort Order') }}">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Show in Home Page') }}</label>
                                <select name="show_home" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label>{{ __('Home Page Featured') }}</label>
                                <select name="featured" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status_id" class="form-control" id="status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-media-upload :title="__('Image')" :name="'image_id'" :dimentions="'570 X 400'" />
                            <x-media-upload :title="__('Banner Image')" :name="'banner_id'" :dimentions="'1366 X 300'" />
                            <x-media-upload :title="__('Mobile Banner Image')" :name="'m_banner_id'" :dimentions="'375 X 250'" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Add New') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <x-media.markup />

@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $(document).on("change", "#create_category_id", function() {
                let category_id = $(this).val();

                $.ajax({
                    url: '{{ route('admin.subcategory.all') }}/of-category/select/' + category_id,
                    type: 'GET',
                    data: {
                        _token: '<?php echo csrf_token(); ?>',
                        "category_id": category_id
                    },
                    success: function(data) {
                        $("#create_sub_category").html(data.option);
                        $(".create-sub-category-wrapper .list").html(data.list);
                        $(".create-sub-category-wrapper span.current").html(
                            "Select Sub Category");
                    },
                    error: function(err) {
                        {{-- toastr.error('<?php echo __('An error occurred'); ?>'); --}}
                    }
                });
            });

            $(document).on("change", "#edit_sub_category_id", function() {
                let category_id = $(this).val();

                $.ajax({
                    url: '{{ route('admin.subcategory.all') }}/of-category/select/' + category_id,
                    type: 'GET',
                    data: {
                        _token: '<?php echo csrf_token(); ?>',
                        "category_id": category_id
                    },
                    success: function(data) {
                        $("#edit_sub_category").html(data.option);
                        $(".edit-sub-category-wrapper .list").html(data.list);
                        $(".edit-sub-category-wrapper span.current").html(
                            "Select Sub Category");
                    },
                    error: function(err) {
                        toastr.error('<?php echo __('An error occurred'); ?>');
                    }
                });
            });

            $('#create-name , #create-slug').on('keyup', function() {
                let title_text = $(this).val();
                $('#create-slug').val(convertToSlug(title_text))
            });

            $('#edit_name , #edit_slug').on('keyup', function() {
                let title_text = $(this).val();
                $('#edit_slug').val(convertToSlug(title_text))
            });

            $(document).on('click', '.child-category_edit_btn', function() {
                // $("#edit_sub_category_id").attr("id", "edit_category_id");
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let slug = el.data('slug');
                let status = el.data('status');
                let category_id = el.data('category-id');
                let sub_category_id = el.data('sub-category-id');
                let modal = $('#child-category_edit_modal');
                let sort_order = el.data('sort-order');
                let show_home = el.data('show-home');
                let featured = el.data('featured');

                $.ajax({
                    url: '{{ route('admin.subcategory.all') }}/of-category/select/' + category_id,
                    type: 'GET',
                    data: {
                        _token: '<?php echo csrf_token(); ?>',
                        "category_id": category_id
                    },
                    success: function(data) {
                        $("#edit_sub_category").html(data.option);
                        $(".edit-sub-category-wrapper .list").html(data.list);

                        modal.find(".edit-sub-category-wrapper .list li[data-value='" +
                            sub_category_id + "']").trigger("click");
                        modal.find(".modal-footer").trigger("click");
                        // $("#edit_category_id").attr("id", "edit_sub_category_id");
                    },
                    error: function(err) {
                        {{-- toastr.error('<?php echo __('An error occurred'); ?>'); --}}
                    }
                });


                modal.find('#child-category_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_category_id option[value="' + category_id + '"]').attr('selected', true);
                modal.find('#edit_name').val(name);
                modal.find('#edit_slug').val(slug);
                modal.find(".edit-status-wrapper .list li[data-value='" + status + "']").trigger("click");

                modal.find('#edit_show_home option[value="' + show_home + '"]').attr('selected', true);
                modal.find('#edit_featured option[value="' + featured + '"]').attr('selected', true);
                modal.find('#edit_sort_order').val(sort_order);

                modal.find(".modal-footer").trigger("click");


                let image = el.data('image');
                let imageid = el.data('imageid');

                let banner = el.data('banner');
                let bannerid = el.data('bannerid');

                let mbanner = el.data('mbanner');
                let mbannerid = el.data('mbannerid');

                if (imageid != '') {
                    modal.find('#image_id .media-upload-btn-wrapper .img-wrap').html(
                        '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                        image + '" > </div></div></div>');
                    modal.find('#image_id .media-upload-btn-wrapper input').val(imageid);
                    modal.find('#image_id .media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }


                if (bannerid != '') {
                    modal.find('#banner_id .media-upload-btn-wrapper .img-wrap').html(
                        '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                        banner + '" > </div></div></div>');
                    modal.find('#banner_id .media-upload-btn-wrapper input').val(bannerid);
                    modal.find('#banner_id.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }

                if (mbannerid != '') {
                    modal.find('#m_banner_id .media-upload-btn-wrapper .img-wrap').html(
                        '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                        mbanner + '" > </div></div></div>');
                    modal.find('#m_banner_id .media-upload-btn-wrapper input').val(mbannerid);
                    modal.find('#m_banner_id.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }


            });
        });
    </script>

    <x-media.js />
    <x-table.btn.swal.js />
    @can('child-categories-bulk-action')
        <x-bulk-action.js :route="route('admin.child-category.bulk.action')" />
    @endcan
@endsection
