@if(isset($zoneCountry))
    <tr>
        <td>
            <select class="form-control" name="country[]" id="country_select">
                <option value="">{{ __("Select a country") }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ $zoneCountry?->id == $country->id ? "selected" : "" }}>{{ $country->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-control select-two select-two-{{ $rand }}" name="states[{{ $zoneCountry?->id ?? "" }}][]" multiple id="states_select">
                <option value="">{{ __("Select a country first") }}</option>
                @if(isset($zoneCountry?->states))
                    @foreach($zoneCountry?->states ?? [] as $state)
                        <option value="{{ $state->id }}"  {{ in_array($state?->id, $zoneCountry->zoneStates->pluck("id")->toArray()) && !empty($zoneCountry) ? "selected" : "" }}>{{ $state->name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td>
            <button type="button" id="shipping_zone_minus_btn" class="btn btn-danger btn-sm">
                <i class="las la-minus"></i>
            </button>
            <button type="button" id="shipping_zone_plus_btn" data-select-two="{{$rand}}" class="btn btn-info btn-sm">
                <i class="las la-plus"></i>
            </button>
        </td>
    </tr>
@else
    <tr>
        <td>
            <select class="form-control" name="country[]" id="country_select">
                <option value="">{{ __("Select a country") }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-control select-two select-two-{{ $rand }}" name="states[][]" multiple id="states_select">
                <option value="">{{ __("Select a country first") }}</option>
            </select>
        </td>
        <td>
            <button type="button" id="shipping_zone_minus_btn" class="btn btn-danger btn-sm">
                <i class="las la-minus"></i>
            </button>
            <button type="button" id="shipping_zone_plus_btn" data-select-two="{{$rand}}" class="btn btn-info btn-sm">
                <i class="las la-plus"></i>
            </button>
        </td>
    </tr>
@endif