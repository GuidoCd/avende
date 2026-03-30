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
        if (!file_exists($this->imagePath)) {
            return;
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($this->imagePath);

        $encoded = $image->scaleDown(width: 1920)->toWebp(quality: 80);

        $filename = Str::slug($this->property->title) . '-' . uniqid() . '.webp';

        $this->property->addMediaFromString($encoded->toString())
            ->usingFileName($filename)
            ->withCustomProperties(['main' => $this->isFirst])
            ->toMediaCollection('images');

        // Clean up temporary image
        @unlink($this->imagePath);
    }
}
