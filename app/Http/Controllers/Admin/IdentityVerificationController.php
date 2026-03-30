<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\UserIdentityVerification;
use Illuminate\Support\Facades\Storage;

class IdentityVerificationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $verifications = UserIdentityVerification::with(['user' => function($q) {
                $q->select('id', 'name', 'email', 'is_verified');
            }])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->through(function ($verif) {
                $diskName = app()->environment('local', 'testing') ? 'public' : 'r2_private';
                $disk = Storage::disk($diskName);
                
                $getUrl = function($path) use ($disk, $diskName) {
                    if (!$path) return null;
                    return $diskName === 'public' 
                        ? $disk->url($path) 
                        : $disk->temporaryUrl($path, now()->addMinutes(30));
                };

                $front = $getUrl($verif->document_front_path);
                $back = $getUrl($verif->document_back_path);
                $selfie = $getUrl($verif->selfie_path);

                return [
                    'id' => $verif->id,
                    'user' => $verif->user,
                    'document_type' => $verif->document_type,
                    'status' => $verif->status,
                    'rejected_reason' => $verif->rejected_reason,
                    'created_at' => $verif->created_at,
                    'images' => [
                        'front' => $front,
                        'back' => $back,
                        'selfie' => $selfie,
                    ]
                ];
            });

        return Inertia::render('Admin/IdentityVerifications/Index', [
            'verifications' => $verifications,
            'filters' => ['status' => $status]
        ]);
    }

    public function approve(UserIdentityVerification $verification)
    {
        $verification->update(['status' => 'approved', 'rejected_reason' => null]);
        $verification->user()->update(['is_verified' => true]);

        return back()->with('success', __('Identidad del usuario aprobada exitosamente.'));
    }

    public function reject(Request $request, UserIdentityVerification $verification)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $verification->update([
            'status' => 'rejected', 
            'rejected_reason' => $request->reason
        ]);
        
        $verification->user()->update(['is_verified' => false]);

        return back()->with('success', __('La solicitud ha sido rechazada.'));
    }
}
