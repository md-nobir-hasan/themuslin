<div class="single-input mt-3">
    <label for="{{ $id }}" class="label-title mb-3">{{ $title }}</label>
    <textarea class="form-control summernote" name="{{ $name }}" id="{{ $id }}">{!!  $value ?? '' !!}</textarea>
</div>
