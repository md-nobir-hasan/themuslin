<?php

namespace Modules\MobileApp\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Page;

class MobileController extends Controller
{
    public function termsAndCondition(){
        $selected_page = get_static_option("checkout_page_terms_link_url");

        $page = Page::where('slug', $selected_page)->select( "title","content")->first();
        return response()->json($page);
    }

    public function privacyPolicy(){
        $selected_page = get_static_option("mobile_privacy_and_policy");

        $page = Page::where('id', $selected_page)->select( "title","content")->first();
        return response()->json($page);
    }

    public function site_currency_symbol(){

        $is_rtl_on_or_not = get_user_lang_direction() == 1 ?? false;

        return response()->json([
            "symbol" => site_currency_symbol(),
            "currencyPosition" => get_static_option('site_currency_symbol_position'),
            "rtl" => $is_rtl_on_or_not,
            "currency_code" => get_static_option("site_global_currency"),
            "tax_type" => get_static_option("prices_include_tax") == 'yes' && get_static_option("display_price_in_the_shop") == 'including' && get_static_option("tax_system") == 'advance_tax_system' ? __("Inclusive Tax") : null,
            "tax_system" => get_static_option("tax_system")
        ]);
    }
}
