<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Degree; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;

class DegreesController extends Controller
{
    public function index(){
        return Inertia::render('Backend/Degrees/Index');
    }

    public function degrees(Request $request){
        $degrees = DB::table('degrees');
        if ($request->degree && $request->degree != "null") {
            $degrees->where(
                'degree',
                'LIKE',
                '%' . $request->degree . '%',
            );
        }
        $degrees = $degrees->orderByDesc('created_at')->paginate(20); 
        return  $degrees;
    }

    public function edit($id){
        return Inertia::render('Backend/Degrees/Update',[
            'degreeId' => $id
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'degree'       => 'required|string|max:250',     
        ]);

        if($request->degree_id)
        {
            $degree = Degree::where('id',$request->degree_id)->first();
            $degree->degree = $request->degree; 
            $degree->save();
        }else
        {
            $degree = new Degree;
            $degree->id = Str::orderedUuid();
            $degree->degree = $request->degree; 
            $degree->save();
        }
            return 'success';
    }


    public function show($id){ 
        $degree = Degree::findOrFail($id);
        return $degree;
    }
 

    public function delete($id){
        $degree = Degree::findOrFail($id);
        $degree->delete();
        return 'success';
    }
    public function degrees_pluck(){
        $degrees = Degree::pluck('degree','id');
        $translated_degrees = $degrees->map(function ($degree, $id) {
            return translate($degree); // translating degrees
        });
        return $translated_degrees;
    }
}
