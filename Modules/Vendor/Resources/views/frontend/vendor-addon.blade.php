<div class="row g-4 mt-4">
    @forelse($vendors as $vendor)
        <x-vendor::style-one :vendor="$vendor" />
    @empty
        <h3 class="text-warning text-center">{{ __("No vendor found") }}</h3>
    @endforelse
</div>