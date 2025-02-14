<div class="media-upload-btn-wrapper">
    <div class="img-wrap">
        @php
            $image = get_attachment_image_by_id(get_static_option($value ?? ''), null, true);
            $image_btn_label = __('Upload Image');
        @endphp
        @if (!empty($image))
            <div class="attachment-preview">
                <div class="thumbnail">
                    <div class="centered">
                        <img class="avatar user-thumb" src="{{ $image['img_url'] }}" alt="">
                    </div>
                </div>
            </div>
            @php  $image_btn_label =__( 'Change Image'); @endphp
        @endif
    </div>
    <input type="hidden" id="{{ $id }}" name="{{ $name }}"
        value="{{ get_static_option($value ?? '') }}">
    <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{ __('Select Image') }}"
        data-modaltitle="{{ __('Upload Image') }}" data-bs-toggle="modal" data-bs-target="#media_upload_modal">
        {{ __($image_btn_label) }}
    </button>
</div>
