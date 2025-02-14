@php
    if (!isset($selectedcat)) {
        $selectedcat = null;
    }
    
    if (!isset($selectedSubCat)) {
        $selectedSubCat = null;
    }
    
    if (!isset($selectedcat)) {
        $selectedcat = null;
    }
    
    $categories = !isset($categories) ? [] : $categories;
    $sub_categories = !isset($subCategories) ? [] : $subCategories;
    $child_categories = !isset($childCategories) ? [] : $childCategories;
@endphp

<div class="dashboard-attr-add-wrapper">
    <div class="dashboard-input mt-4">
        <label class="dashboard-label color-light mb-2">{{ __('Category') }}</label>
        <div class="nice-select-two">
            <select name="category_id" id="category" class="form-control">
                <option value="">{{ __('Select Category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $selectedcat === $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="dashboard-input mt-4">
        <label class="dashboard-label color-light mb-2">{{ __('Sub Category') }}</label>
        <div class="nice-select-two">
            <select class="form-control" name="sub_category" id="sub_category">
                <option value="">{{ __('Select Sub Category') }}</option>
                @foreach ($sub_categories as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $selectedSubCat ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="dashboard-input mt-4">
        <label class="dashboard-label color-light mb-2">{{ __('Child Category') }} </label>
        <div class="nice-select-two">
            <select name="child_category[]" multiple id="child_category" class="form-control">
                <option value="">{{ __('Select Child Category') }}</option>
                @foreach ($child_categories as $item)
                    <option value="{{ $item->id }}"
                        {{ in_array($item->id, $selectedChildCat) ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
