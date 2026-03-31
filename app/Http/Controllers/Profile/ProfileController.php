<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        // Append avatar url so Vue can render it immediately without waiting for generic auth props
        $avatarUrl = null;
        if ($user->avatar) {
            $diskName = app()->environment('local', 'testing') ? 'public' : 'r2_public';
            $avatarUrl = \Illuminate\Support\Facades\Storage::disk($diskName)->url($user->avatar);
        } else {
            $avatarUrl = $user->getFirstMediaUrl('avatar');
        }

        return Inertia::render('Profile/Show', [
            'mustVerifyEmail' => $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail,
            'status' => session('status'),
            'avatar_url' => $avatarUrl ?: null,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Extraer los datos validados pero no el archivo
        $data = $request->safe()->except('avatar');
        $user->fill($data);

        $histories = [];

        // Audit Logging para Email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $histories[] = [
                'context' => 'private',
                'type' => 'email',
                'old_value' => $user->getOriginal('email'),
                'new_value' => $user->email,
            ];
        }

        // Audit Logging para Phone
        if ($user->isDirty('phone')) {
            $histories[] = [
                'context' => 'private',
                'type' => 'phone',
                'old_value' => $user->getOriginal('phone'),
                'new_value' => $user->phone,
            ];
        }

        $user->save();

        if (count($histories) > 0) {
            $user->contactHistory()->createMany($histories);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->read($file);
            $encoded = $image->scaleDown(width: 800)->toWebp(quality: 80);
            
            $filename = 'users/' . $user->id . '/profile/avatar_' . uniqid() . '.webp';
            $diskName = app()->environment('local', 'testing') ? 'public' : 'r2_public';
            
            \Illuminate\Support\Facades\Storage::disk($diskName)->put($filename, $encoded->toString());
            
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk($diskName)->delete($user->avatar);
            }
            
            $user->avatar = $filename;
            $user->save();
        }

        return Redirect::route('profile.show');
    }
}
