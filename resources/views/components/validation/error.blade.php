    <style>
        button.close {
            width: 30px;
            height: 30px;
            border: none;
            background: #000;
            color: #fff;
            border-radius: 3px;
            float: right;
            font-size: 20px;
        }
    </style>

@if($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="list-none">
            <button type="button btn-sm" class="close" data-bs-dismiss="alert">×</button>
            @foreach($errors->all() as $error)
                <li> {{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
