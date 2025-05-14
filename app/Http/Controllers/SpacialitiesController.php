<?php

namespace App\Http\Controllers;

use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use DB;

class SpacialitiesController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Specialities/Index');
    }

    public function specialities(Request $request)
    {
        
        $specialities = DB::table('specialities');
        if ($request->speciality && $request->speciality != "null") {
            $specialities->where(
                'speciality',
                'LIKE',
                '%' . $request->speciality . '%',
            );
        }
        $specialities = $specialities->orderByDesc('created_at')->paginate(20); 

        foreach ($specialities as $speciality) {
            $speciality->image = get_storage_url($speciality->image) ?? "";
            if($speciality->show_speciality == 1)
            {
                $speciality->show_speciality = "true";
            }else
            {
                $speciality->show_speciality = 'false';
            }
            if($speciality->status == 1)
            {
                $speciality->status = "true";
            }else
            {
                $speciality->status = 'false';
            }
        }
        return $specialities;
    }
    public function specialities_list(Request $request)
    {
        $specialities = Speciality::where('status',1)->where('show_speciality',1)->get();
        foreach ($specialities as $speciality) {
            $speciality->image = get_storage_url($speciality->image) ?? "";
            
        }
        return $specialities;
    }

    public function store(Request $request)
    { 
        $request->validate([
            'speciality' => 'required|string|max:250',
            'image' => 'nullable',
            "seo_title" => "nullable",
            "seo_keywords" => "nullable",
            "seo_description" => "nullable",
             
        ]);
        if ($request->speciality_id) {
            $speciality = Speciality::where('id', $request->speciality_id)->first();

        } else {
            $speciality = new Speciality;
            $speciality->id = Str::orderedUuid();
        }
        $speciality->speciality = $request->speciality;
        if ($request->image) {  
            $data = substr($request->image, strpos($request->image, ',') + 1);
            $data = base64_decode($data); 
            $image_with_path = 'SpecialitiesImages/' . Str::random(40) . '.png';
            Storage::put($image_with_path, $data); 
            $speciality->image = $image_with_path; 
        } 

        $speciality->seo_title = $request->seo_title;
        $speciality->seo_keywords = $request->seo_keywords;
        $speciality->seo_description = $request->seo_description;
        $speciality->save();

        return 'success';
    }

    public function show($id)
    {
        $speciality = Speciality::findOrFail($id);
        $speciality->image = get_storage_url($speciality->image) ?? "";
        return $speciality;
    }

    public function delete($id)
    {
        $speciality = Speciality::findOrFail($id);
        if ($speciality->image) {
            Storage::delete($speciality->image);
        }
        $speciality->delete();
        return 'success';
    }

    public function speciailies_pluck()
    {
        $specialities = Speciality::where('status', 1)->pluck('speciality', 'id');
          
        $translated_specialities = $specialities->map(function ($speciality, $id) {
            return translate($speciality); // translating specialities
        });
        

        return $translated_specialities;
    }
    public function specialty_hide_show(Request $request)
    { 
        $specialty = Speciality::where('id',$request->speciality_id)->first();
        
        if($request->speciality_show_status == "true")
        { 
            $specialty->show_speciality = 1;
        }else
        { 
            $specialty->show_speciality = 0;
        } 
        $specialty->save();
        return 'success';
    }
    public function status_changer(Request $request)
    { 
        $specialty = Speciality::where('id',$request->speciality_id)->first();
        if($request->status == "true")
        {
            $specialty->status = 1;
        }else
        {
            $specialty->status = 0;
        }
         
        $specialty->save();
        return 'success';

    }
     
}
