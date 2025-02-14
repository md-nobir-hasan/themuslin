@foreach($sub_category as $item)
    <option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach