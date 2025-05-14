<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    { 
        return Inertia::render('Backend/Dashboard/Index');
    }
    public function create()
    {
        return Inertia::render('Backend/Dashboard/Create', [
            'title' => "Create new Events",
        ]);
    }
}
