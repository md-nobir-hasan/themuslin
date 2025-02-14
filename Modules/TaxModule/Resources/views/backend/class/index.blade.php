@extends('backend.admin-master')
@section('site-title', __('Tax Class'))

@section('style')

@endsection

@section('content')
    <div>
        <x-msg.flash />
        <x-msg.error />
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <div class="dashboard__card__header__left">
                        <h3 class="dashboard__card__title">{{ __('Manage Tax Class') }}</h3>
                        <small class="text-secondary mt-1">
                            {{ __("if a class have any you can't delete class from hare you need to delete all options first or you can force for delete") }}
                        </small>
                    </div>
                </div>
                <div class="dashboard__card__body mt-4">
                    <div class="table-wrap">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>{{ __('SL NO') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $class)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('admin.tax-module.tax-class-option', $class->id) }}">{{ __('View') }}</a>
                                            <button data-id="{{ $class->id }}" data-name="{{ $class->name }}"
                                                id="updateTaxClassButton" class="btn btn-primary btn-sm"
                                                data-bs-target="#updateTaxClass"
                                                data-bs-toggle="modal">{{ __('Edit') }}</button>
                                            <button id="deleteTaxClassButton" data-id="{{ $class->id }}"
                                                data-option-count="{{ $class->class_option_count }}"
                                                data-href="{{ route('admin.tax-module.tax-class-delete', $class->id) }}"
                                                class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h3 class="dashboard__card__title">{{ __('Create tax class') }}</h3>
                </div>
                <div class="dashboard__card__body custom__form mt-4">
                    <form action="{{ route('admin.tax-module.tax-class') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="#tax-class-name" class="form-label">{{ __('Name') }}</label>
                            <input name="name" type="text" class="form-control"
                                placeholder="{{ __('Write tax class name') }}" />
                        </div>

                        <div class="form-group">
                            <button class="cmn_btn btn_bg_profile">{{ __('Create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateTaxClass" tabindex="-1" aria-labelledby="exampleUpdateTaxClass" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom__form">
                <form action="{{ route('admin.tax-module.tax-class') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="" id="tax-class-id" class="form-control">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Update tax class') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#update-tax-class-name" class="form-label">{{ __('Name') }}</label>
                            <input id="update-tax-class-name" name="name" type="text" class="form-control"
                                placeholder="{{ __('Write tax class name') }}" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on("click", "#updateTaxClassButton", function() {
            $("#updateTaxClass #tax-class-id").val($(this).attr("data-id"));
            $("#updateTaxClass #update-tax-class-name").val($(this).attr("data-name"));

        })
        $(document).on("click", "#deleteTaxClassButton", function() {
            let countOption = $(this).attr("data-option-count");
            let formData = new FormData();
            formData.append("_method", "DELETE");
            formData.append("_token", "{{ csrf_token() }}");
            formData.append("id", $(this).attr("data-id"));

            if (countOption > 0) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "if delete this tax class then all tax class option will be deleted and You won't be able to revert those!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        send_ajax_request("GET", formData, $(this).data("data-href"), () => {
                            // before send request
                            toastr.warning("Request send please wait while");
                        }, (data) => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );

                            $(this).parent().parent().remove();
                        }, (data) => {
                            prepare_errors(data);
                        })
                    }
                });
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    send_ajax_request("post", formData, $(this).data("data-href"), () => {
                        // before send request
                        toastr.warning("Request send please wait while");
                    }, (data) => {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );

                        $(this).parent().parent().remove();
                    }, (data) => {
                        prepare_errors(data);
                    })
                }
            });
        });
    </script>
@endsection
