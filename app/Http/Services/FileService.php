<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Upload a file to the configured disk.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string URL to access the file
     */
    public function upload($file, $path)
    {
        // Generate a unique filename to avoid collisions
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

        // Store file on the configured disk (public/private depending on .env)
        $storedPath = $file->storeAs($path, $filename, config('filesystems.default'));

        $disk = Storage::disk(config('filesystems.default'));

        // If disk is private → return a temporary signed URL
        if (config('filesystems.default') === 'private') {
            return $disk->temporaryUrl($storedPath, now()->addMinutes(30));
        }

        // If disk is public → return permanent URL
        return $disk->url($storedPath);
    }

    /**
     * Delete a file from the configured disk.
     *
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        return Storage::disk(config('filesystems.default'))->delete($path);
    }
}
