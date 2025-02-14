@if(session()->has('stock_error'))
    @foreach(session('stock_error') as $item)
        <div class="alert alert-danger">
            {!! $item["msg"] !!}
        </div>
    @endforeach
@endif
