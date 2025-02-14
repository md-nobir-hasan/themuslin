<?php

namespace Modules\TaxModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\TaxModule\Entities\TaxClass;
use Modules\TaxModule\Entities\TaxClassOption;
use Modules\TaxModule\Http\Requests\StoreTaxOptionPostRequest;

class AdminTaxController extends Controller
{
    public function settings()
    {
        $taxClasses = TaxClass::select("id","name")->get();
        return view("taxmodule::backend.settings", compact('taxClasses'));
    }

    public function handleSettings(Request $request){
        update_static_option("prices_include_tax", $request->prices_include_tax ?? "");
        update_static_option("calculate_tax_based_on", $request->calculate_tax_based_on ?? "");
        update_static_option("shipping_tax_class", $request->shipping_tax_class ?? "");
        update_static_option("tax_round_at_subtotal", $request->tax_round_at_subtotal ?? "");
        update_static_option("display_price_in_the_shop", $request->display_price_in_the_shop ?? "");
        update_static_option("display_tax_total", $request->display_tax_total ?? "");
        update_static_option("tax_system", $request->tax_system ?? "");

        return back()->with([
            "msg" => __("Tax settings updated successfully."),
            "type" => "success"
        ]);
    }

    public function taxClass()
    {
        $classes = TaxClass::withCount("classOption")->get();

        return view("taxmodule::backend.class.index", compact('classes'));
    }

    public function handlePostTaxClass(Request $request){
        // validate tax class hare
        // after validation store data
        $data = $request->validate(["name" => "required|unique:tax_classes"]);
        $taxClass = TaxClass::create($data);

        return back()->with([
            'msg' => $taxClass ? __('Successfully created tax class') : __('Something went wrong. failed to create tax class'),
            'type' => $taxClass ? 'success' : 'danger'
        ]);
    }

    public function handleTaxClass(Request $request){
        $data = $request->validate(["name" => "required|unique:tax_classes,id," . $request->id,"id" => "required|exists:tax_classes"]);

        $taxClass = TaxClass::where("id", $data['id'])->update($data);

        return back()->with([
            'msg' => $taxClass ? __('Successfully updated tax class') : __('Something went wrong. failed to update tax class'),
            'type' => $taxClass ? 'success' : 'danger'
        ]);
    }

    public function deleteTaxClass(Request $request){
        // hare first need to check class option are have or not if have then delete all first and if not then delete only tax class
        TaxClassOption::where("class_id", $request->id)->delete();
        TaxClass::where("id", $request->id)->delete();

        return response()->json([
            'msg' => __("Successfully deleted tax class"),
            'type' => "success",
            'success' => true
        ]);
    }

    public function taxClassOption($id)
    {
        $taxClass = TaxClass::with(["classOption","classOption.states","classOption.cities"])->where("id", $id)->first();
        $countries = Country::select("id","name")->get();

        return view("taxmodule::backend.class-option.index", compact('taxClass','countries'));
    }

    public function handleTaxClassOption(StoreTaxOptionPostRequest $request, $id){
        try {
            \DB::beginTransaction();

            $taxClassOptions = $this->prepareTaxClassOptions($request->validated(), $id);
            // first delete all tax class option
            TaxClassOption::where("class_id", $id)->delete();
            // now store bulk items into tax class options
            TaxClassOption::insert($taxClassOptions);

            \DB::commit();

            return back()->with([
                "msg" => __("Successfully stored tax class options"),
                "type" => "success"
            ]);
        }catch (\Exception $e){
            return back()->with([
                "msg" => __("Something went wrong. Failed to store tax class options"),
                "message" => $e->getMessage(),
                "type" => "danger"
            ]);
        }
    }

    private function prepareTaxClassOptions($request, $id){
        $arr = [];

        foreach($request["tax_name"] as $key => $value){
            $arr[] = [
                'class_id' => $id,
                'tax_name' => $value,
                'country_id' => $request['country_id'][$key],
                'state_id' => $request['state_id'][$key],
                'city_id' => $request['city_id'][$key],
                'postal_code' => $request['postal_code'][$key],
                'priority' => $request['priority'][$key],
                'is_compound' => $request['is_compound'][$key] ?? 0,
                'is_shipping' => $request['is_shipping'][$key] ?? 0,
                'rate' => $request['rate'][$key],
            ];
        }

        return $arr;
    }

    public function getCountryStateInfo(Request $request){
        $request->validate(["id" => "required"]);

        $states = State::select('id', 'name')->where('country_id', $request->id)->get();
        $html = "<option value=''>".__('Select State')."</option>";
        foreach($states as $state){
            $html .= "<option value='". $state->id ."'>" . $state->name . "</option>";
        }

        return $html;
    }

    public function getCountryCityInfo(Request $request){
        $request->validate(["id" => "required"]);

        $cities = City::select('id', 'name')->where('state_id', $request->id)->get();
        $html = "<option value=''>". __("Select City") ."</option>";
        foreach($cities as $city){
            $html .= "<option value='". $city->id ."'>" . $city->name . "</option>";
        }

        return $html;
    }
}
