<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\UserIdentityVerification;

class IdentityVerificationController extends Controller
{
    public function index()
    {
        $verification = UserIdentityVerification::where('user_id', Auth::id())->latest()->first();

        if ($verification && in_array($verification->status, ['pending', 'approved'])) {
            $diskName = app()->environment('local', 'testing') ? 'public' : 'r2_private';
            $disk = \Illuminate\Support\Facades\Storage::disk($diskName);
            
            $getUrl = function($path) use ($disk, $diskName) {
                if (!$path) return null;
                // Exclude raw paths not yet processed by Background Job
                if (!\Illuminate\Support\Str::startsWith($path, 'users/')) return null; 
                return $diskName === 'public' 
                    ? $disk->url($path) 
                    : $disk->temporaryUrl($path, now()->addMinutes(30));
            };

            $verification->front_url = $getUrl($verification->document_front_path);
            $verification->back_url = $getUrl($verification->document_back_path);
            $verification->selfie_url = $getUrl($verification->selfie_path);
        }

        return Inertia::render('IdentityVerification/Process', [
            'verification' => $verification
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:ci_dni,pasaporte',
            'document_front' => 'required|file|image|max:10240',
            'selfie' => 'required|file|image|max:10240',
        ]);

        if ($request->document_type === 'ci_dni') {
            $request->validate(['document_back' => 'required|file|image|max:10240']);
        }

        $userId = Auth::id();

        $existing = UserIdentityVerification::where('user_id', $userId)
                        ->whereIn('status', ['pending', 'approved'])
                        ->first();
                        
        if ($existing) {
            return back()->with('error', __('Ya tienes una solicitud en proceso.'));
        }

        $diskName = app()->environment('local', 'testing') ? 'local' : 'r2_private';
        $frontPath = $request->file('document_front')->store('tmp/identity', $diskName);
        $backPath = $request->hasFile('document_back') ? $request->file('document_back')->store('tmp/identity', $diskName) : null;
        $selfiePath = $request->file('selfie')->store('tmp/identity', $diskName);

        $verification = UserIdentityVerification::create([
            'user_id' => $userId,
            'document_type' => $request->document_type,
            'document_front_path' => $frontPath,
            'document_back_path' => $backPath,
            'selfie_path' => $selfiePath,
            'status' => 'pending',
        ]);

        \App\Jobs\ProcessIdentityImages::dispatch($verification);

        return back()->with('success', __('Solicitud enviada correctamente. En breve será procesada.'));
    }
}
