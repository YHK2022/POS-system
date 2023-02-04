<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use App\Language;

class LanguageController extends Controller
{
    public function switchLanguage($locale)
    {
    	setcookie('language', $locale, time() + (86400 * 365), "/");
        $language = Language::firstOrNew(['user_id' => auth()->user()->id]);
        $language->code = $locale;
        $language->user_id = auth()->user()->id;
        $language->save();
    	return Redirect::back();
    }
}