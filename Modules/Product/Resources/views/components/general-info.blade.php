@php
    if (!isset($product)) {
        $product = null;
    }
@endphp

<div class="general-info-wrapper dashboard__card">
    <div class="dashboard__card__header">
        <h4 class="dashboard__card__title"> {{ __('General Information') }} </h4>
    </div>
    <div class="dashboard__card__body custom__form general-info-form">
        <form action="#">
            <div class="row g-3 mt-2">
                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Name') }} </label>
                        <input type="text" class="form--control radius-10" id="product-name"
                            value="{{ $product?->name ?? '' }}" name="name"
                            placeholder="{{ __('Write product Name...') }}">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Slug') }} </label>
                        <input type="text" class="form--control radius-10" id="product-slug"
                            value="{{ $product?->slug ?? '' }}" name="slug"
                            placeholder="{{ __('Write product slug...') }}">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Summary') }} </label>
                        <textarea style="height: 120px" class="form--control form--message  radius-10" name="summery"
                            placeholder="{{ __('Write product Summery...') }}">{{ $product?->summary ?? '' }}</textarea>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Description') }} </label>
                        <textarea class="form--control summernote radius-10" name="description" placeholder="{{ __('Type Description') }}">{!! $product?->description !!}</textarea>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Height (cm)') }} </label>
                        <input type="text" class="form--control radius-10"
                            value="{{ $product?->height ?? '' }}" name="height">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Width (cm)') }} </label>
                        <input type="text" class="form--control radius-10"
                            value="{{ $product?->width ?? '' }}" name="width">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Weight') }} </label>
                        <input type="text" class="form--control radius-10"
                            value="{{ $product?->weight ?? '' }}" name="weight">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <label class="dashboard-label color-light mb-2"> {{ __('Length (cm)') }} </label>
                        <input type="text" class="form--control radius-10"
                            value="{{ $product?->length ?? '' }}" name="length">
                    </div>
                </div>

                

                <div class="col-sm-12">
                    <div class="dashboard-input">
                        <!-- <label class="dashboard-label color-light mb-2"> {{ __('Brand') }} </label>
                        <div class="nice-select-two">
                            <select name="brand" class="form-control" id="brand_id">
                                <option value="">{{ __('Select brand') }}</option>
                                @foreach ($brands as $item)
                                    <option {{ $item->id == $product?->brand_id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div> -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
