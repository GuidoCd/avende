<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SecurityController extends Controller
{
    /**
     * Display the user's security settings.
     */
    public function show(Request $request)
    {
        return Inertia::render('Profile/Security');
    }
}
