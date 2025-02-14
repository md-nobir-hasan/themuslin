@php
    if (!isset($metaData)) {
        $metaData = null;
    }
@endphp

<h5>{{ __("Product Meta") }}</h5>
<div class="meta-body-wrapper mt-3">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-meta-info-tab" data-bs-toggle="tab"
                data-bs-target="#general-meta-info" type="button" role="tab" aria-controls="home"
                aria-selected="true">
                {{ __('General Meta Info') }}</button>
        </li>
        <!-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="facebook-meta-tab" data-bs-toggle="tab" data-bs-target="#facebook-meta"
                type="button" role="tab" aria-controls="facebook-meta" aria-selected="false">
                {{ __('Facebook meta') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="twitter-meta-tab" data-bs-toggle="tab" data-bs-target="#twitter-meta"
                type="button" role="tab" aria-controls="twitter-meta" aria-selected="false">
                {{ __('Twitter meta') }}</button>
        </li> -->
    </ul>
    <div class="tab-content" id="myTabContent">
        
        <div class="tab-pane py-4 fade show active" id="general-meta-info" role="tabpanel"
            aria-labelledby="general-meta-info-tab">
            {{-- Bokki Cokki (Sohan , Sujon , Saiful ) --}}
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('General Info') }}</h4>
                </div>
                <div class="dashboard__card__body custom__form mt-4">
                    <div class="form-group dashboard-input">
                        <label for="general-title">{{ __('Title') }}</label>
                        <input type="text" id="general-title" value="{{ $metaData?->meta_title }}"
                            data-role="tagsinput" class="form--control radius-10 tags_input" name="general_title"
                            placeholder="{{ __('General info title') }}">
                    </div>
                    <div class="form-group">
                        <label for="general-description">{{ __('Description') }}</label>
                        <textarea type="text" id="general-description" name="general_description" class="form--control radius-10 py-2"
                            rows="6" placeholder="{{ __('General info description') }}">{{ $metaData?->meta_description }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="tab-pane py-4 fade" id="facebook-meta" role="tabpanel" aria-labelledby="facebook-meta-tab">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Facebook Info') }}</h4>
                </div>
                <div class="dashboard__card__body custom__form mt-4">
                    <div class="form-group dashboard-input">
                        <label for="facebook-title">{{ __('Title') }}</label>
                        <input type="text" id="facebook-title" name="facebook_title"
                            value="{{ $metaData?->facebook_meta_tags }}" data-role="tagsinput"
                            class="form--control radius-10 tags_input" placeholder="{{ __('General info title') }}">
                    </div>
                    <div class="form-group">
                        <label for="facebook-description">{{ __('Description') }}</label>
                        <textarea type="text" id="facebook-description" name="facebook_description" class="form--control radius-10 py-2"
                            rows="6" placeholder="{{ __('General info description') }}">{{ $metaData?->facebook_meta_description }}</textarea>
                    </div>
                    <x-media.media-upload :old_image="$metaData?->facebookImage" :title="__('General Info Meta Image')" :name="'facebook_meta_image'" :dimentions="'200x200'" />
                </div>
            </div>
        </div>

        <div class="tab-pane py-4 fade" id="twitter-meta" role="tabpanel" aria-labelledby="twitter-meta-tab">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Twitter Info') }}</h4>
                </div>
                <div class="dashboard__card__body custom__form mt-4">
                    <div class="form-group dashboard-input">
                        <label for="general-title">{{ __('Title') }}</label>
                        <input type="text" id="twitter-title" value="{{ $metaData?->twitter_meta_tags }}"
                            name="twitter_title" data-role="tagsinput" class="form--control radius-10 tags_input"
                            placeholder="{{ __('General info title') }}">
                    </div>
                    <div class="form-group">
                        <label for="general-description">{{ __('Description') }}</label>
                        <textarea type="text" id="twitter-description" name="twitter_description" class="form--control radius-10 py-2"
                            rows="6" placeholder="{{ __('General info description') }}">{{ $metaData?->twitter_meta_description }}</textarea>
                    </div>
                    <x-media.media-upload :old_image="$metaData?->twitterImage" :title="__('General Info Meta Image')" :name="'twitter_meta_image'" :dimentions="'200x200'" />
                </div>
            </div>
        </div> -->

    </div>
</div>
