<div class="single-input mb-3">
    <label class="label-title">{{ $title ?? '' }}</label>
    <input type="{{ $type ?? '' }}" name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="{{ $class ?? 'form-control' }}" placeholder="{{ $placeholder ?? '' }}">
    <div class="toggle-password">
        <span class="show-icon"><i class="fa-solid fa-eye-slash"></i></span>
        <span class="hide-icon"><i class="fa-solid fa-eye"></i></span>
    </div>
</div>
