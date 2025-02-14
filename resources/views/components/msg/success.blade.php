@if(session()->has('msg'))
    <div class="alert alert-{{session('type') ? session('type') : 'success' }}">
        {!! Purifier::clean(session('msg')) !!}
    </div>
@endif
