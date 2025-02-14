@php
    $oldimage = isset($oldimage) ? $oldimage : null;
    $imageId = $oldimage?->id ?? ($oldimage ?? null);
    $required = isset($required) ? $required : null;
@endphp

@if((($multiple ?? null) != true) ?? true)
    <div class="form-group" id="{{$name}}">
        <label for="{{$name}}">{{__($title)}}</label>
        @php $signature_image_upload_btn_label = __('Upload Image'); @endphp
        <div class="media-upload-btn-wrapper">
            <div class="img-wrap">
                @if (isset($oldimage) && !empty($oldimage))

                    <div class="upload-finish media-upload-btn-wrapper mt-4">
                        <div class="img-wrap row d-flex">
                            <div class="upload-thumb col-lg-12">
                                {!! render_image($oldimage) !!}

                                <span class="close-thumb" data-media-id="{{  $imageId }}"> <i class="las la-times"></i> </span>
                            </div>
                        </div>
                    </div>

                    @php $signature_image_upload_btn_label = __('Change Image'); @endphp
                @endif
            </div>
            <br>
            <input type="hidden" @php $required @endphp name="{{$name}}" value="{{  $imageId }}">
            <button type="button" class="btn btn-info media_upload_form_btn popup-modal"
                    data-btntitle="{{__('Select Image')}}"
                    data-modaltitle="{{__('Upload Image')}}"
                    data-imgid="{{ $imageId ?? ''}}"
                    data-bs-toggle="modal"
                    data-bs-target="#media_upload_modal">
                {{__($signature_image_upload_btn_label)}}
            </button>
        </div>
        <small>{{__('Recommended image size is ')}} {{$dimentions ?? ''}}</small>
        @if(isset($hint) && is_string($hint))
        <small class="text-secondary"> ({{ $hint }})</small>
        @endif
    </div>
@endif

@if(isset($multiple) && $multiple)
<div class="form-group ">
    <label for="image">{{ __($title) }}</label>
    <div class="media-upload-btn-wrapper">
        <div class="img-wrap">
            @if (isset($galleryImages))
                @php $gallery_images = json_decode($galleryImages, true); @endphp
                @if($gallery_images && $gallery_images != 'null')
                    @foreach($gallery_images as $gl_img)
                        @php $work_section_img = get_attachment_image_by_id($gl_img, null, true); @endphp
                        @if (!empty($work_section_img))
                            <div class="attachment-preview">
                                <div class="thumbnail">
                                    <div class="centered">
                                        <img class="avatar user-thumb"
                                            src="{{$work_section_img['img_url']}}" alt="">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endif
        </div>

        <input @php $required @endphp type="hidden" name="{{ $name }}">
        <button type="button" class="btn btn-info media_upload_form_btn popup-modal"
                data-btntitle="{{__('Select Image')}}"
                data-modaltitle="{{__('Upload Image')}}"
                data-bs-toggle="modal"
                data-mulitple="true"
                data-bs-target="#media_upload_modal">
            {{__('Upload Image')}}
        </button>
    </div>
</div>
@endif
