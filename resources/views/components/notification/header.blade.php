@php
    $type = $type ?? 'admin';
@endphp

<div class="single-icon-flex">
    @if(auth('admin')->check())
        <div class="single-icon notifications-parent">
            <a class="btn btn-outline-danger site-health-btn btn-icon-text" href="{{ route('homepage') }}">
                <i class="las la-eye"></i> <span class="d-none d-sm-inline-block">{{ __("Visit Site") }}</span>
            </a>
        </div>
    @endif

    @if(auth('admin')->check())
        
    @endif
    
</div>
