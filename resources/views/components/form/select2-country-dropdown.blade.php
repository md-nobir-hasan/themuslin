<div class="single-input">
    <label class="label-title mt-3">{{ $title }}</label>
        <select name="{{ $name }}" id="{{ $id ?? '' }}" class="form-control country_select2">
            <option value="">{{ $title }}</option>
            @foreach($allData as $data)
                <option value="{{ $data->id }}">{{ $data->country }}</option>
            @endforeach
        </select>
</div>
