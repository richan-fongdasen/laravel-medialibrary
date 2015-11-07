<?php

namespace Spatie\MediaLibrary\PathGenerator;

use Spatie\MediaLibrary\Conversion\Conversion;
use Spatie\MediaLibrary\Media;

interface PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     *
     * @param Media $media
     * @return string
     */
    public function getPath(Media $media);

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     *
     * @param Media $media
     * @return string
     */
    public function getPathForConversions(Media $media);
}
