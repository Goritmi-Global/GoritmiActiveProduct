<?php

namespace App\Http\Middleware;

use Auth;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Translation;
use App\Models\Upload;
use Inertia\Middleware;
use App\Models\User; 
use Illuminate\Support\Str;  
 
use DB; 
use Illuminate\Support\Facades\Route;  


class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared with every Inertia response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        // Get or create an authentication token
        $auth_token = $request->session()->get('auth_token'); 
        // dd("tste",$auth_token,session()->get('auth_token'));
        if (!$auth_token && Auth::user()) {
            $auth_token = Auth::user()->createToken('myAppToken')->plainTextToken;
            session()->put('auth_token', $auth_token);
        }

        // Get default language and its translation strings
        $default_language = getDefaultLanguage();
 
        $lang_data = Translation::where('lang', $default_language)->pluck('lang_value', 'lang_key');
        // Get website name from settings
        $website_name = Setting::where('type', 'website_name')->value('value') ?? '';

        // Get website logo from settings
        if (auth()->check()) {
            if (auth()->user()->role == 'Admin' || auth()->user()->role == 'admin') {
                $logo = Setting::where('type', 'logo')->whereNULL('user_id')->first();
            } else {
                $logo = Setting::where('type', 'logo')->where('user_id', auth()->user()->id)->first();
            }
        }  

        $website_logo_path = Setting::where('type', 'logo')->value('value');
        if($website_logo_path)
        {
            $uploaded_logo_file_name = Upload::where('id',$website_logo_path)->first();
            // dd($upload);
            $logo = $website_logo_path ? get_storage_url($uploaded_logo_file_name->file_name) : '';
        }

        
// dd($logo);
        // Load authenticated user and their image if available
      
        if (auth()->check()) {
       
           
            $User = auth()->user();
            if ($User->image) {
                $upload = Upload::where('id', $User->image)->first();
                if ($upload) {
                    $User->image = $upload->file_name ? get_storage_url($upload->file_name) : '';
                }
                // dd($User->image);
            }
        }

        return array_merge(parent::share($request), [
            'auth_token'       => $auth_token,
            'default_language' => $default_language,
            'lang_data'        => $lang_data,
            'website_name'     => $website_name,
            'logo'             => $logo ?? '',
            'user'             => $User ?? null,
        ]);
    }
}
