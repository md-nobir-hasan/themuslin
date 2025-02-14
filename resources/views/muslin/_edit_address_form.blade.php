
<form action="{{ route('edit-address', $data->id) }}" method="post" > 
    @csrf 
    <input type="hidden" name="id" value="{{ $data->id }}">

    <div class="form-row">
        <div class="form-group col-sm-4">
            <label>Name*</label>
            <input type="text" class="form-control" placeholder="Name" name="name" value="{{ $data->name }}"  required="" />
        </div>
        <div class="form-group col-sm-4">
            <label>Phone Number* </label>
            <input type="number" class="form-control" placeholder="Phone Number" name="phone" value="{{ $data->phone }}"  required="" />
        </div>
        <div class="form-group col-sm-4">
            <label>Email</label>
            <input type="email" class="form-control" placeholder="Email Address" style="width: 100%" name="email" value="{{ $data->email }}"/>
        </div>
    </div>

    <div class="form-row">

        <div class="form-group col-sm-4">
            <label>Country</label>
            <select class="form-control country" required="" style="width: 100%" name="country_id">
                <option>Select</option>
                @foreach ($all_country as $key => $country)
                    <option value="{{ $key }}" {{ old('country_id', $data->country_id) == $key ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-4">
            <label>State*</label>
            <select class="form-control state" required="" style="width: 100%" name="state_id">
                <option>Select</option>
                @foreach ($all_state as $key => $state)
                    <option value="{{ $key }}" {{ old('state_id', $data->state_id) == $key ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
                
            </select>
        </div>

        <div class="form-group col-sm-4">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" placeholder="City" name="city" value="{{$data->city}}" required="" />
        </div>
        
    </div>

    <div class="form-row">

        <div class="form-group col-sm-8">
            <label for="address">Address </label>
            <input type="text" class="form-control" placeholder="Address*" name="address" value="{{$data->address}}" required=""  />
        </div>
        

        <div class="form-group col-sm-4">
            <label for="zipcode">Zipcode</label>
            <input type="number" class="form-control" placeholder="Zipcode" name="zip_code" value="{{$data->zip_code}}"  />
        </div>

        <div class="form-group col-sm-5">
            <div class="content-area__left__top__check checkboxAddress">
                <div class="">
                    <input type="checkbox" id="checkboxAddress" class="hidden-checkbox" name="default_address" {{ $data->default_shipping_status == 1 ? 'checked' : '' }} >
                    <label for="checkboxAddress" class="check-box">
                        <svg width="15" height="11" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9331 0.846691C14.1246 0.647711 14.1185 0.331187 13.9195 0.139715C13.7205 -0.0517568 13.404 -0.0456702 13.2126 0.15331L4.85767 8.83581L0.860286 4.68168C0.668814 4.4827 0.35229 4.47661 0.15331 4.66808C-0.0456702 4.85955 -0.0517568 5.17608 0.139715 5.37506L4.49726 9.90347C4.558 9.9666 4.63133 10.0103 4.70949 10.0345C4.8843 10.0886 5.0825 10.0444 5.21804 9.90352L13.9331 0.846691Z" fill="white"></path>
                        </svg>
                    </label>
                </div>
                <p>Set as default address</p>
            </div>       
        </div>

    </div>


    <button type="submit" class="btn-submit">
        <span>Save Address</span>
    </button>
</form>
