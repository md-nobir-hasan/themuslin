@extends('backend.admin-master')
@section('site-title', __('All Cities'))
@section('style')
    <x-select2.select2-css />
@endsection
@section('content')
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('All Cities') }}</h4>
                    <div class="dashboard__card__header__right">
                        <x-bulk-action.bulk-action />
                        <x-btn.add-modal :title="__('Add City')" />
                    </div>
                </div>
                <div class="dashboard__card__body mt-4">
                    <!-- Table Start -->
                    <div class="custom_table table-wrap style-04 search_result">
                        @include('countrymanage::city.search-result')
                    </div>
                    <!-- Table End -->
                </div>
            </div>
        </div>
    </div>

    @include('countrymanage::city.add-modal')
    @include('countrymanage::city.edit-modal')
@endsection

@section('script')
    <x-select2.select2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.city.delete.bulk.action')" />
    @include('countrymanage::city.city-js')

    <script>
        $(document).on('click', '.swal_status_change_button', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('You would change status any time') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('Yes, Change it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
    </script>
@endsection
