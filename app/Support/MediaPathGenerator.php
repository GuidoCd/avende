<?php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . '/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . '/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . '/responsive-images/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if ($prefix !== '') {
            $prefix .= '/';
        }

        $modelClass = get_class($media->model);
        $modelId = $media->model_id;

        if ($modelClass === \App\Models\User::class) {
            return $prefix . "users/{$modelId}/profile";
        }

        if ($modelClass === \App\Models\Property\Property::class) {
            return $prefix . "properties/{$modelId}";
        }

        // Default behavior for other models
        $modelName = strtolower(class_basename($media->model_type));
        return $prefix . "{$modelName}s/{$modelId}";
    }
}
