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

            // Use the same logic as the controller to figure out where TEMP files are
            $tempDiskName = app()->environment('local', 'testing') ? 'local' : 'r2_private';
            $tempDisk = Storage::disk($tempDiskName);

            if (!$tempDisk->exists($localPath)) {
                throw new \Exception("Identity image missing on temp disk [{$tempDiskName}]: {$localPath}");
            }

            // read() accepts binary data directly
            $image = $manager->read($tempDisk->get($localPath));
            $encoded = $image->scaleDown(width: 1600)->toWebp(quality: 80);

            $filename = 'users/' . $this->verification->user_id . '/identity/' . $this->verification->id . '/' . uniqid() . '.webp';
            
            $destDiskName = app()->environment('local', 'testing') ? 'public' : 'r2_private';
            
            // Upload to destination disk
            $uploaded = Storage::disk($destDiskName)->put($filename, $encoded->toString());

            if (! $uploaded) {
                \Illuminate\Support\Facades\Log::error("Failed to upload identity image to {$destDiskName}", [
                    'verification_id' => $this->verification->id,
                    'file' => $filename
                ]);
                throw new \Exception("Upload to disk {$destDiskName} failed for file {$filename}");
            }

            // Delete from temp disk
            $tempDisk->delete($localPath);

            // Update verification DB record with the final s3 path
            $this->verification->update([$field => $filename]);
        }
    }
}
