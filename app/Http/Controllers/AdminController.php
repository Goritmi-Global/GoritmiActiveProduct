<?php

namespace App\Http\Controllers;

 
use App\Models\User;
 
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    

    public function user_profile()
    {
        $user_id = User::find(auth()->user()->id);
        return Inertia::render('Backend/Profile/Update', [
            'userId' => $user_id,
        ]);

    }
    
}
