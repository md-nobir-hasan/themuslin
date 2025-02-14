<div class="single-input">
    <label class="label-title">{{ $title }}</label>
    <textarea name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="form-message" cols="{{ $cols ?? 30 }}" rows="{{ $rows ?? 3 }}" placeholder="{{ $placeholder ?? '' }}">{{ $value ?? '' }}</textarea>
</div>
