@foreach($sub_category as $item)
    <li data-value="{{ $item->id }}" class="option">{{ $item->name }}</li>
@endforeach