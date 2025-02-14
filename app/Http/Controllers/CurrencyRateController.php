<?php

namespace App\Http\Controllers;

use App\CurrencyRate;
use App\Http\Requests\CurrencyRequest;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index(){
        $n['currency_rate'] = CurrencyRate::where('currency_code','USD')->first();
        return view('backend.currency-rate.index',$n);
    }

    public function update(CurrencyRequest $request){
        $data = $request->validated();
        $currency_rate = CurrencyRate::where('currency_code',$data['currency_code'])->first();
        if($currency_rate){
            $currency_rate->rate = $data['rate'];
            $currency_rate->save();
            return back()->with(['type'=>'success','msg'=>__('Currency rate updated successfully')]);
        }else{
            CurrencyRate::create([
                'currency_code' => $data['currency_code'],
                'rate' => $data['rate'],
            ]);
            return back()->with(['type'=>'success','msg'=>__('Currency rate updated successfully')]);
        }
    }
}
