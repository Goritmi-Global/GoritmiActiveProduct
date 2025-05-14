<?php
use App\Models\Language;
 
use App\Models\Setting;
use App\Models\Translation;
 
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
 

if (! function_exists('getFileUrl')) {
    function getFileUrl($file_name)
    {
        return $file_name ? env('APP_URL') . Storage::url($file_name) : null;
    }
}

 
function american_date($date)
{
    if ($date) {
        return Carbon::parse($date)->format('m-d-Y');
    }
    return '';
}


function translate($key, $lang = null)
{
    // Load default language from settings or fallback
    $setting = Setting::where('type', 'default_language')->first();
    $default_lang = 'US'; // Default fallback language
    $user_lang = session('default_language') ?? $setting->value ?? 'US';

    // Cache translations per language for a certain duration (e.g., 60 minutes)
    $translations = Cache::remember("translations_{$user_lang}", 60, function () use ($user_lang) {
        return Translation::where('lang', $user_lang)->pluck('lang_value', 'lang_key')->toArray();
    });

    // If the key exists in cached translations, return it
    if (isset($translations[$key])) {
        return $translations[$key];
    }

    // Fetch default translation if not found in cache
    $translation_def = Translation::where('lang', $default_lang)->where('lang_key', $key)->first();

    if ($translation_def == null) {
        // If translation not found in default lang then saving in it
        $translation_def = new Translation;
        $translation_def->id = Str::orderedUuid();
        $translation_def->lang = $default_lang;
        $translation_def->lang_key = $key;
        $translation_def->lang_value = $key;
        
        $translation_def->save();

        // dd($translation_def->lang , $default_lang);
        if ($default_lang != $setting->value) {
            // Add the key for the current language
            $translation_def = new Translation;
            $translation_def->id = Str::orderedUuid();
            $translation_def->lang = $setting->value;
            $translation_def->lang_key = $key;
            $translation_def->lang_value = $key;
            $translation_def->save();
        }
    }elseif($translation_def && ($default_lang != $setting->value)){
        // dd("updated");
        // if found in default then checking it for current which is not default lang
        $translation_def = Translation::where('lang', $setting->value)->where('lang_key', $key)->first();
        if ($translation_def == null) {
            // Add the key for the current language
            $translation_def = new Translation;
            $translation_def->id = Str::orderedUuid();
            $translation_def->lang = $setting->value;
            $translation_def->lang_key = $key;
            $translation_def->lang_value = $key;
            $translation_def->save();
        }
    }

    // Fallback to returning the key if no translation found
    return $key;
}
function get_storage_url($path)
{ 
    return Storage::url($path);
}

function getDefaultLanguage()
{
    // Check if session already has a default language
    if (Session::has('default_language')) {
        $user_language = Session::get('default_language');
        Carbon::setLocale($user_language);
       
        return $user_language;
    }

    function getUserLocation($ip)
    {
        // uncommment the belwo code
        // $response = \Illuminate\Support\Facades\Http::get('http://ip-api.com/json/' . $ip)->json();

        // uncommment the belwo code
        // return $response;
    }


    // Determine the country code based on the user's IP address
    $userIp = request()->ip() ?? '';
    $current_location = getUserLocation($userIp);
    // uncommment the belwo code
    // $countryCode = ($current_location['status'] === 'fail') ? null : $current_location['countryCode'];

    // Check if language exists for the country code
    // uncommment the belwo code
    // if ($countryCode) {
    //     $language = Language::where('status', 1)->where('code', $countryCode)->first();
    //     if ($language) {
    //         // Store the found language in the session
    //         Session::put('default_language', $language->code);
    //         Carbon::setLocale($language->code); // Set locale for Carbon
    //         return $language->code;
    //     }
    // }

    // Fetch default language setting from the database
    $setting = Setting::where('type', 'default_language')->first();
    $default_language = $setting->value ?? 'US';

    // Store the default language in the session
    Session::put('default_language', $default_language);
    Carbon::setLocale($default_language); // Set locale for Carbon

    return $default_language;
}

