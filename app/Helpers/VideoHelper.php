<?php

namespace App\Helpers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class VideoHelper extends Helper
{

    /**
     * define constant variable.
     */
    const DIR = '';

    /**
     * Get Disk driver.
     */
    public static function disk(): Filesystem
    {
        return Storage::disk('video');
    }

    /**
     * Generate name and upload.
     */
    public static function upload($data): String
    {
        $name = parent::uniqueName('.' . $data->extension());
        self::disk()->put(self::DIR . $name, file_get_contents($data));
        return $name;
    }

    /**
     * Delete video.
     */
    public static function destroy($name): void
    {
        if ($name && self::disk()->exists(self::DIR . $name)) {
            self::disk()->delete(self::DIR . $name);
        }
    }

    /**
     * Get video url.
     */
    public static function getUrl($name): String
    {
        if ($name) {
            return self::disk()->url(self::DIR . $name);
        }
        return '';
    }
}
