<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class StorageService
{
    public function put(string $path, $contents, ?string $disk = null)
    {
        return Storage::disk($disk ?: config('filesystems.default'))->put($path, $contents);
    }

    public function get(string $path, ?string $disk = null)
    {
        return Storage::disk($disk ?: config('filesystems.default'))->get($path);
    }

    public function delete(string $path, ?string $disk = null)
    {
        return Storage::disk($disk ?: config('filesystems.default'))->delete($path);
    }

    public function url(string $path, ?string $disk = null)
    {
        return Storage::disk($disk ?: config('filesystems.default'))->url($path);
    }
}
