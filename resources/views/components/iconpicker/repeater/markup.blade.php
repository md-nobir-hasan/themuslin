{{-- !!! NOTE: check the social_icon and social_links name !!! --}}

@php
    $name = $socialIconsName . '[]';
    $link = $socialIconsLink . '[]';
    $id = $id ?? $name;
    $label = $label ?? ''; // <label> text. If truthy, label will be shown.
@endphp

<div class="social_links">
    <div class="social_link row">
        <div class="col-sm-1 col-xl-1">
            <x-iconpicker.input :id="$id" :name="$name" :label="$label"/>
        </div>
        <div class="col-sm-8 col-xl-8 form-group ml-6">
            <input type="text" class="form-control" id="$id"  name="$socialIconsLink" placeholder="{{__('Link')}}">
        </div>
        <div class="col-sm-3 col-xl-3">
            <button type="button" class="btn btn-sm btn-success add">+</button>
            <button type="button" class="btn btn-sm btn-danger remove">-</button>
        </div>
    </div>
</div>

{{-- push css (once) --}}
@once
    @push('custom_style')
<style>
.social_link.row .ml-6 {padding-left: 4rem !important;}
</style>
    @endpush
@endonce
