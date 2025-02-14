<option value="">Select Sub Categories</option>

@foreach($data as $item)
    <option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach
