@php
    $classOption = $classOption ?? new stdClass();
@endphp

<tr>
    <td>
        <input value="{{ $classOption->tax ?? '' }}" type="checkbox" class="tax-option-row-check"
            id="tax-option-row-check" />
    </td>
    <td>
        <div class="table__input">
            <input class="form-control" value="{{ $classOption->tax_name ?? '' }}" type="text" name="tax_name[]"
                required>
        </div>
    </td>
    <td>
        <div class="table__input">
            <select class="table__input__select" name="country_id[]" id="country_id">
                <option value="">{{ __('Select Country') }}</option>

                @foreach ($countries as $country)
                    <option
                        @if (!empty($classOption)) {{ $country->id == ($classOption->country_id ?? '') ? 'selected' : '' }} @endif
                        value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
    </td>
    <td>
        <div class="table__input">
            <select class="table__input__select" name="state_id[]" id="state_id">
                <option value="">{{ __('Select State') }}</option>
                @foreach ($classOption?->states ?? [] as $state)
                    <option {{ $state->id == $classOption->state_id ? 'selected' : '' }} value="{{ $state->id ?? '' }}">
                        {{ $state->name }}</option>
                @endforeach
            </select>
        </div>
    </td>
    <td>
        <div class="table__input">
            <select class="table__input__select" name="city_id[]" id="city_id">
                <option value="">{{ __('Select City') }}</option>
                @foreach ($classOption?->cities ?? [] as $city)
                    <option {{ $city->id == $classOption->city_id ? 'selected' : '' }} value="{{ $city->id ?? '' }}">
                        {{ $city->name }}</option>
                @endforeach
            </select>
        </div>
    </td>
    <td>
        <div class="table__input">
            <input class="form-control" value="{{ $classOption->postal_code ?? '' }}" type="text"
                name="postal_code[]" id="postal_code">
        </div>
    </td>
    <td>
        <div class="table__input">
            <input class="form-control" value="{{ $classOption->rate ?? '0.00' }}" type="number" name="rate[]"
                step="0.01" id="rate" required pattern="[A-Za-z0-9]{5}">
        </div>
    </td>
    <td class="table__input d-none">
        <input class="form-checkbox" {{ ($classOption->is_compound ?? '') == 1 ? 'checked' : '' }} type="checkbox"
            name="is_compound[]" id="compound" value="1" />
    </td>
    <td>
        <div class="table__input">
            <input class="form-checkbox" {{ ($classOption->is_shipping ?? '') == 1 ? 'checked' : '' }} type="checkbox"
                name="is_shipping[]" id="shipping" value="1" />
        </div>
    </td>
    <td>
        <div class="table__input">
            <input class="form-control" value="{{ $classOption->priority ?? '' }}" type="number" name="priority[]"
                id="priority" required>
        </div>
    </td>
</tr>
