<div class="single-input mt-3">
    <label class="label-title">{{ $title }}</label>
    <select name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="{{ $class ?? '' }}">
        <option value="">{{ __('Select Category') }}</option>
        @foreach($allCategories = \Modules\Service\Entities\Category::all_categories() as $data)
            <option value="{{ $data->id }}">{{ $data->category }}</option>
        @endforeach
    </select>
</div>
