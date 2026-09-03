<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadHelper
{
    /**
     * Store uploaded file in both root and public directories for 100% XAMPP compatibility
     */
    public static function uploadImage(UploadedFile $file, string $folder = 'uploads/sections'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (empty($extension) || $extension === 'tmp') {
            $extension = $file->guessExtension() ?: 'jpg';
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        if (empty($baseName)) {
            $baseName = 'image';
        }

        $filename = time() . '_' . rand(100, 999) . '_' . $baseName . '.' . $extension;

        // Path 1: Root directory (for XAMPP direct access http://localhost/innotech/uploads/...)
        $rootPath = base_path($folder);
        if (!file_exists($rootPath)) {
            @mkdir($rootPath, 0755, true);
        }

        // Path 2: Public directory (for artisan serve or standard Laravel public_path)
        $publicPath = public_path($folder);
        // Save to root
        $file->move($rootPath, $filename);

        // Copy to public as well
        @copy($rootPath . '/' . $filename, $publicPath . '/' . $filename);

        return $folder . '/' . $filename;
    }

    public static function uploadFile(UploadedFile $file, string $folder = 'uploads/videos'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (empty($extension) || $extension === 'tmp') {
            $extension = $file->guessExtension() ?: 'mp4';
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        if (empty($baseName)) {
            $baseName = 'video';
        }

        $filename = time() . '_' . rand(100, 999) . '_' . $baseName . '.' . $extension;

        $rootPath = base_path($folder);
        if (!file_exists($rootPath)) {
            @mkdir($rootPath, 0755, true);
        }

        $publicPath = public_path($folder);
        if (!file_exists($publicPath)) {
            @mkdir($publicPath, 0755, true);
        }

        $file->move($rootPath, $filename);
        @copy($rootPath . '/' . $filename, $publicPath . '/' . $filename);

        return $folder . '/' . $filename;
    }
}
