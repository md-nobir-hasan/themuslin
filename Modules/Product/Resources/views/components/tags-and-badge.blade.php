@php
    if (!isset($tag)) {
        $tag = null;
        $tag_name = '';
    } else {
        $tag_name_arr = $tag->pluck('tag_name')?->toArray();
        $tag_name = implode(',', $tag_name_arr ?? []);
    }
    
    if (!isset($singlebadge)) {
        $singlebadge = null;
    }
@endphp

<div class="general-info-wrapper dashboard__card">
    <div class="dashboard__card__header">
        <h4 class="dashboard__card__title"> {{ __('Product Tags and Badge') }} </h4>
    </div>
    <div class="dashboard__card__body custom__form">
        <div class="row g-3 mt-2">
            <div class="col-sm-12">
                <div class="dashboard-input">
                    <label class="dashboard-label"> {{ __('Tags') }} </label>
                    <input type="text" name="tags" class="form-control tags_input radius-10" data-role="tagsinput"
                        value="{{ $tag_name }}">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="dashboard-input">
                    <label class="dashboard-label"> {{ __('Labels') }} </label>
                    <div class="d-flex flex-wrap gap-2 justify-content-start">
                        <input type="hidden" name="badge_id" value="{{ $singlebadge }}" id="badge_id_input" />
                        @foreach ($badges as $badge)
                            <div class="badge-item d-flex {{ $badge->id === $singlebadge ? 'active' : '' }}"
                                data-badge-id="{{ $badge->id }}">
                                <div class="icon">
                                    {!! App\Http\Services\Media::render_image($badge->badgeImage, size: 'thumb') !!}
                                </div>
                                <div class="content">
                                    <h6 class="title">{{ $badge->name }}</h6>
                                    <span
                                        class="badge badge-{{ $badge->type ? 'success bg-success' : 'warning bg-warning' }}">{{ $badge->type ? __('Permanent') : __('Temporary') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
