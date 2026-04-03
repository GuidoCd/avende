<?php

namespace App\Jobs;

use App\Models\Property\Property;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessPropertyImage implements ShouldQueue
{
    use Queueable;

    public $property;
    public $imagePath;
    public $isFirst;

    /**
     * Create a new job instance.
     */
    public function __construct(Property $property, string $imagePath, bool $isFirst)
    {
        $this->property = $property;
        $this->imagePath = $imagePath;
        $this->isFirst = $isFirst;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $diskName = app()->environment('local', 'testing') ? 'local' : 'r2_private';
        $disk = \Illuminate\Support\Facades\Storage::disk($diskName);

        if (!$disk->exists($this->imagePath)) {
            throw new \Exception("Temporary property image missing on disk [{$diskName}]: {$this->imagePath}");
        }

        $manager = new ImageManager(new Driver());
        // read() can handle binary file contents directly
        $image = $manager->read($disk->get($this->imagePath));

        $encoded = $image->scaleDown(width: 1920)->toWebp(quality: 80);

        $filename = Str::slug($this->property->title) . '-' . uniqid() . '.webp';

        $this->property->addMediaFromString($encoded->toString())
            ->usingFileName($filename)
            ->withCustomProperties(['main' => $this->isFirst])
            ->toMediaCollection('images');

        // Clean up temporary image
        $disk->delete($this->imagePath);
    }
}
