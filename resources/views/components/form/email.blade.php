<div class="single-input">
    <label for="title" class="label-title">{{ $title }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? '' }}" value="{{ $value ?? old($name) }}" placeholder="{{ $placeholder }}" class="form-control" >
</div>
