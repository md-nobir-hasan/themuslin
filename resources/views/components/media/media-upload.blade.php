@php
    $id = $id ?? null;
    $oldImage = $oldImage ?? null;
    $galleryImage = $galleryImage ?? null;
@endphp

@if (isset($multiple) && $multiple)
    <div class="dashboard__card mediaUploads__card">
        <div class="dashboard__card__header">
            <h4 class="dashboard__card__title">{{ $title }}</h4>
        </div>
        <div class="dashboard__card__body profile-photo-upload">
            <div class="profile-photo-change bg-white mt-4">
                <div class="upload-finish media-upload-btn-wrapper mt-4">
                    <div class="img-wrap row d-flex">
                        @if (isset($galleryImage))
                            @if ($galleryImage && $galleryImage != 'null')
                                @foreach ($galleryImage as $gl_img)
                                    <div class="upload-thumb col-xxl-2">
                                        {!! !empty($gl_img) ? render_image($gl_img) : $signature_image_tag !!}
                                        <span class="close-thumb" data-media-id="{{ $gl_img->id }}"> <i
                                                class="las la-times"></i> </span>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>

                @php
                    $galleryIds = $galleryImage?->pluck('id')?->toArray() ?? [];
                @endphp
                <input type="hidden" name="{{ $name }}" value="{{ implode('|', $galleryIds) }}">
                <button type="button" data-mulitple="true" class="photo-upload  media_upload_form_btn popup-modal"
                    data-btntitle="{{ __('Select Image') }}" data-modaltitle="{{ __('Upload Image') }}"
                    data-imgid="{{ $id ?? '' }}">
                    <span class="upload-icon"> <i class="las la-cloud-upload-alt"></i> </span>
                    <h5 class="dashboard-common-title">
                        {{ __("Click Files to this area to upload") }}
                    </h5>
                    <span class="upload-para mt-2"> 
                        <!-- {{ __("Dimension of the logo image should be 600 x 600px") }}  -->
                    </span>
                </button>
            </div>
        </div>
    </div>
@else
    <div class="dashboard__card mediaUploads__card">
        <div class="dashboard__card__header">
            <h4 class="dashboard__card__title">{{ $title }}</h4>
        </div>
        <div class="dashboard__card__body profile-photo-upload">
            <div class="profile-photo-change bg-white mt-4">
                <div class="upload-finish media-upload-btn-wrapper mt-4">
                    <div class="img-wrap row d-flex">
                        @if (!empty($oldImage))
                            <div class="upload-thumb col-xxl-2">
                                {!! !empty($oldImage) ? render_image($oldImage) : $signature_image_tag !!}
                                <span class="close-thumb" data-media-id="{{ $oldImage->id }}"> <i
                                        class="las la-times"></i> </span>
                            </div>
                            @php $signature_image_upload_btn_label = __('Change Image'); @endphp
                        @endif
                    </div>
                </div>

                <input type="hidden" name="{{ $name }}" value="{{ $oldImage?->id }}">
                <button type="button" class="photo-upload  media_upload_form_btn popup-modal"
                    data-btntitle="{{ __('Select Image') }}" data-modaltitle="{{ __('Upload Image') }}"
                    data-imgid="{{ $id ?? '' }}">
                    <span class="upload-icon">
                        <i class="las la-cloud-upload-alt"></i>
                    </span>
                    <h5 class="dashboard-common-title">
                        {{ __("Click Files to this area to upload") }}
                    </h5>
                    <span class="upload-para mt-2">
                        <!-- {{ __("Dimension of the logo image should be 600 x 600px") }} -->
                    </span>
                </button>
            </div>
        </div>
    </div>
@endif
