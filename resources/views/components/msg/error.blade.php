@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="list-none">
            <button type="button btn-sm" class="close" data-bs-dismiss="alert">×</button>
            @foreach ($errors->all() as $error)
                <li> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
