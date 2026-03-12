<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign default 'visitor' role using Spatie Permission
        // We will create this role in a seeder if it doesn't exist, but it's safe to use assignRole if it exists.
        // If the role doesn't exist yet, it's better to wrap in try-catch or ensure it's seeded.
        try {
            $user->assignRole('visitor');
        } catch (\Exception $e) {
            // Log or ignore if the role is not yet seeded during initial dev
        }

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
