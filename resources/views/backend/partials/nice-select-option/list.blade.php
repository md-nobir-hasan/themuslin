@foreach($data as $item)
    <li data-value="{{ $item->id }}" class="option">{{ $item->name }}</li>
@endforeach