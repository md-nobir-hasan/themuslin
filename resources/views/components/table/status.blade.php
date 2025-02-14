
<div class="status-dropdown">
    <button value="{{ $statuses?->where("id",$statusId ?? 1)?->first()?->name }}" data-id="{{ $id ?? 0 }}" class="btn dropdown-toggle add-dropdown-text status-dropdown-button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $statuses?->where("id",$statusId ?? 0)?->first()?->name }}</button>
    <ul class="dropdown-menu">
        @foreach($statuses as $status)
            <li class="single-item" data-value="{{ $status->name }}" data-status-id="{{ $status->id }}" data-id="{{ $id }}">
                <a class="dropdown-item" href="#1">{{ $status->name }}</a>
            </li>
        @endforeach
    </ul>
</div>