<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/User/Index');
    }

    public function store(Request $request)
    {
        
        if($request->user_id)
        {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'role' => 'required', 
                'email'=> 'required|unique:users,email,' . $request->user_id, 
            ]);
            $User = User::findOrFail($request->user_id);
        }else
        {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'role' => 'required', 
                'email'=> 'required|unique:users,email',
                'password' => 'required|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required|required_with:password|same:password',
            ]);
            $User = new User;

        }
        $User->first_name = $request->first_name;
        $User->last_name = $request->last_name;
        $User->email = $request->email;
        $User->role = $request->role;
        $User->password = Hash::make($request->password);
         
        $User->save();
 
        
        return 'success';

    }


    public function users_fetch()
    {

        $users = User::all();
        $users_ids = $users->pluck('id');

        return [
            'users' => $users,
            'users_ids' => $users_ids,
        ];

    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    public function delete($id)
    {

        $user = User::findOrFail($id);
        $user->delete();
        return 'success';
    }

    public function profile_show($id)
    {
        $Users = User::withTrashed()->find($id);
        if ($Users->image) {
            $upload = Upload::where('id', $Users->image)->first();
            if ($upload) {
                $Users->image = $upload->file_name ? get_storage_url($upload->file_name) : '';
            }
        }
        return $Users;
    }
    public function edit($id)
    {
        return Inertia::render('Backend/User/Update', [
            'userId' => $id,
        ]);
    }
    public function profile_update(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'current_password' => [
                'nullable',
                'string',
                'max:255',
                'required_with:new_password,confirm_password',
            ],
            'new_password'     => [
                'nullable',
                'string',
                'required_with:current_password',
                'same:confirm_password',
            ],
            'confirm_password' => [
                'nullable',
                'string',
                'required_with:new_password',
                'same:new_password',
            ],
            'image'            => 'nullable',
        ]);

        $user = User::find(auth()->id());

        // Handle password update logic only if password fields are present
        if ($request->filled(['current_password', 'new_password', 'confirm_password'])) {
            if (! Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'errors'  => [
                        'current_password' => [translate('The provided current password does not match our records.')],
                    ],
                    'message' => translate("The given data was invalid."),
                ], 422);
            }

            // Update password if current_password matches
            $user->password = Hash::make($request->new_password);
        }

        // Update name and email if provided
        if ($request->filled('first_name')) {
            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
        }

        if ($request->image) {
            
            $existingInUploads = Upload::where('id', $user->image)->first();
            if ($existingInUploads) {
                Storage::delete($existingInUploads->file_name);
                $existingInUploads->delete();
            }

            $data = substr($request->image, strpos($request->image, ',') + 1);
            $data = base64_decode($data);

            $image_name_with_path = 'UsersImages/' . Str::random(40) . '.png';
             
            Storage::disk('public')->put($image_name_with_path, $data);


            $Upload                     = new Upload;
            $Upload->file_original_name = $image_name_with_path;

            $Upload->extension = 'png';
            $Upload->type      = 'image/png';
            $Upload->file_name = $image_name_with_path;

            $Upload->save();

            $user->image = $Upload->id;
        }

        // Save updated user information
        $user->save();

        return 'success';
    }
    
}

