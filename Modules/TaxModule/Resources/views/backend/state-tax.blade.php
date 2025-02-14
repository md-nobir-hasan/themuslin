@extends('backend.admin-master')
@section('site-title')
    {{ __('State Tax') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{ __('All State Tax') }}</h4>
                        @can('tax-state-delete')
                            <x-bulk-action.dropdown />
                        @endcan
                        <button class="btn btn-info btn-sm text-light" data-bs-toggle="modal"
                            data-bs-target="#state_tax_new_modal">Add new state tax</button>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_state_tax as $tax)
                                        <tr>
                                            <x-bulk-action.td :id="$tax->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($tax->state)->name }}</td>
                                            <td>{{ $tax->tax_percentage }}</td>
                                            <td>
                                                @can('tax-state-delete')
                                                    <x-table.btn.swal.delete :route="route('admin.tax.state.delete', $tax->id)" />
                                                @endcan
                                                @can('tax-state-update')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#state_tax_edit_modal"
                                                        class="btn btn-primary btn-sm btn-xs mb-2 me-1 state_tax_edit_btn"
                                                        data-id="{{ $tax->id }}" data-country_id="{{ $tax->country_id }}"
                                                        data-state_id="{{ $tax->state_id }}"
                                                        data-tax_percentage="{{ $tax->tax_percentage }}">
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
        </div>
    </div>

    @can('tax-state-update')
        <div class="modal fade" id="state_tax_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update State Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.tax.state.update') }}" method="post">
                        <input type="hidden" name="id" id="state_tax_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group country-wrapper">
                                <label for="country_id">{{ __('Country') }}</label>
                                <select name="country_id" class="form-control" id="edit_country_id">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach ($all_country as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group state-wrapper">
                                <label for="edit_state_id">{{ __('state') }}</label>
                                <select name="state_id" class="form-control" id="edit_state_id">
                                    <option value="">{{ __('select state') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_tax_percentage">{{ __('Tax Percentage') }}</label>
                                <input type="number" class="form-control" id="edit_tax_percentage" name="tax_percentage"
                                    placeholder="{{ __('Tax Percentage') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('tax-state-create')
        <div class="modal fade" id="state_tax_new_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New State Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.tax.state.new') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="country_id">{{ __('Country') }}</label>
                                <select name="country_id" class="form-control" id="create_country_id">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach ($all_country as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group create-state-wrapper">
                                <label for="state_id">{{ __('State') }}</label>
                                <select name="state_id" class="form-control" id="create_state_id">
                                    <option value="">{{ __('Select Country first') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tax_percentage">{{ __('Tax Percentage') }}</label>
                                <input type="number" class="form-control" id="tax_percentage" name="tax_percentage"
                                    placeholder="{{ __('Tax Percentage') }}">
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{ __('Add New') }}</button>
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
    <x-bulk-action.js :route="route('admin.tax.state.bulk.action')" />

    <script>
        $(document).ready(function() {
            $(document).on('click', '.state_tax_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let country_id = el.data('country_id');
                let state_id = el.data('state_id');
                let tax_percentage = el.data('tax_percentage');

                let modal = $('#state_tax_edit_modal');
                //ajax call to get country related state and set select the current value
                $.get('{{ route('admin.state.by.country') }}', {
                    id: el.data('country_id')
                }).then(function(data) {

                    $('#edit_state_id').html('');
                    let option = "";
                    let list = "";
                    for (const state of data) {
                        let selected = state.id == state_id ? 'selected' : '';
                        // $('#edit_state_id').append('<option '+selected+' value="'+state.id+'">'+state.name+'</option>');

                        option += `<option value="` + state.id + `">` + state.name + `</option>`;
                        list += `<li data-value="` + state.id + `" class="option">` + state.name +
                            `</li>`;
                    }

                    $('#edit_state_id').html(option);
                    $(".state-wrapper .list").html(list);
                    $(".state-wrapper .list li[data-value=" + state_id + "]").trigger("click");
                    modal.find('.modal-footer').trigger("click");
                });

                modal.find('#state_tax_id').val(id);
                modal.find('#edit_state_id').val(state_id);
                modal.find('#edit_country_id option[value="' + el.data('country_id') + '"]').attr(
                    'selected', true);

                $("#country_id option[value=" + country_id + "]").select();
                $(".country-wrapper .list li[data-value=" + country_id + "]").trigger("click");
                $('#edit_state_id option[value=' + state_id + ']').attr("selected", "true");
                modal.find('#edit_tax_percentage').val(tax_percentage);
                modal.find('.modal-footer').trigger("click");
            });

            $('#country_id').on('change', function() {
                let id = $(this).val();
                $.get('{{ route('admin.state.by.country') }}', {
                    id: id
                }).then(function(data) {
                    $('#state_id').html('');
                    for (const state of data) {
                        $('#state_id').append('<option value="' + state.id + '">' + state.name +
                            '</option>');
                    }
                });
            });

            $('#create_country_id').on('change', function() {
                let id = $(this).val();
                $.get('{{ route('admin.state.by.country') }}', {
                    id: id
                }).then(function(data) {
                    $('#create_state_id').html('');
                    let option = "";
                    let list = "";
                    for (const state of data) {
                        option += '<option value="' + state.id + '">' + state.name + '</option>';
                        list += `<li data-value="` + state.id + `" class="option">` + state.name +
                            `</li>`;
                    }

                    $('#create_state_id').html(option);
                    $(".create-state-wrapper .list").html(list);
                });
            });

            $('#edit_country_id').on('change', function() {
                let id = $(this).val();
                $.get('{{ route('admin.state.by.country') }}', {
                    id: id
                }).then(function(data) {
                    $('#edit_state_id').html('');
                    let ed_option = "";
                    let ed_list = `<li data-value="" class="option">Select State</li>`;

                    for (const state of data) {
                        ed_option += '<option value="' + state.id + '">' + state.name + '</option>';
                        ed_list += `<li data-value="` + state.id + `" class="option">` + state
                            .name + `</li>`;
                    }

                    $('#edit_state_id').html(ed_option);
                    // $(".state-wrapper .current").html("Select State");
                    $(".state-wrapper .list").html(ed_list);
                });
            });
        });
    </script>
@endsection
