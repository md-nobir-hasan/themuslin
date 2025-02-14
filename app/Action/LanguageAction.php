<?php

namespace App\Action;

use App\Language;

class LanguageAction
{
    public static function setDefaultLanguage($id) : array
    {
        $selected_language = Language::find($id);

        if ($selected_language) {
            Language::where('default', 1)->update(['default' => 0]);

            $selected_language->update([
                'default' => 1,
                'status' => 'publish',
            ]);

            session()->put('lang', $selected_language->slug);

            return [
                'status' => true,
                'lang' => $selected_language->name
            ];
        }

        return [
            'status' => false,
            'lang' => ''
        ];
    }
}