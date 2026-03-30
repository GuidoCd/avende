<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\UserIdentityVerification;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessIdentityImages implements ShouldQueue
{
    use Queueable;

    public $verification;

    public function __construct(UserIdentityVerification $verification)
    {
        $this->verification = $verification;
    }

    public function handle(): void
    {
        $manager = new ImageManager(new Driver());

        $fields = [
            'document_front_path',
            'document_back_path',
            'selfie_path',
        ];

        foreach ($fields as $field) {
            $localPath = $this->verification->{$field};
            
            if (!$localPath) continue;

            $fullLocalPath = Storage::disk('local')->path($localPath);

            if (!file_exists($fullLocalPath)) continue;

            $image = $manager->read($fullLocalPath);
            $encoded = $image->scaleDown(width: 1600)->toWebp(quality: 80);

            $filename = 'users/' . $this->verification->user_id . '/identity/' . $this->verification->id . '/' . uniqid() . '.webp';
            
            $diskName = app()->environment('local', 'testing') ? 'public' : 'r2_private';
            
            // Upload to appropriate disk
            Storage::disk($diskName)->put($filename, $encoded->toString());

            // Delete from local temp
            @unlink($fullLocalPath);

            // Update verification DB record with the private s3 path
            $this->verification->update([$field => $filename]);
        }
    }
}
