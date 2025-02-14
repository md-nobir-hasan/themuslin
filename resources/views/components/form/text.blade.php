<div class="single-input mb-3">
    <label for="{{ $id }}" class="label-title mb-3">{{ $title ?? '' }}</label>
    <input type="{{ $type ?? '' }}" name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" value="{{ $value ?? '' }}" placeholder="{{ $placeholder ?? '' }}" class="{{ $class ?? 'form-control' }}" >
</div>
