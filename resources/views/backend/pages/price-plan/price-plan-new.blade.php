@extends('backend.admin-master')
@section('site-title')
    {{ __('New Price Plan') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <!-- basic form start -->
            <div class="col-lg-12">
                @include('backend/partials/message')
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('New Price Plan') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.price.plan') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="language">{{ __('Languages') }}</label>
                                <select name="lang" id="language" class="form-control">
                                    @foreach ($all_languages as $lang)
                                        <option value="{{ $lang->slug }}">{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">{{ __('Title') }}</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="{{ __('Title') }}">
                            </div>
                            <div class="form-group">
                                <label for="price">{{ __('Price') }}</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    placeholder="{{ __('Price') }}">
                            </div>
                            <div class="form-group">
                                <label for="type">{{ __('Type') }}</label>
                                <input type="text" class="form-control" id="type" name="type"
                                    placeholder="{{ __('Type') }}">
                            </div>
                            <div class="form-group">
                                <label for="features">{{ __('Features') }}</label>
                                <textarea class="form-control" id="features" name="features" placeholder="{{ __('Features') }}" cols="30"
                                    rows="10"></textarea>
                                <small class="info=text">{{ __('Separate feature by new line') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="btn_text">{{ __('Button Text') }}</label>
                                <input type="text" class="form-control" id="btn_text" name="btn_text"
                                    placeholder="{{ __('Button Text') }}">
                            </div>
                            <div class="form-group">
                                <label for="highlinght"><strong>{{ __('Highlight') }}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="highlinght" id="highlinght">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="url_status"><strong>{{ __('Plan Detail Page') }}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="url_status" id="url_status">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="btn_url">{{ __('Button URL') }}</label>
                                <input type="text" class="form-control" id="btn_url" name="btn_url"
                                    placeholder="{{ __('Button URL') }}">
                            </div>
                            <div class="form-group">
                                <label for="categories_id">{{ __('Category') }}</label>
                                <select name="categories_id" class="form-control" id="category">
                                    @foreach ($all_category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="publish">{{ __('Publish') }}</option>
                                    <option value="draft">{{ __('Draft') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Add New Price Plan') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.price_plan_edit_btn', function() {
                var el = $(this);
                var id = el.data('id');
                var title = el.data('title');
                var action = el.data('action');
                var form = $('#price_plan_edit_modal_form');
                form.attr('action', action);
                form.find('#price_plan_id').val(id);
                form.find('#edit_title').val(title);
                form.find('#edit_price').val(el.data('price'));
                form.find('#edit_icon').val(el.data('icon'));
                form.find('#edit_type').val(el.data('type'));
                form.find('#edit_btn_text').val(el.data('btntext'));
                form.find('#edit_btn_url').val(el.data('btnurl'));
                form.find('#edit_features').val(el.data('features'));
                form.find('.icp-dd').attr('data-selected', el.data('icon'));
                form.find('.iconpicker-component i').attr('class', el.data('icon'));
                form.find('#edit_language option[value=' + el.data("lang") + ']').attr('selected', true);
                if (el.data('urlstatus') != '') {
                    form.find('#edit_url_status').attr('checked', true);
                    form.find('#edit_url_status').parent().parent().next().hide();
                }
            });
            $('.icp-dd').iconpicker();
            $('.icp-dd').on('iconpickerSelected', function(e) {
                var selectedIcon = e.iconpickerValue;
                $(this).parent().parent().children('input').val(selectedIcon);
            });

            $(document).on('change', 'input[name="url_status"]', function(e) {
                e.preventDefault();
                if ($('input[name="url_status"]').is(":checked")) {
                    $(this).parent().parent().next().hide();
                } else {
                    $(this).parent().parent().next().show();
                }
            });

            $(document).on('change', '#language', function(e) {
                e.preventDefault();
                var selectedLang = $(this).val();
                $.ajax({
                    url: "{{ route('admin.price.plan.lang.cat') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        lang: selectedLang
                    },
                    success: function(data) {
                        $('#category').html('<option value="">Select Category</option>');
                        $.each(data, function(index, value) {
                            $('#category').append('<option value="' + value.id + '">' +
                                value.name + '</option>')
                        });
                    }
                });
            });

        });
    </script>
@endsection
