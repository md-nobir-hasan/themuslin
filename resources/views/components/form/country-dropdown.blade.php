<div class="single-input">
    <label class="label-title">{{ $title }}</label>
    <select name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="form-control country_select2">
        <option value="">{{ __('Select Country') }}</option>
        @foreach($all_countries = \Modules\CountryManage\Entities\Country::all_countries() as $country)
            <option value="{{ $country->id }}" @if(Auth::guard('web')->check() && $country->id == Auth::guard('web')->user()->country_id) selected @endif>{{ $country->country }}</option>
        @endforeach
    </select>
    <span class="country_info"></span>
</div>
