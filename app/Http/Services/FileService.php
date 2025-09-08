<?php

namespace App\Http\Services;

class FileService
{
    public function upload($file, $path)
    {
        return $file->store($path, 'public');
    }

    public function delete($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
