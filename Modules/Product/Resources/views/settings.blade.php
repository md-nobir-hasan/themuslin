@extends("backend.admin-master")

@section("site-title")

@endsection

@section("content")
    <x-msg.success />
    <x-msg.error />

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __("Product Settings") }}</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="">{{ __("Inventory warning threshold") }}</label>
                            <input name="stock_threshold_amount" value="{{ get_static_option("stock_threshold_amount") ?? '' }}" class="form-control" placeholder="{{ __("Write inventory warning threshold amount") }}" type="number" />
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary">{{ __("Update Settings") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")

@endsection
