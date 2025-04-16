<?php

namespace App\Http\Helpers;

use App\Models\FrontVariablesLang;
use Illuminate\Database\Eloquent\Model;

class LanguageHelper
{
    public function getTranslations(array $keys, string $languageKey)
    {
        $translations = [];
        $frontVariables = [];

        $frontVariablesLangs = FrontVariablesLang::leftjoin('translations as t', 't.foreign_key', '=', 'front_variables_lang.id')
            ->select(
                'front_variables_lang.key as key',
                'front_variables_lang.value as value_ru',
                't.value as value_language_key'
            )
            ->whereIn('front_variables_lang.key', $keys);

        if ($languageKey != 'ru')
            $frontVariablesLangs = $frontVariablesLangs->where(function ($query) use ($languageKey) {
                $query->whereNull('t.locale')
                    ->orWhere('t.locale', $languageKey);

            });

        $frontVariablesLangs->get()

            ->each(function ($item) use (&$frontVariables, $languageKey) {

                if (!empty($item->value_language_key) && $languageKey != 'ru') {
                    $frontVariables[$item->key] = $item->value_language_key;
                    return true;
                }

                if ($item->value_ru !== null) {
                    $frontVariables[$item->key] = $item->value_ru;
                    return true;
                }

                $frontVariables[$item->key] = $item->key;

            });

        foreach ($keys as $value) {
            $translations[$value] = $value;

            if (isset($frontVariables[$value]))
                $translations[$value] = $frontVariables[$value];

        }

        return $translations;
    }
}
