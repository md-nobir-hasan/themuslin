<?php

namespace App\Helpers;

use App\Language;

class LanguageHelper
{
    private static $language = null;
    private static $default = null;
    private static $user_lang_slug = null;
    private static $default_slug = null;
    private static $user_lang = null;
    private static $all_language = null;

    public function __construct()
    {
        self::lang_instance();
    }

    private static function lang_instance()
    {
        if (self::$language === null) {
            self::$language = new Language();
        }
        return self::$language;
    }

    public static function user_lang()
    {
        if (self::$user_lang === null) {
            $session_lang = session()->get('lang');
            if ( !empty($session_lang) && $session_lang !== self::default_slug()){
                self::$user_lang = self::lang_instance()->where('slug',session()->get('lang'))->first();
            }else{
                self::$user_lang = self::default();
            }

        }
        return self::$user_lang;
    }

    public static function default()
    {
        if (self::$default === null) {
            $default = self::lang_instance()->where('default', '1')->first();
            self::$default = $default;
        }
        return self::$default;
    }

    public static function default_slug()
    {
        if (self::$default_slug === null) {
            $default = self::lang_instance()->where('default', '1')->first();
            self::$default_slug = $default->slug;
        }
        return self::$default_slug;
    }
    public static function default_dir()
    {
        if (self::$default === null) {
            $default = self::lang_instance()->where('default', '1')->first();
            self::$default = $default;
        }
        return self::$default->direction;
    }
    public static function user_lang_slug(){
        if (self::$user_lang_slug === null) {
            $default = self::lang_instance()->where('default', '1')->first();
            self::$user_lang_slug = session()->get('lang') ?? $default->slug;
        }
        return self::$user_lang_slug;
    }
    public static function user_lang_dir()
    {
        return self::user_lang()->direction;
    }

    public static function all_languages(string $type = 'publish')
    {
        if (self::$all_language === null) {
            self::$all_language = self::lang_instance()->where(['status' => 'publish'])->get();
        }
        return self::$all_language;
    }

    public static function getAllLanguages()
    {
        return [
            [
                "value" => "af",
                "lang" => "af",
                "title" => "Afrikaans"
            ],
            [
                "value" => "ar",
                "lang" => "ar",
                "title" => "العربية"
            ],
            [
                "value" => "ary",
                "lang" => "ar",
                "title" => "العربية المغربية"
            ],
            [
                "value" => "as",
                "lang" => "as",
                "title" => "অসমীয়া"
            ],
            [
                "value" => "az",
                "lang" => "az",
                "title" => "Azərbaycan dili"
            ],
            [
                "value" => "azb",
                "lang" => "az",
                "title" => "گؤنئی آذربایجان"
            ],
            [
                "value" => "bel",
                "lang" => "be",
                "title" => "Беларуская мова"
            ],
            [
                "value" => "bg_BG",
                "lang" => "bg",
                "title" => "Български"
            ],
            [
                "value" => "bn_BD",
                "lang" => "bn",
                "title" => "বাংলা"
            ],
            [
                "value" => "bo",
                "lang" => "bo",
                "title" => "བོད་ཡིག"
            ],
            [
                "value" => "bs_BA",
                "lang" => "bs",
                "title" => "Bosanski"
            ],
            [
                "value" => "ca",
                "lang" => "ca",
                "title" => "Català"
            ],
            [
                "value" => "ceb",
                "lang" => "ceb",
                "title" => "Cebuano"
            ],
            [
                "value" => "cs_CZ",
                "lang" => "cs",
                "title" => "Čeština"
            ],
            [
                "value" => "cy",
                "lang" => "cy",
                "title" => "Cymraeg"
            ],
            [
                "value" => "da_DK",
                "lang" => "da",
                "title" => "Dansk"
            ],
            [
                "value" => "de_CH",
                "lang" => "de",
                "title" => "Deutsch (Schweiz)"
            ],
            [
                "value" => "de_AT",
                "lang" => "de",
                "title" => "Deutsch (Österreich)"
            ],
            [
                "value" => "de_CH_informal",
                "lang" => "de",
                "title" => "Deutsch (Schweiz, Du)"
            ],
            [
                "value" => "de_DE",
                "lang" => "de",
                "title" => "Deutsch"
            ],
            [
                "value" => "de_DE_formal",
                "lang" => "de",
                "title" => "Deutsch (Sie)"
            ],
            [
                "value" => "dsb",
                "lang" => "dsb",
                "title" => "Dolnoserbšćina"
            ],
            [
                "value" => "dzo",
                "lang" => "dz",
                "title" => "རྫོང་ཁ"
            ],
            [
                "value" => "el",
                "lang" => "el",
                "title" => "Ελληνικά"
            ],
            [
                "value" => "en_US",
                "lang" => "en",
                "title" => "English (USA)"
            ],
            [
                "value" => "en_AU",
                "lang" => "en",
                "title" => "English (Australia)"
            ],
            [
                "value" => "en_GB",
                "lang" => "en",
                "title" => "English (UK)"
            ],
            [
                "value" => "en_CA",
                "lang" => "en",
                "title" => "English (Canada)"
            ],
            [
                "value" => "en_ZA",
                "lang" => "en",
                "title" => "English (South Africa)"
            ],
            [
                "value" => "en_NZ",
                "lang" => "en",
                "title" => "English (New Zealand)"
            ],
            [
                "value" => "eo",
                "lang" => "eo",
                "title" => "Esperanto"
            ],
            [
                "value" => "es_AR",
                "lang" => "es",
                "title" => "Español de Argentina"
            ],
            [
                "value" => "es_EC",
                "lang" => "es",
                "title" => "Español de Ecuador"
            ],
            [
                "value" => "es_MX",
                "lang" => "es",
                "title" => "Español de México"
            ],
            [
                "value" => "es_ES",
                "lang" => "es",
                "title" => "Español"
            ],
            [
                "value" => "es_VE",
                "lang" => "es",
                "title" => "Español de Venezuela"
            ],
            [
                "value" => "es_CO",
                "lang" => "es",
                "title" => "Español de Colombia"
            ],
            [
                "value" => "es_CR",
                "lang" => "es",
                "title" => "Español de Costa Rica"
            ],
            [
                "value" => "es_PE",
                "lang" => "es",
                "title" => "Español de Perú"
            ],
            [
                "value" => "es_PR",
                "lang" => "es",
                "title" => "Español de Puerto Rico"
            ],
            [
                "value" => "es_UY",
                "lang" => "es",
                "title" => "Español de Uruguay"
            ],
            [
                "value" => "es_GT",
                "lang" => "es",
                "title" => "Español de Guatemala"
            ],
            [
                "value" => "es_CL",
                "lang" => "es",
                "title" => "Español de Chile"
            ],
            [
                "value" => "et",
                "lang" => "et",
                "title" => "Eesti"
            ],
            [
                "value" => "eu",
                "lang" => "eu",
                "title" => "Euskara"
            ],
            [
                "value" => "fa_IR",
                "lang" => "fa",
                "title" => "فارسی"
            ],
            [
                "value" => "fa_AF",
                "lang" => "fa",
                "title" => "(فارسی (افغانستان"
            ],
            [
                "value" => "fi",
                "lang" => "fi",
                "title" => "Suomi"
            ],
            [
                "value" => "fr_FR",
                "lang" => "fr",
                "title" => "Français"
            ],
            [
                "value" => "fr_BE",
                "lang" => "fr",
                "title" => "Français de Belgique"
            ],
            [
                "value" => "fr_CA",
                "lang" => "fr",
                "title" => "Français du Canada"
            ],
            [
                "value" => "fur",
                "lang" => "fur",
                "title" => "Friulian"
            ],
            [
                "value" => "gd",
                "lang" => "gd",
                "title" => "Gàidhlig"
            ],
            [
                "value" => "gl_ES",
                "lang" => "gl",
                "title" => "Galego"
            ],
            [
                "value" => "gu",
                "lang" => "gu",
                "title" => "ગુજરાતી"
            ],
            [
                "value" => "haz",
                "lang" => "haz",
                "title" => "هزاره گی"
            ],
            [
                "value" => "he_IL",
                "lang" => "he",
                "title" => "עִבְרִית"
            ],
            [
                "value" => "hi_IN",
                "lang" => "hi",
                "title" => "हिन्दी"
            ],
            [
                "value" => "hr",
                "lang" => "hr",
                "title" => "Hrvatski"
            ],
            [
                "value" => "hsb",
                "lang" => "hsb",
                "title" => "Hornjoserbšćina"
            ],
            [
                "value" => "hu_HU",
                "lang" => "hu",
                "title" => "Magyar"
            ],
            [
                "value" => "hy",
                "lang" => "hy",
                "title" => "Հայերեն"
            ],
            [
                "value" => "id_ID",
                "lang" => "id",
                "title" => "Bahasa Indonesia"
            ],
            [
                "value" => "is_IS",
                "lang" => "is",
                "title" => "Íslenska"
            ],
            [
                "value" => "it_IT",
                "lang" => "it",
                "title" => "Italiano"
            ],
            [
                "value" => "ja",
                "lang" => "ja",
                "title" => "日本語"
            ],
            [
                "value" => "jv_ID",
                "lang" => "jv",
                "title" => "Basa Jawa"
            ],
            [
                "value" => "ka_GE",
                "lang" => "ka",
                "title" => "ქართული"
            ],
            [
                "value" => "kab",
                "lang" => "kab",
                "title" => "Taqbaylit"
            ],
            [
                "value" => "kk",
                "lang" => "kk",
                "title" => "Қазақ тілі"
            ],
            [
                "value" => "km",
                "lang" => "km",
                "title" => "ភាសាខ្មែរ"
            ],
            [
                "value" => "kn",
                "lang" => "kn",
                "title" => "ಕನ್ನಡ"
            ],
            [
                "value" => "ko_KR",
                "lang" => "ko",
                "title" => "한국어"
            ],
            [
                "value" => "ckb",
                "lang" => "ku",
                "title" => "كوردی‎"
            ],
            [
                "value" => "lo",
                "lang" => "lo",
                "title" => "ພາສາລາວ"
            ],
            [
                "value" => "lt_LT",
                "lang" => "lt",
                "title" => "Lietuvių kalba"
            ],
            [
                "value" => "lv",
                "lang" => "lv",
                "title" => "Latviešu valoda"
            ],
            [
                "value" => "mk_MK",
                "lang" => "mk",
                "title" => "Македонски јазик"
            ],
            [
                "value" => "ml_IN",
                "lang" => "ml",
                "title" => "മലയാളം"
            ],
            [
                "value" => "mn",
                "lang" => "mn",
                "title" => "Монгол"
            ],
            [
                "value" => "mr",
                "lang" => "mr",
                "title" => "मराठी"
            ],
            [
                "value" => "ms_MY",
                "lang" => "ms",
                "title" => "Bahasa Melayu"
            ],
            [
                "value" => "my_MM",
                "lang" => "my",
                "title" => "ဗမာစာ"
            ],
            [
                "value" => "nb_NO",
                "lang" => "nb",
                "title" => "Norsk bokmål"
            ],
            [
                "value" => "ne_NP",
                "lang" => "ne",
                "title" => "नेपाली"
            ],
            [
                "value" => "nl_NL",
                "lang" => "nl",
                "title" => "Nederlands"
            ],
            [
                "value" => "nl_BE",
                "lang" => "nl",
                "title" => "Nederlands (België)"
            ],
            [
                "value" => "nl_NL_formal",
                "lang" => "nl",
                "title" => "Nederlands (Formeel)"
            ],
            [
                "value" => "nn_NO",
                "lang" => "nn",
                "title" => "Norsk nynorsk"
            ],
            [
                "value" => "oci",
                "lang" => "oc",
                "title" => "Occitan"
            ],
            [
                "value" => "pa_IN",
                "lang" => "pa",
                "title" => "ਪੰਜਾਬੀ"
            ],
            [
                "value" => "pl_PL",
                "lang" => "pl",
                "title" => "Polski"
            ],
            [
                "value" => "ps",
                "lang" => "ps",
                "title" => "پښتو"
            ],
            [
                "value" => "pt_BR",
                "lang" => "pt",
                "title" => "Português do Brasil"
            ],
            [
                "value" => "pt_PT_ao90",
                "lang" => "pt",
                "title" => "Português (AO90)"
            ],
            [
                "value" => "pt_AO",
                "lang" => "pt",
                "title" => "Português de Angola"
            ],
            [
                "value" => "pt_PT",
                "lang" => "pt",
                "title" => "Português"
            ],
            [
                "value" => "rhg",
                "lang" => "rhg",
                "title" => "Ruáinga"
            ],
            [
                "value" => "ro_RO",
                "lang" => "ro",
                "title" => "Română"
            ],
            [
                "value" => "ru_RU",
                "lang" => "ru",
                "title" => "Русский"
            ],
            [
                "value" => "sah",
                "lang" => "sah",
                "title" => "Сахалыы"
            ],
            [
                "value" => "snd",
                "lang" => "sd",
                "title" => "سنڌي"
            ],
            [
                "value" => "si_LK",
                "lang" => "si",
                "title" => "සිංහල"
            ],
            [
                "value" => "sk_SK",
                "lang" => "sk",
                "title" => "Slovenčina"
            ],
            [
                "value" => "skr",
                "lang" => "skr",
                "title" => "سرائیکی"
            ],
            [
                "value" => "sl_SI",
                "lang" => "sl",
                "title" => "Slovenščina"
            ],
            [
                "value" => "sq",
                "lang" => "sq",
                "title" => "Shqip"
            ],
            [
                "value" => "sr_RS",
                "lang" => "sr",
                "title" => "Српски језик"
            ],
            [
                "value" => "sv_SE",
                "lang" => "sv",
                "title" => "Svenska"
            ],
            [
                "value" => "sw",
                "lang" => "sw",
                "title" => "Kiswahili"
            ],
            [
                "value" => "szl",
                "lang" => "szl",
                "title" => "Ślōnskŏ gŏdka"
            ],
            [
                "value" => "ta_IN",
                "lang" => "ta",
                "title" => "தமிழ்"
            ],
            [
                "value" => "ta_LK",
                "lang" => "ta",
                "title" => "தமிழ்"
            ],
            [
                "value" => "te",
                "lang" => "te",
                "title" => "తెలుగు"
            ],
            [
                "value" => "th",
                "lang" => "th",
                "title" => "ไทย"
            ],
            [
                "value" => "tl",
                "lang" => "tl",
                "title" => "Tagalog"
            ],
            [
                "value" => "tr_TR",
                "lang" => "tr",
                "title" => "Türkçe"
            ],
            [
                "value" => "tt_RU",
                "lang" => "tt",
                "title" => "Татар теле"
            ],
            [
                "value" => "tah",
                "lang" => "ty",
                "title" => "Reo Tahiti"
            ],
            [
                "value" => "ug_CN",
                "lang" => "ug",
                "title" => "ئۇيغۇرچە"
            ],
            [
                "value" => "uk",
                "lang" => "uk",
                "title" => "Українська"
            ],
            [
                "value" => "ur",
                "lang" => "ur",
                "title" => "اردو"
            ],
            [
                "value" => "uz_UZ",
                "lang" => "uz",
                "title" => "O‘zbekcha"
            ],
            [
                "value" => "vi",
                "lang" => "vi",
                "title" => "Tiếng Việt"
            ],
            [
                "value" => "zh_TW",
                "lang" => "zh",
                "title" => "繁體中文"
            ],
            [
                "value" => "zh_HK",
                "lang" => "zh",
                "title" => "香港中文版"
            ],
            [
                "value" => "zh_CN",
                "lang" => "zh",
                "title" => "简体中文"
            ]
        ];
    }
}