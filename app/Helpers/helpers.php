<?php

use App\Helpers\LanguageHelper;
use App\Http\Services\Media;
use App\Http\Services\NotificationService;
use App\Language;
use App\MenuBuilder\MenuBuilderAdminRender;
use App\MenuBuilder\MenuBuilderFrontendRender;
use App\PaymentGateway;
use App\StaticOption;
use App\MediaUpload;
use App\Page;
use App\Blog;
use App\CurrencyRate;
use Modules\CountryManage\Entities\Country;

use App\Widgets;
use App\WidgetsBuilder\WidgetBuilderSetup;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignProduct;
use Modules\Campaign\Entities\CampaignSoldProduct;
use App\PaymentGateway\PaymentGatewaySetup;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Attributes\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductCategory;
use App\Shipping\ShippingMethod;
use App\Shipping\ZoneRegion;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;
use Modules\TaxModule\Services\CalculateTaxServices;
use Stevebauman\Location\Facades\Location;

function getAllExternalMenu(): array
{
    return (new \App\Helpers\ModuleMetaData())->getAllExternalMenu();
}
function active_menu($url)
{
    return $url == request()->path() ? 'active' : '';
}

function active_menu_frontend($url)
{
    return $url == request()->path() ? 'current-menu-item' : '';
}

function check_image_extension($file)
{
    $extension = strtolower($file->getClientOriginalExtension());
    if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension = 'gif') {
        return false;
    }
    return true;
}

function render_image_markup_by_attachment_id($id, $class = null, $size = 'full', $isLazy = false, $isAjax = false)
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        if ($isLazy) {
            $class_list = "class=";
            $class_list .= !empty($class) ? $class . ' lazy"' : ' lazy';
            $data_ajax = $isAjax ? ' data-loader="ajax" ' : '';
            $output .= '<img data-src="'.$image_details['img_url'].'" ' . $class_list . ' ' . $data_ajax . ' />';
        } else {
            $class_list = !empty($class) ? 'class="' . $class . '"' : '';
            $output = '<img src="' . $image_details['img_url'] . '" ' . $class_list . ' alt="' . $image_details['img_alt'] . '"/>';
        }
    }

    return $output;
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

function sendEmail($to, $name, $subject, $message, $from = '')
{
    $template = get_static_option('site_global_email_template');
    $from = get_static_option('site_global_email');

    $headers = "From: " . $from . " \r\n";
    $headers .= "Reply-To: <$from> \r\n";
    $headers .= "Return-Path: " . ($from) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "X-Priority: 2\nX-MSmail-Priority: high";
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

    $mm = str_replace("@username", $name, $template);
    $message = str_replace("@message", $message, $mm);
    $message = str_replace("@company", get_static_option('site_title'), $message);

    if (mail($to, $subject, $message, $headers)) {
        return true;
    }
}

function sendPlanEmail($to, $name, $subject, $message, $from)
{

    $headers = "From: " . $from . " \r\n";
    $headers .= "Reply-To: <$from> \r\n";
    $headers .= "Return-Path: " . ($from) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "X-Priority: 2\nX-MSmail-Priority: high";
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    }
}


function set_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    }
    return false;
}

function get_static_option($key, $default = null)
{
    $option_name = $key;
    $value = \Illuminate\Support\Facades\Cache::remember($option_name, 3600, function () use ($option_name) {
        try {
            return StaticOption::where('option_name', $option_name)->first();
        } catch (\Exception $e) {
            return null;
        }
    });

    return !empty($value) ? $value->option_value : $default;
}

function get_default_language()
{
    $defaultLang = Language::where('default', 1)->first();
    return $defaultLang->slug;
}

function update_static_option($key, $value) : bool
{
    $option = StaticOption::where('option_name', $key)->first();
    if (!$option) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    }else{
        $option->update([
            'option_name' => $key,
            'option_value' => $value
        ]);
        \Illuminate\Support\Facades\Cache::forget($key);
        return true;
    }
}

function delete_static_option($key)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::where('option_name', $key)->delete();
        return true;
    }
    return false;
}

function single_post_share($url, $title, $img_url) // idea given by sharifur rahman and implemented by md zahid
{
    $output = '';
    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);

    $site_title = get_static_option('site_title');

    //all social share link generate
    $facebook_share_link = "https://www.facebook.com/sharer/sharer.php?u=$encoded_url";
    $twitter_share_link = "https://twitter.com/intent/tweet?text=$post_title&amp;url=$encoded_url&amp;via=$site_title";
    $linkedin_share_link = "https://www.linkedin.com/shareArticle?mini=true&url=$encoded_url&amp;title=$post_title";
    $pinterest_share_link = "https://pinterest.com/pin/create/button/?url=$encoded_url&amp;media=$img_url&amp;description=$post_title";
    $whatsapp_share_link = "https://api.whatsapp.com/send?text=*$post_title $encoded_url";
    $reddit_share_link = "https://reddit.com/submit?url=$encoded_url&title=$post_title";

    $output .= '<li class="list"><a class="social-icon" href="' . $facebook_share_link . '"><i class="lab la-facebook-f icon"></i></a></li>';
    $output .= '<li class="list"><a class="social-icon" href="' . $twitter_share_link . '"><i class="lab la-twitter icon"></i></a></li>';
    $output .= '<li class="list"><a class="social-icon" href="' . $linkedin_share_link . '"><i class="lab la-linkedin-in icon"></i></a></li>';
    $output .= '<li class="list"><a class="social-icon" href="' . $pinterest_share_link . '"><i class="lab la-pinterest-p icon"></i></a></li>';
    $output .= '<li class="list"><a class="social-icon" href="' . $whatsapp_share_link . '"><i class="lab la-whatsapp icon"></i></a></li>';
    $output .= '<li class="list"><a class="social-icon" href="' . $reddit_share_link . '"><i class="lab la-reddit icon"></i></a></li>';

    return $output;
}


function load_google_fonts()
{
    //google fonts link;
    $fonts_url = 'https://fonts.googleapis.com/css2?family=';
    //body fonts
    $body_font_family = get_static_option('body_font_family') ?? 'Open Sans';
    $heading_font_family = get_static_option('heading_font_family') ?? 'Montserrat';
    $extra_font_family = get_static_option('extra_font_family') ?? 'sans-serif';

    $load_body_font_family = str_replace(' ', '+', $body_font_family);
    $body_font_variant = get_static_option('body_font_variant');
    $body_font_variant_selected_arr = !empty($body_font_variant) ? unserialize($body_font_variant, ['class' => false]) : ['400'];
    $load_body_font_variant = is_array($body_font_variant_selected_arr) ? implode(';', $body_font_variant_selected_arr) : '400';

    $body_italic = '';
    preg_match('/1,/', $load_body_font_variant, $match);
    if (count($match) > 0) {
        $body_italic = 'ital,';
    } else {
        $load_body_font_variant = str_replace('0,', '', $load_body_font_variant);
    }

    $fonts_url .= $load_body_font_family . ':' . $body_italic . 'wght@' . $load_body_font_variant;
    $load_heading_font_family = str_replace(' ', '+', $heading_font_family);
    $heading_font_variant = get_static_option('heading_font_variant');
    $heading_font_variant_selected_arr = !empty($heading_font_variant) ? unserialize($heading_font_variant, ['class' => false]) : ['400'];
    $load_heading_font_variant = is_array($heading_font_variant_selected_arr) ? implode(';', $heading_font_variant_selected_arr) : '400';
    if (!empty(get_static_option('heading_font')) && $heading_font_family != $body_font_family) {
        $heading_italic = '';
        preg_match('/1,/', $load_heading_font_variant, $match);

        if (count($match) > 0) {
            $heading_italic = 'ital,';
        } else {
            $load_heading_font_variant = str_replace('0,', '', $load_heading_font_variant);
        }

        $fonts_url .= '&family=' . $load_heading_font_family . ':' . $heading_italic . 'wght@' . $load_heading_font_variant;
    }

    $fonts_url .= '&family=' . $load_body_font_family . ':' . $body_italic . 'wght@' . $load_body_font_variant;
    $load_extra_font_family = str_replace(' ', '+', $extra_font_family);
    $extra_font_variant = get_static_option('extra_font_variant');
    $extra_font_variant_selected_arr = !empty($extra_font_variant) ? unserialize($extra_font_variant, ['class' => false]) : ['400'];
    $load_extra_font_variant = is_array($extra_font_variant_selected_arr) ? implode(';', $extra_font_variant_selected_arr) : '400';

    if (!empty(get_static_option('extra_font')) && $extra_font_family != $body_font_family) {
        $extra_italic = '';
        preg_match('/1,/', $load_extra_font_variant, $match);

        if (count($match) > 0) {
            $extra_italic = 'ital,';
        } else {
            $load_extra_font_variant = str_replace('0,', '', $load_extra_font_variant);
        }

        $fonts_url .= '&family=' . $load_extra_font_family . ':' . $extra_italic . 'wght@' . $load_extra_font_variant;
    }

    return sprintf('<link rel="preconnect" href="https://fonts.gstatic.com"> <link href="%1$s&display=swap" rel="stylesheet">', $fonts_url);
}

function render_background_image_markup_by_attachment_id($id, $size = 'full', $isLazy = false) : string
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        if ($isLazy) {
            $output = 'data-src="' . $image_details['img_url'] . '"';
        } else {
            $output = 'style="background-image: url(' . $image_details['img_url'] . ');"';
        }
    }
    return $output;
}

function render_favicon_by_id($id)
{
    $site_favicon = get_attachment_image_by_id($id, "full", false);
    $output = '';
    if (!empty($site_favicon)) {
        $output .= '<link rel="icon" href="' . $site_favicon['img_url'] . '" type="image/png">';
    }
    return $output;
}

function get_attachment_image_by_id($id, $size = null, $default = false)
{
    $image_details = MediaUpload::find($id);
    $return_val = [];
    $image_url = '';

    if(optional($image_details)->path ?? false){
        if (file_exists('assets/uploads/media-uploader/' . optional($image_details)->path ?? '')) {
            $image_url = asset('assets/uploads/media-uploader/' . optional($image_details)->path);
        }
    }else{
        return "";
    }

    if (!empty($id) && !empty($image_details)) {
        switch ($size) {
            case "large":
                if (file_exists('assets/uploads/media-uploader/large-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/large-' . $image_details->path);
                }
                break;
            case "grid":
                if (file_exists('assets/uploads/media-uploader/grid-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/grid-' . $image_details->path);
                }
                break;
            case "thumb":
                if (file_exists('assets/uploads/media-uploader/thumb-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/thumb-' . $image_details->path);
                }
                break;
            case "product":
                if (file_exists('assets/uploads/media-uploader/product-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/product-' . $image_details->path);
                }
                break;
            default:
                if (file_exists('assets/uploads/media-uploader/' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/' . $image_details->path);
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $image_details->id;
        $return_val['path'] = $image_details->path;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $image_details->alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = asset('assets/uploads/no-image.png');
    }

    return $return_val;
}

function get_user_lang()
{
    return $lang = LanguageHelper::user_lang_slug();
}

function get_user_lang_direction()
{
    $default = \App\Language::where('default', 1)->first();
    $user_direction = \App\Language::where('slug', session()->get('lang'))->first();

    return !empty(session()->get('lang')) ? $user_direction->direction : $default->direction;
}

function filter_static_option_value(string $index, array $array = [], $default = '')
{
    return $array[$index] ?? $default;
}

function render_og_meta_image_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';
    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = ' <meta property="og:image" content="' . $image_details['img_url'] . '" />';
    }
    return $output;
}


function setEnvValue(array $values): bool
{

    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            // If key does not exist, add it
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }
    }

    $str = substr($str, 0, -1);
    if (!file_put_contents($envFile, $str)) return false;
    return true;
}

function get_language_by_slug($slug)
{
    $lang_details = \App\Language::where('slug', $slug)->first();
    return !empty($lang_details) ? $lang_details->name : '';
}

function getAllCurrency() : array
{
    return [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];
}

function site_currency_symbol($text = false)
{
    $all_currency = getAllCurrency();

    $symbol = '$';
    $global_currency = get_static_option('site_global_currency');

    foreach ($all_currency as $currency => $sym) {
        if ($global_currency == $currency) {
            $symbol = $text ? $currency : $sym;
            break;
        }
    }

    return $symbol;
}

function amount_with_currency_symbol($amount, $text = false): string
{
    if (is_null($amount) || empty($amount) || $amount == 0){
        return "";
    }

    $symbol = site_currency_symbol($text);
    $position = get_static_option('site_currency_symbol_position');

    if (empty($amount)) {
        $return_val = $symbol . $amount;
        if ($position == 'right') {
            $return_val = $amount . $symbol;
        }
    }

    $amount = number_format($amount);

    $return_val = $symbol . $amount;

    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }

    return $return_val;
}

function float_amount_with_currency_symbol($amount, $text = false)
{
    $symbol = site_currency_symbol($text);
    $position = get_static_option('site_currency_symbol_position');

    if (empty($amount)) {
        $return_val = $symbol . $amount;
        if ($position == 'right') {
            $return_val = $amount . $symbol;
        }
    }

    $amount = number_format((float)$amount, 2, '.', ',');

    $return_val = $symbol . $amount;

    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }

    return $return_val;
}

function get_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_footer_copyright');
    $footer_copyright_text = str_replace(array('{copy}', '{year}'), array('&copy;', date('Y')), $footer_copyright_text);
    return $footer_copyright_text;
}

function get_country_field($name, $id, $class)
{
    // $all_countries = \App\Helpers\CountryHelper::getAllCountries();
    $output = "<select style='height:50px;' name='$name' id='$id' class='$class'>";
    $output .= "<option value=''>" . __('Select Country') . "</option>";

    $all_countries = Country::where('status', 'publish')->get()->toArray();   
    $all_countries = array_column($all_countries, 'name', 'id');

    foreach ($all_countries as $key => $country) {
        $output .= "<option value='$key'>$country</option>";
    }

    $output .= "</select>";
    return $output;
}

function google_captcha_check($token)
{
    $captha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $captha_url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('secret' => get_static_option('site_google_captcha_v3_secret_key'), 'response' => $token)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    return $result;
}

function get_field_by_type($type, $name, $placeholder, $options = [], $requried = null, $mimes = null)
{
    $markup = '';
    $required_markup_html = 'required="required"';
    $name = htmlspecialchars(strip_tags($name));
    $type = htmlspecialchars(strip_tags($type));
    $placeholder = htmlspecialchars(strip_tags($placeholder));

    switch ($type) {
        case ('email'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="email" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('tel'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="tel" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('date'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="date" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('url'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="url" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('textarea'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group textarea"><textarea name="' . $name . '" id="' . $name . '" cols="30" rows="10" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></textarea></div>';
            break;
        case ('file'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $mimes_type_markup = str_replace('mimes:', __('Accept File Type:') . ' ', $mimes);
            $markup = ' <div class="form-group file"> <label for="' . $name . '">' . __($placeholder) . '</label> <input type="file" id="' . $name . '" name="' . $name . '" ' . $required_markup . ' class="form-control" > <span class="help-info">' . $mimes_type_markup . '</span></div>';
            break;
        case ('checkbox'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group checkbox">  <input type="checkbox" id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '> <label for="' . $name . '">' . __($placeholder) . '</label></div>';
            break;
        case ('select'):
            $option_markup = '';
            $required_markup = !empty($requried) ? $required_markup_html : '';
            foreach ($options as $opt) {
                $option_markup .= '<option value="' . Str::slug(htmlspecialchars(strip_tags($opt))) . '">' . htmlspecialchars(strip_tags($opt)) . '</option>';
            }
            $markup = ' <div class="form-group select"> <label for="' . $name . '">' . __($placeholder) . '</label> <select id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '>' . $option_markup . '</select></div>';
            break;
        default:
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="text" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
    }

    return $markup;
}

function render_form_field_for_frontend($form_content)
{
    if (empty($form_content)) {
        return;
    }
    $output = '';
    $form_fields = json_decode($form_content);
    $select_index = 0;
    $options = [];
    foreach ($form_fields->field_type as $key => $value) {
        if (!empty($value)) {
            if ($value == 'select') {
                $options = explode("\n", $form_fields->select_options[$select_index]);
            }
            $required = isset($form_fields->field_required->$key) ? $form_fields->field_required->$key : '';
            $mimes = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            $output .= get_field_by_type($value, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $options, $required, $mimes);
            if ($value == 'select') {
                $select_index++;
            };
        }
    }
    return $output;
}

//function render_payment_gateway_for_form($cash_on_delivery = false)
//{
//    return PaymentGatewaySetup::renderFrontendFormContent($cash_on_delivery);
//}

function render_payment_gateway_select()
{
    if (empty(get_static_option('site_payment_gateway'))) {
        return;
    }

    $all_gateway = PaymentGatewaySetup::gateway_list();
    $output = '';

    foreach ($all_gateway as $gateway) {
        $selected = (get_static_option('site_default_payment_gateway') == $gateway) ? 'selected' : '';
        if (!empty(get_static_option($gateway . '_gateway'))) {
            $output .= '<option value="' . $gateway . '" ' . $selected . '>' . ucfirst(str_replace('_', ' ', $gateway)) . '</option>';
        }
    }
    return $output;
}

function render_drag_drop_form_builder_markup($content = '')
{
    $output = '';

    $form_fields = json_decode($content);
    $output .= '<ul id="sortable" class="available-form-field main-fields">';
    if (!empty($form_fields)) {
        $select_index = 0;
        foreach ($form_fields->field_type as $key => $ftype) {
            $args = [];
            $required_field = '';
            if (property_exists($form_fields, 'field_required')) {
                $filed_requirement = (array)$form_fields->field_required;
                $required_field = !empty($filed_requirement[$key]) ? 'on' : '';
            }
            if ($ftype == 'select') {
                $args['select_option'] = isset($form_fields->select_options[$select_index]) ? $form_fields->select_options[$select_index] : '';
                $select_index++;
            }
            if ($ftype == 'file') {
                $args['mimes_type'] = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            }
            $output .= render_drag_drop_form_builder_field_markup($key, $ftype, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $required_field, $args);
        }
    } else {
        $output .= render_drag_drop_form_builder_field_markup('1', 'text', 'your-name', 'Your Name', '');
    }

    $output .= '</ul>';
    return $output;
}

function render_drag_drop_form_builder_field_markup($key, $type, $name, $placeholder, $required, $args = [])
{
    $required_check = !empty($required) ? 'checked' : '';
    $placeholder = htmlspecialchars(strip_tags($placeholder));
    $name = htmlspecialchars(strip_tags($name));
    $type = htmlspecialchars(strip_tags($type));
    $output = '<li class="ui-state-default">
                     <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="remove-fields">x</span>
                    <a data-bs-toggle="collapse" href="#fileds_collapse_' . $key . '" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        ' . ucfirst($type) . ': <span
                                class="placeholder-name">' . $placeholder . '</span>
                    </a>';
    $output .= '<div class="collapse" id="fileds_collapse_' . $key . '">
            <div class="card card-body margin-top-30">
                <input type="hidden" class="form-control" name="field_type[]"
                       value="' . $type . '">
                <div class="form-group">
                    <label>' . __('Name') . '</label>
                    <input type="text" class="form-control " name="field_name[]"
                           placeholder="' . __('enter field name') . '"
                           value="' . $name . '" >
                </div>
                <div class="form-group">
                    <label>' . __('Placeholder/Label') . '</label>
                    <input type="text" class="form-control field-placeholder"
                           name="field_placeholder[]" placeholder="' . __('enter field placeholder/label') . '"
                           value="' . $placeholder . '" >
                </div>
                <div class="form-group">
                    <label ><strong>' . __('Required') . '</strong></label>
                    <label class="switch">
                        <input type="checkbox" class="field-required" ' . $required_check . ' name="field_required[' . $key . ']">
                        <span class="slider-yes-no"></span>
                    </label>
                </div>';
    if ($type == 'select') {
        $output .= '<div class="form-group">
                        <label>' . __('Options') . '</label>
                            <textarea name="select_options[]" class="form-control max-height-120" cols="30" rows="10"
                                required>' . strip_tags($args['select_option']) . '</textarea>
                           <small>' . __('separate option by new line') . '</small>
                    </div>';
    }
    if ($type == 'file') {
        $output .= '<div class="form-group"><label>' . __('File Type') . '</label><select name="mimes_type[' . $key . ']" class="form-control mime-type">';
        $output .= '<option value="mimes:jpg,jpeg,png"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:jpg,jpeg,png') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:jpg,jpeg,png') . '</option>';

        $output .= '<option value="mimes:txt,pdf"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:txt,pdf') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:txt,pdf') . '</option>';

        $output .= '<option value="mimes:doc,docx"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:mimes:doc,docx') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:mimes:doc,docx') . '</option>';

        $output .= '</select></div>';
    }
    $output .= '</div></div></li>';

    return $output;
}

function is_paypal_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD'];
    return in_array($global_currency, $supported_currency);
}

function get_manual_payment_description($type = 'manual_payment')
{
    $payment_description = get_static_option('site_' . $type . '_description');
    $payment_description = str_replace(array('https://{url}', 'http://{url}'), array(url('/'), url('/')), $payment_description);
    return $payment_description;
}


function is_paytm_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['INR'];
    return in_array($global_currency, $supported_currency);
}

function is_razorpay_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['INR'];
    return in_array($global_currency, $supported_currency);
}

function is_mollie_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR'];
    return in_array($global_currency, $supported_currency);
}

function is_flutterwave_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'];
    return in_array($global_currency, $supported_currency);
}

function is_paystack_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['NGN', 'GHS'];
    return in_array($global_currency, $supported_currency);
}

function get_amount_in_usd($amount, $currency)
{
    $output = 0;
    $all_currency = getAllCurrency();
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'USD') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_usd_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}

function get_amount_in_inr($amount, $currency)
{
    $output = 0;
    $all_currency = getAllCurrency();
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'INR') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_inr_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}

function get_amount_in_ngn($amount, $currency)
{
    $output = 0;
    $all_currency = getAllCurrency();
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'NGN') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_ngn_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}

function check_currency_support_by_payment_gateway($gateway)
{
    $output = false;
    if ($gateway == 'paypal') {
        $output = is_paypal_supported_currency();
    } elseif ($gateway == 'paytm') {
        $output = is_paytm_supported_currency();
    } elseif ($gateway == 'mollie') {
        $output = is_mollie_supported_currency();
    } elseif ($gateway == 'stripe') {
        $output = true;
    } elseif ($gateway == 'razorpay') {
        $output = is_razorpay_supported_currency();
    } elseif ($gateway == 'flutterwave') {
        $output = is_flutterwave_supported_currency();
    } elseif ($gateway == 'paystack') {
        $output = is_paystack_supported_currency();
    } else {
        $output = true;
    }

    return $output;
}

function custom_number_format($amount)
{
    return number_format((float)$amount, 2, '.', '');
}

function get_charge_currency($gateway)
{
    $output = 'USD';
    if ($gateway == 'paypal') {
        $output = 'USD';
    } elseif ($gateway == 'paytm') {
        $output = 'INR';
    } elseif ($gateway == 'mollie') {
        $output = 'USD';
    } elseif ($gateway == 'razorpay') {
        $output = 'INR';
    } elseif ($gateway == 'flutterwave') {
        $output = 'USD';
    } elseif ($gateway == 'paystack') {
        $output = 'NGN';
    }

    return $output;
}

function get_charge_amount($amount, $gateway)
{
    $output = 0;
    if ($gateway == 'paypal') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'paytm') {
        $output = get_amount_in_inr($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'mollie') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'razorpay') {
        $output = get_amount_in_inr($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'flutterwave') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'paystack') {
        $output = get_amount_in_ngn($amount, get_static_option('site_global_currency'));
    }

    return $output;
}

function get_paypal_form_url()
{
    $output = 'https://secure.paypal.com/cgi-bin/webscr';
    $sandbox_enable = get_static_option('paypal_test_mode');
    if (!empty($sandbox_enable)) {
        $output = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }
    return $output;
}

function get_paytm_environment()
{
    $output = 'PROD';
    $sandbox_enable = get_static_option('paytm_test_mode');
    if (!empty($sandbox_enable)) {
        $output = 'TEST';
    }
    return $output;
}

function redirect_404_page()
{
    return view('frontend.pages.404');
}

function getVisIpAddr()
{

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function get_visitor_country()
{
    $return_val = 'NG';
    $ip = getVisIpAddr();
    $ipdat = @json_decode(file_get_contents(
        "http://www.geoplugin.net/json.gp?ip=" . $ip
    ));

    $ipdat = (array)$ipdat;
    $return_val = isset($ipdat['geoplugin_countryCode']) ? $ipdat['geoplugin_countryCode'] : $return_val;

    return $return_val;
}

function get_user_name_by_id($id)
{
    $user = \App\User::find($id);
    return $user;
}

function all_languages()
{
    return LanguageHelper::getAllLanguages();
}

function render_embed_google_map($address, $zoom = 10)
{
    if (empty($address)) {
        return;
    }
    printf(
        '<div class="elementor-custom-embed"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe></div>',
        rawurlencode($address),
        $zoom,
        $address
    );
}

function get_mege_menu_item_url($type, $slug, $id)
{
    $return_val = '';
    switch ($type) {
        case ('service_mega_menu'):
            $return_val = route('frontend.services.single', [purify_html($slug), $id]);
            break;
        case ('product_mega_menu'):
            $return_val = route('frontend.products.single', [purify_html($slug), $id]);
            break;
        case ('blog_mega_menu'):
            $return_val = route('frontend.blog.single', [purify_html($slug), $id]);
            break;
        default:
            break;
    }

    return $return_val;
}

function paypal_gateway()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::paypal();
}

function paytm_gateway()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::paytm();
}

function stripe_gateway()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::stripe();
}

function paystack_gateway()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::paystack();
}

function razorpay_gateway()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::razorpay();
}

function flutterwaverave_gateway()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::flutterwave();
}

function mollie_gateway()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::mollie();
}

function script_currency_list()
{
    return \Xgenious\Paymentgateway\Facades\XgPaymentGateway::script_currency_list();
}

function render_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_footer_copyright');
    $footer_copyright_text = str_replace('{copy}', '&copy;', $footer_copyright_text);
    $footer_copyright_text = str_replace('{year}', date('Y'), $footer_copyright_text);

    return purify_html_raw($footer_copyright_text);
}

function render_admin_panel_widgets_list()
{
    return WidgetBuilderSetup::get_admin_panel_widgets();
}

function render_admin_saved_widgets($location)
{
    $output = '';
    $all_widgets = Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'asc')->get();
    foreach ($all_widgets as $widget) {
        $output .= WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $widget->widget_name,
            'id' => $widget->id,
            'type' => 'update',
            'order' => $widget->widget_order,
            'location' => $widget->widget_location
        ]);
    }

    return $output;
}

function get_admin_sidebar_list()
{
    return WidgetBuilderSetup::get_admin_widget_sidebar_list();
}

function render_frontend_sidebar($location, $args = []): string
{
    $output = '';
    $all_widgets = Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'ASC')->get();
    foreach ($all_widgets as $widget) {
        $output .= WidgetBuilderSetup::render_widgets_by_name_for_frontend([
            'name' => $widget->widget_name,
            'location' => $location,
            'id' => $widget->id,
            'column' => $args['column'] ?? false
        ]);
    }
    return $output;
}

function get_all_language()
{
    $all_lang = Language::where('status', 'publish')->orderBy('default', 'DESC')->get();
    return $all_lang;
}

function get_language_name_by_slug($slug)
{
    $data = Language::where('slug', $slug)->first();
    return $data->name;
}

function get_blog_category_by_id($id, $type = '')
{
    $return_val = __('uncategorized');
    $blog_cat = \App\BlogCategory::find($id);
    if (!empty($blog_cat)) {
        $return_val = $blog_cat->name;
        if ($type == 'link') {
            $return_val = '<a href="' . route('frontend.blog.category', ['id' => $blog_cat->id, 'any' => Str::slug($blog_cat->name)]) . '">' . $blog_cat->name . '</a>';
        }
    }
    return $return_val;
}

function getAllBlogTags(): array
{
    $all_blog_tags = Blog::select('tags')->where('status', 'publish')->pluck('tags')->toArray();
    $tags = [];

    foreach ($all_blog_tags as $blog_tags) {
        $tags = array_merge($tags, explode(',', $blog_tags));
    }

    return array_unique($tags);
}

function custom_amount_with_currency_symbol($amount, $text = false)
{
    $amount = number_format((float)$amount, 0, '.', ',');
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = '<span class="sign">' . $symbol . '</span>' . $amount;
    if ($position == 'right') {
        $return_val = $amount . '<span class="sign">' . $symbol . '</span>';
    }
    return $return_val;
}

function admin_default_lang()
{
    $default_lang = Language::where(['default' => 1, 'status' => 'publish'])->first();
    return $default_lang->slug;
}

function front_default_lang()
{
    $default_lang = !empty(session()->get('lang')) ? session()->get('lang') : Language::where('default', 1)->first()->slug;
    return $default_lang;
}

function get_default_language_direction()
{
    $default_lang = Language::where('default', 1)->first();
    return !empty($default_lang) ? $default_lang->direction : 'ltr';
}

function multilang_field_display($fields, $field_name, $lang)
{
    foreach ($fields as $field) {
        if ($field->lang == $lang) {
            return $field->$field_name;
        }
    }
}

function get_image_category_name_by_id($id)
{
    $return_val = __('uncategorized');

    $category_details = \App\ImageGalleryCategory::find($id);
    if (!empty($category_details)) {
        $return_val = $category_details->title;
    }

    return $return_val;
}

function is_tax_enable()
{
    return get_static_option('product_tax') && get_static_option('product_tax_system') == 'exclusive' ? true : false;
}

function render_ratings($ratings, $class = '')
{
    return str_repeat('<i class="las la-star '.$class.'"></i>', $ratings);
}

function get_cart_items()
{
    $old_cart_item = session()->get('cart_item');
    $return_val = !empty($old_cart_item) ? $old_cart_item : [];

    return $return_val;
}

function get_attachment_url_by_id($id, $size = null)
{
    $return_val = get_attachment_image_by_id($id, $size);
    return $return_val['image_id'] ?? '';
}

function get_cart_subtotal($currency_symbol = true)
{
    $total_cart_items = session()->get('cart_item');
    $return_val = $currency_symbol ? amount_with_currency_symbol(0) : 0;
    if (!empty($total_cart_items)) {
        $return_val = 0;
        foreach ($total_cart_items as $product_id => $cat_data) {
            $return_val += (int)$cat_data['price'];
        }
        return $currency_symbol ? amount_with_currency_symbol($return_val) : $return_val;
    }

    return $return_val;
}

function cart_total_items()
{
    $return_val = session()->get('cart_item');
    return !empty($return_val) ? array_sum(array_column($return_val, 'quantity')) : 0;
}


function is_shipping_available()
{
    $all_cart_item = session()->get('cart_item');
    $return_val = true;
    $cart_item_type = !empty($all_cart_item) ? array_unique(array_column($all_cart_item, 'type')) : [];
    if (count($cart_item_type) == 1 && in_array('digital', $cart_item_type)) {
        $return_val = false;
    }

    return $return_val;
}

function rest_cart_session()
{
    session()->forget([
        'shipping_charge',
        'cart_item',
        'coupon_discount',
    ]);
}

function all_lang_slugs()
{
    return Language::all()->pluck('slug')->toArray();
}

function exist_slugs($model_data)
{
    return $model_data->lang_all->pluck('lang')->toArray();
}

function purify_html($html)
{
    return strip_tags(\Mews\Purifier\Facades\Purifier::clean($html));
}

function purify_html_raw($html)
{
    return \Mews\Purifier\Facades\Purifier::clean($html);
}

function render_colored_text($text)
{
    return str_replace(['{color}', '{/color}'], ['<span>', '</span> '], $text);
}

function get_percentage($amount, $numb)
{
    if ($amount > 0) {
        return round($numb / ($amount / 100), 2);
    }
    return 0;
}

function render_attachment_preview_for_admin($id)
{
    $markup = '';
    $header_bg_img = get_attachment_image_by_id($id, null, true);
    $img_url = $header_bg_img['img_url'] ?? '';
    $img_alt = $header_bg_img['img_alt'] ?? '';
    if (!empty($img_url)) {
        $markup = sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt="%2$s"></div></div></div>', $img_url, $img_alt);
    }
    return $markup;
}


function render_gallery_image_attachment_preview($gal_image)
{
    if (empty($gal_image)) {
        return;
    }
    $output = '';
    $gallery_images = explode('|', $gal_image);
    foreach ($gallery_images as $gl_img) {
        $work_section_img = get_attachment_image_by_id($gl_img, null, true);
        if (!empty($work_section_img)) {
            $output .= sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt=""> </div></div></div>', $work_section_img['img_url']);
        }
    }
    return $output;
}

function render_img_url_data_attr($id, $attr)
{
    $header_bg_img = get_attachment_image_by_id($id, null, true);
    $img_url = $header_bg_img['img_url'] ?? '';
    return sprintf('data-%1$s="%2$s"', $attr, $img_url);
}

/* menu builder helpers */
function render_pages_list($lang = null)
{
    $instance = new \App\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_static_pages_list($lang);
}

function render_dynamic_pages_list($lang = null)
{
    $instance = new \App\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_post_type_page_list($lang);
}

function render_mega_menu_list($lang = null)
{
    $instance = new \App\MenuBuilder\MegaMenuBuilderSetup();
    return $instance->render_mega_menu_list($lang);
}

function render_draggable_menu($id): string
{
    $instance = new MenuBuilderAdminRender();

    return $instance->render_admin_panel_menu($id);
}

function render_frontend_menu($id,$type=null): string
{
    $instance = new MenuBuilderFrontendRender();

    return $instance->render_frrontend_panel_menu($id,$type);
}

function ratings_markup($ratings, $type = '')
{
    $markup = '';
    $markup_frontend = '';
    switch ($ratings) {
        case ('1'):
            $markup = '<i class="las la-star"></i>';
            $markup_frontend = '<li><i class="las la-star"></i></li>';
            break;
        case ('2'):
            $markup = '<i class="las la-star"></i><i class="las la-star"></i>';
            $markup_frontend = '<li><i class="las la-star"></i></li><li><i class="las la-star"></i></li>';
            break;
        case ('3'):
            $markup = '<i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>';
            $markup_frontend = '<li><i class="las la-star"></i></li><li><i class="las la-star"></i></li><li><i class="las la-star"></i></li>';
            break;
        case ('4'):
            $markup = '<i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>';
            $markup_frontend = '<li><i class="las la-star"></i></li><li><i class="las la-star"></i></li><li><i class="las la-star"></i></li><li><i class="las la-star"></i></li>';
            break;
        case ('5'):
            $markup = '<i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>';
            $markup_frontend = '<li><i class="las la-star"></i></li><li><i class="las la-star"></i></li><li><i class="las la-star"></i></li><li><i class="las la-star"></i></li><li><i class="las la-star"></i></li>';
            break;
        default:
            break;
    }
    return $type == 'li' ? $markup_frontend : $markup;
}


function sanitize_html($value)
{
    return htmlspecialchars(strip_tags($value));
}

function esc_url($val)
{
    return htmlspecialchars(filter_var($val, FILTER_SANITIZE_URL));
}

function custom_html($value)
{
    return Purifier::clean($value);
}

function sanitizeArray($input_array, $secondary = false)
{
    $return_arr = [];
    if (is_array($input_array) && count($input_array)) {
        $return_arr = [];
        foreach ($input_array as $value) {
            $clean_data = is_array($value) ? sanitizeArray($value) : sanitize_html($value);
            if (is_string($clean_data) && strlen($clean_data)) {
                $return_arr[] = $clean_data;
            }
        }
        return $return_arr;
    }
}

function decodeProductAttributesOld($endcoded_attributes): array
{
    $all_attributes = ProductAttribute::all();
    $decoded_attributes = json_decode($endcoded_attributes, true);
    $result = [];
    if ($decoded_attributes) {
        foreach ($decoded_attributes as $key => $attribute) {
            $db_attribute = $all_attributes->find($key);
            $result[] = [
                'name' => $all_attributes->find($key)->title,
                'terms' => $attribute
            ];
        }
    }

    return $result;
}

function decodeProductAttributes($endcoded_attributes): array
{
    $decoded_attributes = json_decode($endcoded_attributes, true);
    $result = [];
    if ($decoded_attributes) {
        foreach ($decoded_attributes as $key => $attributes) {
            $result[] = [
                'name' => count($attributes) ? $attributes[0]['type'] : '',
                'terms' => $attributes
            ];
        }
    }

    return $result;
}

if(!function_exists("getUserByGuard")){
    function getUserByGuard($guard = 'web'): ?Authenticatable
    {
        return auth()->guard($guard)->user();
    }
}

function get_product_variant_list_by_id($id)
{
    $varitnt = ProductAttribute::find($id);
    if (empty($varitnt)) {
        return '';
    }
    return $varitnt->title;
}

function getUserShippingAddress($shipping_address_id)
{
    $user_shipping_address = '';
    if ($shipping_address_id) {
        $user_shipping = \App\Shipping\ShippingAddress::where('id', $shipping_address_id)
            ->with('country', 'state')
            ->first();
        //find($shipping_address_id);
        $user_shipping_address = $user_shipping ?? '';
    }
    return $user_shipping_address;
}

function getAllProductSubcategory($product)
{
    $all_subcategory = $product->subCategory();
    $subcategory_arr = [];
    foreach ($all_subcategory as $subcategory) {
        $subcategory_arr[] = [
            'name' => $subcategory->title ?? '',
            'url' => route('frontend.products.subcategory', $subcategory->id)
        ];
    }
    return $subcategory_arr;
}

function getCampaignItemStockInfo($campaign_product)
{
    $campaign_product_count = optional($campaign_product)->units_for_sale ?? 0;
    $campaign_price = optional($campaign_product)->campaign_price ?? 0;
    $campaign_sold_product_count = optional(CampaignSoldProduct::where('product_id', $campaign_product->product_id)->first())->sold_count ?? 0;

    return [
        'in_stock_count' => $campaign_product_count,
        'sold_count' => $campaign_sold_product_count,
        'campaign_price' => $campaign_price,
    ];
}

function getCampaignPricePercentage($campaign_product, $product_price, $precision = 0): float|int
{
    if (!$campaign_product) return 0;
    return round(getPercentage($product_price, $campaign_product->campaign_price) * -1, $precision);
}

function getPercentage($main_price, $lower_price)
{
    return round(($main_price - $lower_price) / $main_price * 100,2);
}

function getQuickViewDataMarkup($item)
{
    $title = $item->title ?? "";
    $slug = $item->slug ?? "";
    $summary = $item->summary ?? "";
    $avg_rating = $item->rating_avg_rating ?? "";
    $stock_count = 0;

    $campaign_product = $item->campaign_product;
    $sale_price = $campaign_product ? $campaign_product->campaign_price : $item->sale_price;
    $deleted_price = $campaign_product ? $item->sale_price : $item->price;
    $campaign_percentage = $campaign_product ? getPercentage($item->sale_price, $sale_price) : false;

    $campaignSoldCount = CampaignSoldProduct::where("product_id",$item->id)->first();
    $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($item->inventory)->stock_count ?? 0;
    $stock_count = $stock_count > 0 ? $stock_count : 0;

    $sale_price = float_amount_with_currency_symbol($sale_price);
    $deleted_price = float_amount_with_currency_symbol($deleted_price);

    // category
    $category = $item->category_id ? ProductCategory::where('id', $item->category_id)->first() : null;
    $category_name = $category ? $category->title : "";
    $category_page_url = $category ? route('frontend.products.category', $category->id) : '#';

    // subcategory
    $qv_subcategory_arr = getAllProductSubcategory($item);
    $qv_subcategory_markup = "";
    $qv_subcategory_markup = json_encode($qv_subcategory_arr);

    $image_url = "";
    $image_id = $item->image;
    if ($image_id) {
        $image_details = render_image($image_id,render_type: 'url');
        if (!empty($image_details)) {
            $image_url = $image_details;
        }
    }

    if($campaign_product){
        $campaign_title = Campaign::select('id','title')->where("id",$campaign_product->campaign_id)->first();
    }

    $isCampaign =  $item->campaign ? 'true' : 'false';
    $campaignTitle = $campaign_title->title ?? '';

    $quick_view_data = "data-title='" . htmlentities($title, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-slug='" . htmlentities($item->slug, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-summary='" . htmlentities($summary, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-price='" . htmlentities($deleted_price, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-sale-price='" . htmlentities($sale_price, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-uom='" . htmlentities($item->uom, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-unit='" . htmlentities($item->unit, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-attributes='" . htmlentities($item->attributes, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-category='" . htmlentities($category_name, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-subcategory='" . htmlentities($qv_subcategory_markup, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-category-url='" . htmlentities($category_page_url, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-image='" . htmlentities($image_url, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-rating='" . htmlentities($avg_rating, ENT_QUOTES) . "' ";
    $quick_view_data .= "data-iscampaign='" .$isCampaign."'";
    $quick_view_data .= "data-campaignTitle='" .$campaignTitle."'";
    $end_date = $campaign_product->end_date ?? '';
    if(!empty($end_date)){
        $quick_view_data .= "data-campaigndate='" .htmlentities($end_date, ENT_QUOTES). "' ";
    }

    $quick_view_data .= $stock_count
        ? "data-in-stock='" . htmlentities($stock_count, ENT_QUOTES) . "' "
        : '';

    if($campaign_percentage){
        $quick_view_data .= "data-campaign-percentage='" . htmlentities(round($campaign_percentage,0)) . "%'";
    }

    $quick_view_data .= "data-badge='" . htmlentities($item->badge ?? "") . "'";

    return $quick_view_data;
}

function getCountryShippingCost($type, $id)
{
    if ($type == 'country') {
        $zone_regions = ZoneRegion::whereJsonContains('country', $id)->get()->pluck('zone_id');
    } elseif ($type == 'state') {
        $zone_regions = ZoneRegion::whereJsonContains('state', $id)->get()->pluck('zone_id');
    }

    $shipping_methods = ShippingMethod::with('availableOptions')->whereIn('zone_id', $zone_regions)->get();
    return $shipping_methods;
}

function getItemAttributesName($attributes) : string
{
    if (is_string($attributes)) {
        try {
            $attributes = json_decode($attributes, true);
        } catch (Exception $exception) {
            //
        }
    }

    $item_attributes = '';
    $attribute_count = 0;
    if ($attributes) {
        $item_attributes .= ' (';
        foreach ($attributes as $key => $attribute) {
            if ($key != 'price' && $key != 'user_id') {
                if ($key == '_color') {
                    $item_attributes .= sprintf(__('Color: %s'),$attribute).' , ';
                    $attribute_count += 1;
                } elseif ($key == '_size') {
                    $item_attributes .= 'Size: ' . $attribute . ', ';
                    $attribute_count += 1;
                } else {
                    $item_attributes .= $key .': '. $attribute . ', ';
                    $attribute_count += 1;
                }
            }
        }
        $item_attributes = $attribute_count ? substr($item_attributes, 0, -2) . ')' : '';
    }
    return $item_attributes;
}

function ratingMarkup($rating_avg, $rating_count, $include_count = true) {
    $width = round($rating_avg * 20);

    $output = '<div class="rating-wrapper"><div class="rating-contents"><div class="ratings"><span class="hide-rating"></span>';
    $output .= '<span class="show-rating" style="width: '. $width .'%"></span>';
    $output .= '</div>';

    if ($include_count) {
        $output .= '<p><span class="total-ratings">(' . $rating_count . ')</span></p>';
    }

    $output .= '</div></div>';
    return $output;
}

/** ============================================
 *          PAGE RENDER BUILDER HELPERS
 * ============================================= */
function formatCustomTags($custom_tags, $render_tags, $text) {
    return str_replace($custom_tags, $render_tags, $text);
}

function getInvoiceAddressInfo($address_id, $type)
{
    $type_name = "";
    $address_text = "";
    $output = "";

    switch ($type) {
        case 'country':
            $country = $address_id ? \Modules\CountryManage\Entities\Country::find($address_id) : 0;
            $address_text = optional($country)->name;
            $type_name = __("Country");
            break;

        case 'state':
            $state = $address_id ? \Modules\CountryManage\Entities\State::find($address_id) : 0;
            $address_text = optional($state)->name;
            $type_name = __("State");
            break;

        case 'city':
            $address_text = $address_id;
            $type_name = __("City");
            break;

        case 'zipcode':
            $address_text = $address_id;
            $type_name = __("Zip Code");
            break;

        default:
            $type_name = "";
            $address_text = "";
            break;
    }

    $output = '<li class="list"><span>'. $type_name .':</span> '. $address_text .'</li>';

    return $output;
}
/*
 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
 * */
function getProductByParams($params) {
    $product_items = $params['product_items'];
    $items_order = $params['items_order'];
    $items_count = $params['items_count'];
    $sort_by = $params['sort_by'];

    $products = Product::query()
        ->with('campaign_sold_product', 'subCategory','campaign_product', 'inventory','badge','uom')
        ->withAvg("reviews", "rating")
        ->withCount("reviews")
            // this line of code will return sum of tax rate for example I have 2 tax one is 5 percent another one is 10 percent then this will return 15 percent
        ->withSum("taxOptions", "rate")
        ->where('status_id', '1');


    // call a function for campaign this function will add condition to this table
    $products = productCampaignCondition($products);
    if (!empty($product_items)) {
        $products->whereIn('id', $product_items);
    }

    return !empty($items_count)
        ? productSort($products, $sort_by, $items_order)->paginate($items_count)
        : productSort($products, $sort_by, $items_order)->paginate();
}

/*
 * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator
 * */
function getPaginationSummaryText($pagination_instance)
{
    $from_text = $pagination_instance->perPage() * $pagination_instance->currentPage() - ($pagination_instance->perPage() - 1);

    $start = $pagination_instance->perPage() * $pagination_instance->currentPage() - ($pagination_instance->perPage() - 1);
    $end = $pagination_instance->total();

    if ($pagination_instance->currentPage() < $pagination_instance->lastPage()) {
        $end = $pagination_instance->perPage() * $pagination_instance->currentPage();
    }

    return [
        'start' => $start,
        'end' => $end,
        'total' => $pagination_instance->total()
    ];
}

function getProductSearchRatingInput($field, $selected_value) : string
{
    $checked = $selected_value == $field ? 'checked' : '';
    $output = '<div class="single-rating-item">
                <label class="radio-btn-wrapper">
                    <input type="radio" class="checkbox"
                           name="filter_rating" data-value="'.$field.'"
                           class="checkbox filter_rating" '.$checked.'
                    >
                    <span class="checkmark"></span>
                    <span class="icon-wrap">';
    $output .= str_repeat('<i class="las la-star icon active"></i>', $field);
    $output .= str_repeat('<i class="lar la-star icon active"></i>', abs(5 - $field));
    $output .= '</span></label></div>';
    return $output;
}

function productSort($all_products, $sort_by, $items_order = 'DESC') {
    if ($sort_by == 'popularity') {
        $all_products->orderBy('sold_count', $items_order);
    } else if ($sort_by == 'latest') {
        $all_products->orderBy('id', $items_order);
    } else if ($sort_by == 'price_low') {
        $all_products->orderBy('price', 'ASC');
    } else if ($sort_by == 'price_high') {
        $all_products->orderBy('price', 'DESC');
    } elseif ($sort_by == 'rating') {
        $all_products->withAvg('rating', 'rating')->orderBy('rating_avg_rating', $items_order);
    }
    return $all_products;
}
function getItemAttributes($product_id)
{
    $product = Product::find($product_id);
    if (is_null($product)) {
        return [];
    }

    $result = [];
    $all_attributes = json_decode($product?->attributes, true);
    foreach ($all_attributes as $key => $product_attributes) {
        foreach ($product_attributes as $key => $product_attribute) {
            $result[$product_attribute['type']][] = $product_attribute['name'];
        };
    }
    return $result;
}
function getCampaignProductById($product_id): ?CampaignProduct
{
    return CampaignProduct::where('product_id', $product_id)
        ->whereDate("start_date","<=", now())
        ->whereDate("end_date",">=", now())->first();
}
function getItemDisplayPrice($item): void
{
    $campaign_product = getCampaignProductById($item->id);
    $sale_price = $campaign_product ? $campaign_product->campaign_price : $item->sale_price;
    $deleted_price = $campaign_product ? $item->sale_price : $item->price;
}
function getSlugFromReadingSetting ($reading_type,$default='blog') {
    $page_id = get_static_option($reading_type);
    $page = null;
    try{
        $page = Page::find($page_id);
    }catch (\Exception $e){
        //handle error
    }

    if ($page) {
        return $page->slug;
    }
    return $default;
}
function getAllCategory() {
    return Category::select('id', 'name as title')->where('status_id', 1)->get();
}
function render_navbar_category_menu(): string
{
    $category_dropdown_data = json_decode(get_static_option('navbar_category_dropdown'), true);

    if (empty($category_dropdown_data)) return '';

    $output = '';
    $all_categories = Category::with('subcategory')->whereIn('id', array_keys($category_dropdown_data))->get();

    foreach ($all_categories as $category) {
        $category_url = route('frontend.products.category', $category->id);
        $sub_category_ids = !empty($category_dropdown_data[$category->id]['subcategories'])
            ? $category_dropdown_data[$category->id]['subcategories']
            : [];
        $subcategories = !empty($category->subcategory)
            ? $category->subcategory->whereIn('id', $sub_category_ids)
            : null;

        $sub_menu_class = $subcategories->count() ? 'menu-item-has-children mega-menu-wrap' : '';

        $style = !empty($category_dropdown_data[$category->id]['style'])
            ? $category_dropdown_data[$category->id]['style']
            : 'list';
        $sub_category_style_class = $style === 'thumbnail' ? 'catg' : '';

        // category
        $output .= '<li class="'.$sub_menu_class.'">'; // category <li> start
        $output .= '<a href="'.$category_url.'">'.$category->title.'</a>';

        // subcategory
        if ($subcategories->count()) {
            $output .= '<ul class="sub-menu mega-menu-inner '.$sub_category_style_class.'">'; // subcategory <ul> start

            foreach ($subcategories as $subcategory) {
                if ($style == 'list') {
                    /* LIST style */
                    $output .= '<li class="mega-menu-single-section">'; // list style section start
                    $products = Product::where('status_id', 1)->whereIn('sub_category_id', $sub_category_ids)->get();

                    $output .= '<ul class="mega-menu-single-section">'; // sub-category column start
                    $output .= '<h5 class="menu-title">'.$subcategory->title.'</h5>'; // sub-category title

                    foreach ($products as $product) {
                        $output .= '<li><a href="'.route("frontend.products.single", $product->slug).'">'.$product->title.'</a></li>';
                    }

                    $output .= '</ul>'; // sub-category column end
                    $output .= '</li>'; // list style section end
                } else {
                    /* THUMBNAIL style */
//                    $output .= '<li class="mega-menu-single-section custom">'; // thumbnail style section start
//                    $output .= '<ul class="mega-menu-main">'; // subcategory items thumbnails <ul> start

                    $output .=
                        '<li class="round-menu-product"><a href="'.route("frontend.products.subcategory", $subcategory->id).'">'
                        . render_image_markup_by_attachment_id($subcategory->image)
                        . $subcategory->title
                        . '</a></li>';

//                    $output .= '</ul>';
//                    $output .= '</li>'; // thumbnail style section end
                }
            }

            $output .= '</ul>'; // subcategory </ul> end
        }

        $output .= '</li>'; // category <li> end
    }

    return $output;
}

/* menu builder helpers */

function render_product_category_list($lang = null)
{
    $instance = new \App\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_product_category_list($lang);
}

function render_product_category_mega_menu_list($lang = null)
{
    $instance = new \App\MenuBuilder\CategoryMenuBuilderSetup();
    return $instance->render_mega_menu_list($lang);
}

function render_draggable_category_menu($id)
{
    $instance = new MenuBuilderAdminRender();
    return $instance->render_product_category_admin_panel_menu($id);
}

function render_payment_gateway_name($type){
    if($type == 'manual_payment'){
        return get_static_option("site_manual_payment_name");
    }elseif($type == 'cash_on_delivery'){
        return __("Cash On Delivery");
    }else{
        return $type;
    }
}

if(!function_exists("render_view_for_nice_select")){
    #[ArrayShape(["option" => "string","li" => "string"])]
    function render_view_for_nice_select($data): array
    {
        $li = view("vendor::backend.get_state.list",compact("data"))->render();
        $option = view("backend.partials.nice-select-option.option",compact("data"))->render();

        return ["li" => $li,"option" => $option];
    }
}

if(!function_exists("render_validated_value")){
    function render_validated_value($data): string
    {
        $output = "";
        foreach($data as $key => $value){
            $output .= $key . ' = ' . $value . "<br>";
        }

        return $output;
    }
}

if(!function_exists("status_option")){
    function status_option($type = "option"): string
    {
        // get all status
        $statuses = \App\Status::all();
        $output = "";

        if($type == "option"){
            foreach($statuses as $status){
                $output .= "<option value='" . $status->id . "'>". $status->name ."</option>";
            }
        }elseif ($type == "list"){
            foreach($statuses as $status){
                $output .= '<li class="single-item" data-value="'. $status->id .'"><a class="dropdown-item" href="#1">' . $status->name . '</a></li>';
            }
        }

        return $output;
    }
}

if(!function_exists("filter_value_for_query")){
    function filter_value_for_query($value): string
    {
        return trim(strip_tags($value));
    }
}

if(!function_exists("render_image")){ // Idea and generated by Md Zahidul Islam
    function render_image(string|object|null $image, string $size = "grid",string $render_type = 'tag',string $class = '', string $attribute = '' , string $file_type = "image", bool $defaultImage = false,string $custom_path = '', string $imageType = '') : null | string
    {
        return Media::render_image($image, $size, $render_type, $class, $attribute, $file_type, $defaultImage, $custom_path);
    }
}

if(!function_exists("createSlug")){
    function createSlug($sluggable_text, $model_name, $is_module = false, $module_name = null, $column_name = 'slug'): string  // Idea from Suzon extended by Md Zahid
    {
        // Use CamelCase for Model and Module Name
        if ($is_module)
        {
            $model_path = 'Modules\\'.ucwords($module_name).'\Entities\\' . ucwords($model_name);
        } else {
            $model_path = '\App\Models\\' . ucwords($model_name);
        }

        $slug = Str::slug($sluggable_text);
        $check = true;

        do{
            $old_category = (new $model_path)->where($column_name, $slug)->orderBy('id','DESC')->first();

            if ($old_category != null) {
                $old_category_name = $old_category->$column_name;
                $exploded = explode('-', $old_category_name);

                if (array_key_exists(1, $exploded)) {
                    $number = end($exploded);

                    if (is_numeric($number))
                    {
                        $number = (int)$number;
                        array_pop($exploded);

                        $final_array = array_merge($exploded, Arr::wrap(++$number));

                        $slug = implode('-',$final_array);
                    } else {
                        $slug .= '-1';
                    }
                } else {
                    $slug .= '-1';
                }
            }else{
                $check = false;
            }
        } while ($check);

        return $slug;
    }
}

function render_star_rating_markup($rating): string
{
    $star = (int)(2*$rating).'0';

    return '<div class="rating-wrap mt-2">
                 <div class="ratings">
                      <span class="hide-rating"></span>
                      <span class="show-rating" style="width: '.$star.'%'.'"></span>
                 </div>
            </div>';
}

function moduleExists($name): bool
{
    $module_status = json_decode(file_get_contents(__DIR__.'/../../modules_statuses.json'));
    return property_exists($module_status,$name) ? $module_status->$name : false;
}

/**
 * @return void
 */
function common_route(): void
{
    Route::get('/', 'index')->name('all');
    Route::get('new', 'create')->name('new');
    Route::post('new', 'store');
    Route::get('edit/{item}', 'edit')->name('edit');
    Route::post('update', 'update')->name('update');
    Route::post('delete/{item}', 'destroy')->name('delete');
    Route::post('bulk-action', 'bulk_action')->name('bulk.action');
}

if(!function_exists("toFixed")){
    function toFixed($number, $decimals = 0): string
    {
        return number_format($number, $decimals, '.', "");
    }
}

function render_payment_gateway_for_form($cash_on_delivery = false): string
{
    $output = '<div class="payment-gateway-wrapper">';
    $output .= '<input type="hidden" name="selected_payment_gateway" value="' . get_static_option('site_default_payment_gateway') . '">';
    $all_gateway = PaymentGateway::when($cash_on_delivery == false, function ($query){
        $query->whereNot('name','cash_on_delivery');
    })->get();

    $output .= '<ul>';

    foreach ($all_gateway as $gateway) {
        // Payfast & Flutterwave is removed due to support issue
        $class = (get_static_option('site_default_payment_gateway') == $gateway->name) ? 'class="selected"' : '';

        $output .= '<li data-gateway="' . $gateway->name . '" ' . $class . '><div class="img-select">';
        $output .= render_image_markup_by_attachment_id($gateway->image);
        $output .= '</div></li>';
    }

    $output .= '</ul>';
    $output .= '</div>';

    return $output;
}

function toastr_success($success){
    Toastr::success($success,'Success!', ["positionClass" => "toast-top-right","closeButton" => "true","progressBar" => "true"]);
}

function toastr_error($success): void
{
    Toastr::success($success,'Error!', ["positionClass" => "toast-top-right","closeButton" => "true","progressBar" => "true"]);
}

function activeGuard(): int|string|null
{
    foreach(array_keys(config('auth.guards')) as $guard){
        if(auth($guard)->check()) return $guard;
    }

    return null;
}

function addonProductInstance(): Builder|_IH_Product_QB
{
    $products = Product::query();

    $products->withCount("inventoryDetail","ratings","orderItems");
    $products->with([
        'vendor',
        "taxOptions:tax_class_options.id,country_id,state_id,city_id,rate",
        "vendorAddress:vendor_addresses.id,country_id,state_id,city_id",
        "campaign_sold_product","category","subCategory","childCategory",
        "campaign_product" => function ($query) {
            $query = productCampaignConditionWith($query);
        },"inventory","uom", "badge","taxOptions:tax_class_options.id,country_id,state_id,city_id,rate",
        "vendorAddress:vendor_addresses.id,country_id,state_id,city_id"
    ]);
    $products->withAvg('ratings','rating');// this line of code will return a sum of tax rate, for example,
    //I have 2 tax one is 5 percent, another one is 10 percent then this will return 15 percent
    $products->withSum("taxOptions", "rate");

    return $products;
}

function calculatePercentageAmount($price, $percentage): float|int
{
    return ($price * $percentage) / 100;
}

function calculatePrice($price, $product,$for = "product"): float|int|null
{
    if(gettype($product) == 'array'){
        $product = (object) $product;
    }

    return CalculateTaxServices::productPrice($price, $product,$for);
}

function productCampaignConditionWith($query){
    $date = now();

    return $query->whereDate("end_date", ">=", $date)->whereDate("start_date", "<=", $date);
}

function productCampaignCondition($query){
    $date = now();

    $query->whereHas("campaign_product", function ($campaignProduct) use ($date){
        $campaignProduct->whereDate("end_date", ">=", $date)->whereDate("start_date", "<=", $date);
    })->when(get_static_option("calculate_tax_based_on") == 'customer_billing_address', function ($query){
        // first need to get vendor address from
    });

    return $query;
}
function tax_options_sum_rate($item, $country_id = 0, $state_id = 0, $city_id = 0){
    $taxOptions = $item->taxOptions?->where("country_id", $country_id);
    $stateTaxOptions = $taxOptions->where("state_id", $state_id);
    $cityTaxOptions = $taxOptions->where("city_id", $city_id);
    $taxPercentage = 0;
    $taxPercentageForState = 0;
    $taxPercentageForCity = 0;

    foreach ($taxOptions as $taxOption) {
        $taxPercentage += $taxOption->rate;
    }

    foreach ($stateTaxOptions as $taxOption) {
        $taxPercentageForState += $taxOption->rate;
    }

    foreach ($cityTaxOptions as $taxOption) {
        $taxPercentageForCity += $taxOption->rate;
    }

    if($item->taxOptions->count() > 0 && empty($country_id)){
        foreach($item->taxOptions as $taxOption){
            $item->tax_options_sum_rate += $taxOption->rate;
        }

        return $item;
    }

    if(!empty($country_id) && $taxOptions->count() > 0){
        $item->tax_options_sum_rate = $taxPercentage;
    }

    if(!empty($state_id) && $stateTaxOptions->count() > 0){
        $item->tax_options_sum_rate = $taxPercentageForState;
    }

    if(!empty($city_id) && $cityTaxOptions->count() > 0 && is_int($city_id)){
        $item->tax_options_sum_rate = $taxPercentageForCity;
    }

    return $item;
}

function calculateOrderedPrice($price, $tax, $type): float|int
{
    // check type if type is billing
    return match ($type){
        "billing_address", "inclusive_price" => $price * $tax / 100,
        "zone_wise_tax" => $tax,
    };
}

function prepare_coordinates($object): string
{
    $str = '';
    foreach ($object as $key => $coordinate) {
        $str .= '(' . $coordinate->lat . ', ' . $coordinate->lng . ')' . ($key + 1 != count($object) ? ',' : '');
    }
    return $str;
}

function coordinatesArray($wkt): array
{
    // Extract the coordinates part from the WKT representation
    $pattern = '/POLYGON\(\((.*?)\)\)/';
    if (preg_match($pattern, $wkt, $matches)) {
        $coordinates = explode(',', $matches[1]);
        $points = [];
        foreach ($coordinates as $coordinate) {
            [$lng, $lat] = explode(' ', trim($coordinate));
            $points[] = ["lat" => (float) $lat, "lng" => (float) $lng];
        }
        return $points;
    }
    return [];
}

function deliveryManTypes(): array
{
    return ["Freelance", "Contractual", "wise", "Employee"];
}

function vehicleTypes(): array
{
    return ["Car", "Motorcycle", "Bicycle", "Truck", "Van"];
}

function identityTypes(): array
{
    return ["Passport", "Driver's License", "National Identity Card"];
}

function xgNotifications($for = null): object|array
{
    return (new NotificationService())->fetch($for);
}

function xgUnReadNotifications($from = null): int
{
    return (new NotificationService())->unreadMessageCount($from);
}

function formatNotificationText($text = null): string
{
    return str_replace(["[b]","[/b]","[br]"],["<b>","</b>","<br>"],$text);
}

function getCurrencyRate($currency_code = 'USD'){
    $rate = Cache::get('currency_rate');
    if(is_null($rate)){
        $currency_rate = CurrencyRate::where('currency_code',$currency_code)->first();
        $rate = $currency_rate->rate;
        Cache::forever('currency_rate', $rate);
    }
    return $rate;
}

function setIpCurrency(){
    $ip = request()->ip();

    // $currency_ip = session()->get('currency');
    $currency_ip = null;

    // if currency is already set then return it

    if($currency_ip){
        return $currency_ip;
    }

    // if currency is not set then get it from ip
    if ($position = Location::get($ip)) {
        // Successfully retrieved position.
        if($position->currencyCode == 'BDT'){
            session()->put('currency','BDT');
            return 'BDT';
        }else{
            session()->put('currency','USD');
            return 'USD';
        }
    } else {
        // Failed retrieving position.
        session()->put('currency','BDT');
        Log::error('Failed retrieving position.');
        return 'BDT';
    }
}

function getCurrency(){
    // $currency_browser = session()->get('currency_browser');
    $currency_browser = null;
    $currency_ip = setIpCurrency();
    $currency = $currency_browser ?: $currency_ip ;
    return $currency;
}

function currencySign(){
    $currency = getCurrency();
    if($currency == 'BDT'){
        return 'Tk';
    }else{
        return '$';
    }
}

function BDtoUSD($price,$sellPrice){
    $bd_to_usd_rate = getCurrencyRate();
    $price = round($price / $bd_to_usd_rate,2);
    $sellPrice = round($sellPrice / $bd_to_usd_rate,2);
    return ['price' => $price, 'sell_price' => $sellPrice];
}

function getLocationBasedCartContent()
{
    $cart = Cart::content();
    $currency = getCurrency(); // This uses your existing helper
    
    // Transform cart items with location-based prices
    $updatedCart = $cart->map(function($item) use ($currency) {

        $product = Product::find($item->id);

        $additional_price = $item->options['additional_price'] ?? 0;
        $extra_cost = $item->options['extra_cost'] ?? 0;

        $amounts = $product->amountsBasedOnCurrency($additional_price, $extra_cost);
        // dd($amounts);
        // Update price based on location currency
        $item->price = $amounts['sell_price'];
        $item->options['currency'] = $amounts['currency'];
        
        return $item;
    });
    
    return $updatedCart;
}
