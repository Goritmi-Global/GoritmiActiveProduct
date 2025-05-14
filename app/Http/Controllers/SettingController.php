<?php
namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Certificate;
use App\Models\HeroSection;
use App\Models\ParticipantAppliedCertificate;
use App\Models\Seo;
use App\Models\Setting;
use App\Models\Upload;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Setting/Index');
    }

    public function setting_add()
    {
        return Inertia::render('Backend/Setting/Create');
    }

    public function setting_edit($id)
    {
        return Inertia::render('Backend/Setting/Update', [
            'settingId' => $id,
        ]);
    }

    public function setting(Request $request)
    {
        return Setting::all();
    }

    public function setting_store(Request $request)
    {
        if ($request['type'] == 'site') {

            $request->validate([
                'site_name' => 'required',
                'type'      => 'required',
            ]);

            $array = ['site_name' => $request->site_name];
            $value = json_encode($array);

            if ($request->id == "") {

                $Setting     = new Setting;
                $Setting->id = Str::orderedUuid();

            } else {
                $Setting = Setting::findOrFail($request->id);
            }
            $Setting->type  = $request->type;
            $Setting->value = $value;
            $Setting->save();

            return 'success';

        } else if ($request['type'] == 'AboutUs') {

            $request->validate([
                'paragraph1' => 'required',
                'paragraph2' => 'required',
                'type'       => 'required',
            ]);

            $array = ['paragraph1' => $request->paragraph1, 'paragraph2' => $request->paragraph2];
            $value = json_encode($array);

            if ($request->id == "") {
                $Setting     = new Setting;
                $Setting->id = Str::orderedUuid();
            } else {
                $Setting = Setting::findOrFail($request->id);
            }

            $Setting->type  = $request->type;
            $Setting->value = $value;
            $Setting->save();
            return 'success';

        } else if ($request['type'] == 'Footerlinks') {

            $request->validate([
                'contents' => 'required',
                'link'     => 'required',
                'type'     => 'required',
            ]);

            $array = ['contents' => $request->content, 'link' => $request->link];
            $value = json_encode($array);

            $Setting        = new Setting;
            $Setting->id    = Str::orderedUuid();
            $Setting->type  = $request->type;
            $Setting->value = $value;
            $Setting->save();

            return $value;

        }

    }

    public function setting_show($id)
    {
        $portfolio = Setting::findOrFail($id);
        return $portfolio;
    }

    public function setting_update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'image'    => 'nullable|mimes:jpg,png',
            'category' => 'required|string|max:255',
            'link'     => 'required|string|max:255',
        ]);

        $Setting       = Setting::findOrFail($request->id);
        $Setting->name = $request->name;

        if ($request->image) {
            // Deleting the old image
            Storage::delete($Portfolio->image);

            $Setting->image = $request->image->store('portfolioImages');
        }
        $Setting->category = $request->category;
        $Setting->link     = $request->link;
        $Setting->save();

        return 'success';
    }

    public function setting_delete($id)
    {
        $Setting = Setting::findOrFail($id);
        // Deleting the image
        Storage::delete($Setting->image);
        $Setting->delete();
        return 'success';
    }

    public function SiteName()
    {
        $data = Setting::where('type', 'site')->first();
        return json_decode($data);

    }

    public function AboutUs()
    {
        $data = Setting::where('type', 'AboutUs')->first();
        return json_decode($data);

    }

    public function Footerlinks()
    {
        $data = Setting::where('type', 'Footerlinks')->get();
        return json_decode($data);

    }

    public function external_website_index()
    {
        return Inertia::render('Backend/ExternalWebsite/Create');
    }

    public function seo_store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'keywords'    => 'required',
            'description' => 'required',

        ]);
        $Exist_seo = $seo_exist = DB::table('seo')->first();

        if ($Exist_seo) {

            $seo_exist = DB::table('seo')->first();
            $Seo       = Seo::findOrFail($seo_exist->id);

        } else {

            $Seo     = new Seo;
            $Seo->id = Str::orderedUuid();

        }

        $Seo->title       = $request->title;
        $Seo->keyword     = $request->keywords;
        $Seo->description = $request->description;

        if ($request->file('image')) {
            // if ($Seo->image) {
            //     Storage::delete($Seo->image);
            //     // Storage::disk('real_public')->delete($Seo->image);
            // }

            $existingInUploads = Upload::where('id', $Seo->image)->first();
            if ($existingInUploads) {
                Storage::delete($existingInUploads->file_name);
                $existingInUploads->delete();
            }

            if ($request->file('image')) {

                $file_name_with_path        = $request->file('image')->store('SeoImage');
                $upload                     = new Upload;
                $upload->file_original_name = $request->file('image')->getClientOriginalName();
                $upload->file_name          = $file_name_with_path;
                $upload->file_size          = $request->file('image')->getSize();
                $upload->extension          = $request->file('image')->extension();
                $upload->type               = $request->file('image')->getClientMimeType();
                $upload->save();
                $Seo->image = $upload->id;
            }
            // $Seo->image = $request->file('image')->store('SeoImage');
        }
        $Seo->save();
        return 'success';
    }

    public function seo()
    {
        $seo = DB::table('seo')->first();
        // $seo->image = get_storage_url($seo->image);
        if ($seo->image) {
            $upload = Upload::where('id', $seo->image)->first();
            if ($upload) {
                $seo->image = $upload->file_name ? get_storage_url($upload->file_name) : '';
            }
        }
        return $seo;
    }
    public function logo_store(Request $request)
    {

        $request->validate([
            'logo' => 'required',
        ]);

        if ($request->logo) {
            if (auth()->user()->role == 'admin') {
                $Logo = Setting::where('type', 'logo')->where('user_id', null)->first();
                if (! $Logo) {
                    $Logo     = new Setting;
                    $Logo->id = Str::orderedUuid();
                }

            } else {
                $Logo = Setting::where('type', 'logo')->where('user_id', auth()->user()->id)->first();
                if (! $Logo) {
                    $Logo          = new Setting;
                    $Logo->id      = Str::orderedUuid();
                    $Logo->user_id = auth()->user()->id;
                }
            }

            $Logo->type = 'logo';

            if ($request->file('logo')) {

                $file_name_with_path        = $request->file('logo')->store('WebsiteLogos');
                $upload                     = new Upload;
                $upload->file_original_name = $request->file('logo')->getClientOriginalName();
                $upload->file_name          = $file_name_with_path;
                $upload->file_size          = $request->file('logo')->getSize();
                $upload->extension          = $request->file('logo')->extension();
                $upload->type               = $request->file('logo')->getClientMimeType();
                $upload->save();
                $Logo->value = $upload->id;
            }

            $Logo->save();

        }
    }

    public function logo()
    {
        if (auth()->user()->role == 'admin') {
            $logo = Setting::where('type', 'logo')->where('user_id', null)->first();
        } else {
            $logo = Setting::where('type', 'logo')->where('user_id', auth()->user()->id)->first();
        }

        if ($logo) {
            $upload = Upload::where('id', $logo->value)->first();
            if ($upload) {
                $logo->image = $upload->file_name ? get_storage_url($upload->file_name) : '';

            }
        }

        return $logo;
    }

    public function website_name_store(Request $request)
    {

        $request->validate([
            'website_name' => 'required|string|max:255',
        ]);

        if ($request->website_name) {
            $website_name = Setting::where('type', 'website_name')->first();
            if (! $website_name) {
                $website_name     = new Setting;
                $website_name->id = Str::orderedUuid();
               
            }

            $website_name->user_id = auth()->user()->id;
            $website_name->type  = 'website_name';
            $website_name->value = $request->website_name;
            $website_name->save();

        }
    }

    public function website_name_fetch()
    {
        $website_name = Setting::where('type', 'website_name')->first();
        return $website_name;
    }

    public function website_footer_store(Request $request)
    {

        $request->validate([
            'website_footer' => 'required|string|max:255',
        ]);

        if ($request->website_footer) {
            $website_footer = Setting::where('type', 'website_footer')->first();
            if (! $website_footer) {
                $website_footer     = new Setting;
                $website_footer->id = Str::orderedUuid();
            }

            $website_footer->type  = 'website_footer';
            $website_footer->value = $request->website_footer;
            $website_footer->save();

        }
    }

    public function website_footer_fetch()
    {
        $website_footer = Setting::where('type', 'website_footer')->first();
        return $website_footer;
    }

    public function fetchAllHeroSections()
    {

        if (auth()->user()->role == 'admin') {
            $heroes = HeroSection::where('user_id', null)->get(); // Fetch all hero sections
        } else {
            $heroes = HeroSection::where('user_id', auth()->user()->id)->get(); // Fetch all hero sections
        }
        return response()->json($heroes);
    }
    public function storeAllHeroSections(Request $request)
    {
         
        $rules    = [];
        $messages = [];

        // Dynamically build validation rules and custom messages
        foreach ($request->all() as $lang => $data) {
            if (! empty($data['hero_title']) || ! empty($data['description'])) {
                $rules["$lang.hero_title"]  = 'required_without:' . $lang . '.description|string|max:255';
                $rules["$lang.description"] = 'required_without:' . $lang . '.hero_title|string|max:1000';

                // Custom error messages
                $messages["$lang.hero_title.required_without"]  = translate("The title field is required when the description is filled for the language: $lang.");
                $messages["$lang.description.required_without"] = translate("The description field is required when the title is filled for the language: $lang.");
                $messages["$lang.hero_title.string"]            = translate("The title field is required when the description is filled");
                $messages["$lang.description.string"]           = translate("The description field is required when the title is filled");
            }
        }

        // Validate the request
        $validatedData = $request->validate($rules, $messages);

        // Clear existing records only for the current user role
        HeroSection::where(function ($query) {
            if (auth()->user()->role === 'admin') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', auth()->user()->id);
            }
        })->delete();

        // Save new records based on the user role
        foreach ($request->all() as $lang => $data) {
            if (! empty($data['hero_title']) || ! empty($data['description'])) {
                $hero              = new HeroSection();
                $hero->id          = Str::orderedUuid();
                $hero->lang        = $lang;
                $hero->title       = $data['hero_title'];
                $hero->description = $data['description'];

                // Set user_id based on role
                // $hero->user_id = auth()->user()->role === 'admin' ? null : auth()->user()->id;
                $hero->user_id = auth()->user()->id;

                $hero->save();
            }
        }

        return 'success';
    }

// public function storeAllHeroSections(Request $request)
// {
//     $rules = [];
//     $messages = [];

//     // Dynamically build validation rules and custom messages
//     foreach ($request->all() as $lang => $data) {
//         if (!empty($data['hero_title']) || !empty($data['description'])) {
//             $rules["$lang.hero_title"] = 'required_without:' . $lang . '.description|string|max:255';
//             $rules["$lang.description"] = 'required_without:' . $lang . '.hero_title|string|max:1000';

//             // Custom error messages
//             $messages["$lang.hero_title.required_without"] = translate("The title field is required when the description is filled for the language: $lang.");
//             $messages["$lang.description.required_without"] = translate("The description field is required when the title is filled for the language: $lang.");
//             $messages["$lang.hero_title.string"] = translate("The title field is required when the description is filled");
//             $messages["$lang.description.string"] = translate("The description field is required when the title is filled");
//         }
//     }

//     // Validate the request
//     $validatedData = $request->validate($rules, $messages);

//     // Clear the HeroSection table
//     HeroSection::truncate();

//     // Loop through each record in the request and save to the database
//     foreach ($request->all() as $lang => $data) {
//         if (!empty($data['hero_title']) || !empty($data['description'])) {
//             $hero = new HeroSection();
//             $hero->id = Str::orderedUuid(); // Generate a unique ID
//             $hero->lang = $lang; // Set the language from the key
//             $hero->title = $data['hero_title']; // Set the title
//             $hero->description = $data['description']; // Set the description

//             if(auth()->user()->role == 'admin')
//             {
//                 $hero->user_id = NULL;
//             }else
//             {
//                 $hero->user_id = auth()->user()->id;
//             }
//                 $hero->save(); // Save the record to the database
//             }
//     }

//     return 'success';
// }

    // public function hero_store(Request $request)
    // {
    //     $request->validate([
    //         'hero_title' => 'required',
    //         'description' => 'required',
    //     ]);

    //     HeroSection::where('lang', $request->selected_language_code)->delete();

    //     $Hero = new HeroSection;
    //     $Hero->id = Str::orderedUuid();

    //     $Hero->title = $request->hero_title;
    //     $Hero->description = $request->description;
    //     $Hero->lang = $request->selected_language_code;
    //     $Hero->save();
    //     return 'success';

    // }

    // public function hero_fetch($language_code)
    // {
    //     $hero = HeroSection::where('lang', $language_code)->get();

    //     return $hero;
    // }

    public function get_hero_section_data_selected_laguage($language_code, $user_id = '')
    { 
        if ($user_id) {
            // dd($user_id);
            $hero = HeroSection::where('lang', $language_code)->where('user_id', $user_id)->first();

        } else {
            $hero = HeroSection::where('lang', $language_code)->whereNULL('user_id')->first();

        }

        return $hero;
    }

    // the below code is for Email
    public function welcome_email()
    {
        return Inertia::render('Backend/Emails/Welcome');
    }

    public function welcome_email_store(Request $request)
    {

        $request->validate([
            'subject'           => 'required',
            'email_description' => ['required', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail("Description cannot be empty or contain only HTML tags.");
                }
            }],
        ]);

        if ($request->email_description) {
            $email_data_array = [];

            $email_data_array[] = [
                'subject'     => $request->subject,
                'description' => $request->email_description,
            ];

            $Email = Setting::where('type', 'welcome_email')->first();
            if (! $Email) {
                $Email     = new Setting;
                $Email->id = Str::orderedUuid();
            }

            $Email->type  = 'welcome_email';
            $Email->value = json_encode($email_data_array);
            $Email->save();

        }
    }
    // sales representative email 
    public function sales_representative_email()
    {
        return Inertia::render('Backend/Emails/SalesRepresentativeEmail');
    }

    public function sales_representative_email_store(Request $request)
    {

        $request->validate([
            'subject'           => 'required',
            'email_description' => ['required', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail("Description cannot be empty or contain only HTML tags.");
                }
            }],
        ]);

        if ($request->email_description) {
            $email_data_array = [];

            $email_data_array[] = [
                'subject'     => $request->subject,
                'description' => $request->email_description,
            ];

            $Email = Setting::where('type', 'sales_representative_email')->first();
            if (! $Email) {
                $Email     = new Setting;
                $Email->id = Str::orderedUuid();
            }

            $Email->type  = 'sales_representative_email';
            $Email->value = json_encode($email_data_array);
            $Email->save();

        }
    }

    public function sales_representative_email_fetch()
    {
        $Email = Setting::where('type', 'sales_representative_email')->first();

        return $Email;
    }

    // certificate apply email ///////////////////////////////////////////////

    public function certificate_apply_email()
    {
        $subscribing_ids = ParticipantAppliedCertificate::where('certificate_id', '!=', '')->pluck('certificate_id');
        $subscriber      = Certificate::whereIn('id', $subscribing_ids)->pluck('title', 'id');

        return Inertia::render('Backend/Emails/CertificateApply', ['certificate_subscribers' => $subscriber]);

    }

    public function certficiate_apply_email_store(Request $request)
    {

        $request->validate([
            'subject'           => 'required',
            'email_description' => ['required', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail("Description cannot be empty or contain only HTML tags.");
                }
            }],
        ]);
        if ($request->email_description) {
            $email_data_array = [];

            $email_data_array[] = [
                'subject'     => $request->subject,
                'description' => $request->email_description,
            ];

            $Email = Setting::where('type', 'certificate_apply_email')->first();
            if (! $Email) {
                $Email     = new Setting;
                $Email->id = Str::orderedUuid();
            }

            $Email->type  = 'certificate_apply_email';
            $Email->value = json_encode($email_data_array);
            $Email->save();

        }
    }

    public function certficiate_apply_email_fetch()
    {
        $Email = Setting::where('type', 'certificate_apply_email')->first();

        return $Email;
    }

    // certificate apply email ///////////////////////////////////////////////

    public function welcome_email_fetch()
    {
        $Email = Setting::where('type', 'welcome_email')->first();
        return $Email;
    }

    public function test_email()
    {
        $Email      = Setting::where('type', 'welcome_email')->first();
        $email_test = json_decode($Email->value);

        $email_test = $email_test[0]->description;
        $user       = "Arqam";

        $healthy = ['${USER}'];
        $yummy   = [$user];

        $email_test = str_replace($healthy, $yummy, $email_test);

        return view('TestEmail', compact('email_test'));
    }
    // resend_credentials_email  ///////////////////////////////////////////////

    public function resend_credentials_email()
    {
        return Inertia::render('Backend/Emails/ResendCredentials');
    }
    public function resend_credentials_email_fetch()
    {
        $Email = Setting::where('type', 'resend_credentials_email')->first();

        return $Email;
    }

    public function resend_credentials_email_store(Request $request)
    {
        $request->validate([
            'subject'           => 'required',
            'email_description' => ['required', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail("Description cannot be empty or contain only HTML tags.");
                }
            }],
        ]);

        if ($request->email_description) {
            $email_data_array = [];

            $email_data_array[] = [
                'subject'     => $request->subject,
                'description' => $request->email_description,
            ];

            $Email = Setting::where('type', 'resend_credentials_email')->first();
            if (! $Email) {
                $Email     = new Setting;
                $Email->id = Str::orderedUuid();
            }

            $Email->type  = 'resend_credentials_email';
            $Email->value = json_encode($email_data_array);
            $Email->save();

        }
    }

    // Reset password email functions

    public function Reset_password_email()
    {
        return Inertia::render('Backend/Emails/ResetPassword');
    }

    public function Reset_password_email_fetch()
    {
        $Email = Setting::where('type', 'reset_password_email_section')->first();

        return $Email;
    }
    public function reset_password_email_store(Request $request)
    {
        $request->validate([
            'subject'           => 'required',
            'email_description' => ['required', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail("Description cannot be empty or contain only HTML tags.");
                }
            }],
        ]);

        if ($request->email_description) {
            $email_data_array = [];

            $email_data_array[] = [
                'subject'     => $request->subject,
                'description' => $request->email_description,
            ];

            $Email = Setting::where('type', 'reset_password_email_section')->first();
            if (! $Email) {
                $Email     = new Setting;
                $Email->id = Str::orderedUuid();
            }

            $Email->type  = 'reset_password_email_section';
            $Email->value = json_encode($email_data_array);
            $Email->save();

        }
    }

    // Collection email functions
    public function collection_email()
    {
        return Inertia::render('Backend/Emails/Collection');
    }

    public function collection_email_fetch()
    {
        $Email = Setting::where('type', 'collection_email_section')->first();

        return $Email;
    }
    public function collection_email_store(Request $request)
    {

        $request->validate([
            'subject'           => 'required',
            'email_description' => ['required', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail("Description cannot be empty or contain only HTML tags.");
                }
            }],
        ]);

        if ($request->email_description) {
            $email_data_array = [];

            $email_data_array[] = [
                'subject'     => $request->subject,
                'description' => $request->email_description,
            ];

            $Email = Setting::where('type', 'collection_email_section')->first();
            if (! $Email) {
                $Email     = new Setting;
                $Email->id = Str::orderedUuid();
            }

            $Email->type  = 'collection_email_section';
            $Email->value = json_encode($email_data_array);
            $Email->save();

        }
    }

    // weekly_report email functions
    public function weekly_report_email()
    {
        // $subscribers = Participant::where('certificate_id', '!=' ,'')->pluck('name','id');
        // $subscribers = ParticipantAppliedCertificate::where('certificate_id', '!=' ,'')->get();

        $subscribing_ids = ParticipantAppliedCertificate::where('certificate_id', '!=', '')->pluck('certificate_id');
        $subscriber      = Certificate::whereIn('id', $subscribing_ids)->pluck('title', 'id');

        // in the list the Mailing List id will be zero (0)
        // $subscriber->push("Mailing list");

        return Inertia::render('Backend/Emails/WeeklyReport', ['certificate_subscribers' => $subscriber]);

    }

    public function weekly_report_email_fetch()
    {
        $Email = Setting::where('type', 'weekly_report_email_section')->first();

        return $Email;
    }
    public function weekly_report_email_store(Request $request)
    {

        $request->validate([
            'subject'           => 'required',
            'email_description' => ['required', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail("Description cannot be empty or contain only HTML tags.");
                }
            }],
        ]);

        if ($request->email_description) {
            $email_data_array = [];

            $email_data_array[] = [
                'description' => $request->email_description,
            ];

            $Email = Setting::where('type', 'weekly_report_email_section')->first();
            if (! $Email) {
                $Email     = new Setting;
                $Email->id = Str::orderedUuid();
            }

            $Email->type  = 'weekly_report_email_section';
            $Email->value = json_encode($email_data_array);
            $Email->save();

        }
    }

    // carousel

    // public function carousel_store(Request $request)
    // {
    //     $existing_text = Setting::where('type', 'carousel_text')->first();

    //     $carousel_array = [];
    //     if ($existing_text) {
    //         $existing_carousel = json_decode($existing_text->value, true);
    //         foreach ($existing_carousel as $record) {
    //             $carousel_array[] = [
    //                 'image_id' => $record['image_id'],
    //                 'description' => $record['description'],
    //             ];
    //         }
    //         $existing_text->delete();
    //     }

    //     // $request->validate([
    //     //     'image' => 'required|image',
    //     // ]);
    //     $data = substr($request->image, strpos($request->image, ',') + 1);
    //     $data = base64_decode($data);

    //     $upload_ids = [];

    //     $existing_text = new Setting;
    //     $existing_text->id = Str::orderedUuid();

    //     $data = substr($request->image, strpos($request->image, ',') + 1);
    //     $data = base64_decode($data);

    //     $image_name_with_path = 'CarouselImages/' . Str::random(40) . '.png';
    //     Storage::put($image_name_with_path, $data);

    //     $Upload = new Upload;
    //     $Upload->file_original_name = $image_name_with_path;

    //     $Upload->extension = 'png';
    //     $Upload->type = 'image/png';
    //     $Upload->file_name = $image_name_with_path;

    //     $Upload->save();

    //     $carousel_array[] = [
    //         'image_id' => $Upload->id,
    //         'description' => "",
    //     ];

    //     $existing_text->type = 'carousel_text';
    //     $existing_text->value = json_encode($carousel_array);
    //     $existing_text->save();

    //     return 'success';

    // }
    // public function carousel_text_update(Request $request)
    // {
    //     $existing_text = Setting::where('type', 'carousel_text')->first();

    //     // the data existing in database
    //     $existing_record = (array) json_decode($existing_text->value);
    //     $existing_images_ids = [];
    //     foreach ($existing_record as $machine1) {
    //         $existing_images_ids[] = $machine1->image_id;
    //     }

    //     // The requested data coming from the front side
    //     $requested_record = (array) json_decode($request->text_list);
    //     $requested_images_ids = [];
    //     foreach ($requested_record as $machine2) {
    //         $requested_images_ids[] = $machine2->image_id;
    //     }

    //     // the below line code is for to confirm the deleted record id
    //     $removed_image = array_diff($existing_images_ids, $requested_images_ids);

    //     // the below check (if) used to remove the image from the folder and from the upload db table
    //     if ($removed_image) {
    //         $Uploaded = Upload::where('id', $removed_image)->first();
    //         Storage::delete($Uploaded->file_name);
    //         $Uploaded->delete();
    //     }

    //     // below code is used to adjust the new record and remove the existing record
    //     $carousel_array = [];
    //     if ($existing_text) {
    //         $exit_record = json_decode($request->text_list, true);

    //         foreach ($exit_record as $abc) {
    //             $carousel_array[] = [
    //                 'image_id' => $abc['image_id'],
    //                 'description' => $abc['description'],
    //             ];
    //         }
    //         $existing_text->delete();
    //     }

    //     $existing_text = new Setting;
    //     $existing_text->id = Str::orderedUuid();

    //     $existing_text->type = 'carousel_text';
    //     $existing_text->value = json_encode($carousel_array);
    //     $existing_text->save();

    //     return 'success';

    // }
    // public function carousel_text_fetch()
    // {
    //     $carousels = Setting::where('type', 'carousel_text')->first();
    //     if ($carousels) {

    //         $carousel_decoded_record = (array) json_decode($carousels->value);
    //         foreach ($carousel_decoded_record as $record) {

    //             $uploaded_image = Upload::where('id', $record->image_id)->first();

    //             if ($uploaded_image) {
    //                 $record->image =  get_storage_url($uploaded_image->file_name);
    //             }

    //         }
    //         return $carousel_decoded_record;
    //     }
    // }

    // public function carousel_dragging($newPositions)
    // {
    //     $newPositions = json_decode($newPositions);

    //     foreach ($newPositions as $newPosition) {
    //         $carousel = Carousel::findOrFail($newPosition->event_id);
    //         $carousel->order_id = $newPosition->order_id;
    //         $carousel->save();
    //     }

    //     return 'success';
    // }

    // front end
    public function fronend_carousel_fetch($id = null)
    {
        if ($id) {
            $carousels = Carousel::orderBy('order_id', 'asc')
            ->where('user_id', $id)
            ->get();        
        } else {
            $carousels = Carousel::orderBy('order_id', 'asc')
                ->whereNull('user_id')
                ->get();
        } 
        foreach ($carousels as $record) {
            $uploaded_image = Upload::where('id', $record->image_id)->first();
            if ($uploaded_image) {
                $record->image = get_storage_url($uploaded_image->file_name);
            }
        }

        return $carousels;
    }

    public function carousel_store(Request $request)
    {
        $request->validate([
            'image'       => 'required',
            'description' => 'nullable',

        ]);

        $Carousel     = new Carousel;
        $Carousel->id = Str::orderedUuid();

        $data = substr($request->image, strpos($request->image, ',') + 1);
        $data = base64_decode($data);

        $image_name_with_path = 'CarouselImages/' . Str::random(40) . '.png';
        Storage::put($image_name_with_path, $data);

        $Upload                     = new Upload;
        $Upload->file_original_name = $image_name_with_path;

        $Upload->extension = 'png';
        $Upload->type      = 'image/png';
        $Upload->file_name = $image_name_with_path;

        $Upload->save();

        $Carousel->image_id = $Upload->id;

        // Set user_id based on role
        if (auth()->user()->role == 'admin') {
            $Carousel->user_id = null;
        } else {
            $Carousel->user_id = auth()->user()->id;

        }
        $Carousel->save();

        return 'success';
    }

    public function carousel_fetch()
    {
        if (auth()->check()) {

            if (auth()->user()->role == 'admin') {
                $carousels = Carousel::orderBy('order_id', 'asc')
                    ->whereNull('user_id')
                    ->get();
            } else {
                $carousels = Carousel::orderBy('order_id', 'asc')
                    ->where('user_id', auth()->user()->id)
                    ->get();
            }
        } else {
            $carousels = Carousel::orderBy('order_id', 'asc')
                ->whereNull('user_id')
                ->get();
        }

        foreach ($carousels as $record) {
            $uploaded_image = Upload::where('id', $record->image_id)->first();
            if ($uploaded_image) {
                $record->image = get_storage_url($uploaded_image->file_name);
            }
        }

        return $carousels;
    }

    // public function carousel_text_update(Request $request)
    // {

    //     foreach (json_decode($request->text_list) as $record) {
    //         $carousel = Carousel::where('id', $record->id)->first();
    //         $carousel->description = $record->description;
    //         $carousel->save();
    //     }
    //     return 'success';
    // }
    public function carousel_text_update(Request $request)
    {
        foreach (json_decode($request->text_list) as $record) {
            $carousel = Carousel::where('id', $record->id);

            // Check role and filter by user_id if not admin
            if (auth()->user()->role !== 'admin') {
                $carousel->where('user_id', auth()->user()->id);
            } else {
                $carousel->whereNull('user_id');
            }

            $carousel = $carousel->first();

            if ($carousel) {
                $carousel->description = $record->description;
                $carousel->save();
            }
        }

        return 'success';
    }

    public function carousel_delete($id)
    {

        $carousel = Carousel::where('id', $id)->first();
        if ($carousel->image_id) {
            $upload = Upload::where('id', $carousel->image_id)->first();
            if ($upload) {
                Storage::delete($upload->file_name);
                $upload->delete();
            }

        }
        $carousel->delete();

        return 'success';
    }

    // public function carousel_dragging($newPositions)
    // {
    //     $newPositions = json_decode($newPositions);

    //     foreach ($newPositions as $newPosition) {
    //         $carousel = Carousel::findOrFail($newPosition->event_id);
    //         $carousel->order_id = $newPosition->order_id;
    //         $carousel->save();
    //     }

    //     return 'success';
    // }
    public function carousel_dragging($newPositions)
    {
        $newPositions = json_decode($newPositions);

        foreach ($newPositions as $newPosition) {
            // Fetch the carousel record based on role
            $carouselQuery = Carousel::where('id', $newPosition->event_id);

            if (auth()->user()->role !== 'admin') {
                $carouselQuery->where('user_id', auth()->user()->id);
            } else {
                $carouselQuery->whereNull('user_id');
            }

            $carousel = $carouselQuery->first();

            if ($carousel) {
                $carousel->order_id = $newPosition->order_id;
                $carousel->save();
            }
        }

        return 'success';
    }

}
