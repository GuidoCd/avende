<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PreferencesController extends Controller
{
    /**
     * Update the user's preferences (theme, locale)
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'nullable|string|in:es,en,pt',
            'theme' => 'nullable|string|in:light,dark,system',
        ]);

        if (isset($validated['locale'])) {
            session()->put('locale', $validated['locale']);
            App::setLocale($validated['locale']);
        }
        
        if (isset($validated['theme'])) {
            session()->put('theme', $validated['theme']);
        }

        if ($user = $request->user()) {
            
            \Illuminate\Support\Facades\Log::info('Preferences Update payload:', $request->all());

            $prefs = $user->preferences ?? [];
            $changed = false;
            
            if ($request->has('locale') && ($prefs['locale'] ?? null) !== $request->input('locale')) {
                $prefs['locale'] = $request->input('locale');
                $changed = true;
            }
            if ($request->has('theme') && ($prefs['theme'] ?? null) !== $request->input('theme')) {
                $prefs['theme'] = $request->input('theme');
                $changed = true;
            }
            
            if ($changed) {
                // Use forceFill to guarantee the JSON cast updates the raw attribute
                $user->forceFill(['preferences' => $prefs])->save();
            }
        }

        return back();
    }
}
