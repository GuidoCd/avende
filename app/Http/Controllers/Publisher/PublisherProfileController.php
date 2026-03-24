<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PublisherProfileUpdateRequest;

class PublisherProfileController extends Controller
{
    public function show(Request $request)
    {
        $publicProfile = $request->user()->publicProfile;

        return Inertia::render('Publisher/Profile', [
            'publicProfile' => $publicProfile,
        ]);
    }

    public function update(PublisherProfileUpdateRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();
        $publicProfile = $user->publicProfile()->firstOrNew();

        $histories = [];

        // Si ya existía el perfil público, verificamos para el historial de auditoría
        if ($publicProfile->exists) {
            if ($publicProfile->public_email !== $validated['public_email']) {
                $histories[] = [
                    'context' => 'public',
                    'type' => 'email',
                    'old_value' => $publicProfile->public_email,
                    'new_value' => $validated['public_email'],
                ];
            }
            if ($publicProfile->public_phone !== $validated['public_phone']) {
                $histories[] = [
                    'context' => 'public',
                    'type' => 'phone',
                    'old_value' => $publicProfile->public_phone,
                    'new_value' => $validated['public_phone'],
                ];
            }
        }

        $publicProfile->fill($validated);

        if ($publicProfile->isDirty() || count($histories) > 0) {
            if (count($histories) > 0) {
                $user->contactHistory()->createMany($histories);
            }
            $user->publicProfile()->save($publicProfile);
        }

        return Redirect::back();
    }
}
