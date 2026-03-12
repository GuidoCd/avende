<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    /**
     * Update the user's session locale.
     */
    public function update(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|in:es,en,pt',
        ]);

        $locale = $request->input('locale');
        
        session()->put('locale', $locale);
        App::setLocale($locale);

        return back();
    }
}
