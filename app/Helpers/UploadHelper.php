<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadHelper
{
    /**
     * Store uploaded file in both public and root directories for 100% compatibility across
     * all environments (cPanel shared hosting, Apache DocumentRoot, XAMPP, and artisan serve).
     */
    public static function uploadImage(UploadedFile $file, string $folder = 'uploads/sections'): string
    {
        $folder = trim(str_replace('\\', '/', $folder), '/');

        $extension = strtolower($file->getClientOriginalExtension());
        if (empty($extension) || $extension === 'tmp') {
            $extension = $file->guessExtension() ?: 'jpg';
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        if (empty($baseName)) {
            $baseName = 'image';
        }

        $filename = time() . '_' . rand(100, 999) . '_' . $baseName . '.' . $extension;

        // Path 1: Public directory (where web server directly serves static assets from)
        $publicPath = public_path($folder);
        if (!file_exists($publicPath)) {
            @mkdir($publicPath, 0777, true);
        }

        // Path 2: Root directory (for local XAMPP / direct access / backup)
        $rootPath = base_path($folder);
        if (!file_exists($rootPath)) {
            @mkdir($rootPath, 0777, true);
        }

        $fileInPublic = $publicPath . DIRECTORY_SEPARATOR . $filename;
        $fileInRoot = $rootPath . DIRECTORY_SEPARATOR . $filename;

        // Move to public path first
        $moved = false;
        try {
            $file->move($publicPath, $filename);
            @chmod($fileInPublic, 0644);
            $moved = true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("UploadHelper: Move to public failed, trying root: " . $e->getMessage());
            try {
                $file->move($rootPath, $filename);
                @chmod($fileInRoot, 0644);
                $moved = true;
            } catch (\Throwable $e2) {
                \Illuminate\Support\Facades\Log::error("UploadHelper: Move to root also failed: " . $e2->getMessage());
                throw $e2;
            }
        }

        // Ensure file is synced in both locations
        if (file_exists($fileInPublic) && !file_exists($fileInRoot)) {
            @copy($fileInPublic, $fileInRoot);
            @chmod($fileInRoot, 0644);
        } elseif (file_exists($fileInRoot) && !file_exists($fileInPublic)) {
            @copy($fileInRoot, $fileInPublic);
            @chmod($fileInPublic, 0644);
        }

        return $folder . '/' . $filename;
    }

    public static function uploadFile(UploadedFile $file, string $folder = 'uploads/videos'): string
    {
        $folder = trim(str_replace('\\', '/', $folder), '/');

        $extension = strtolower($file->getClientOriginalExtension());
        if (empty($extension) || $extension === 'tmp') {
            $extension = $file->guessExtension() ?: 'mp4';
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        if (empty($baseName)) {
            $baseName = 'file';
        }

        $filename = time() . '_' . rand(100, 999) . '_' . $baseName . '.' . $extension;

        $publicPath = public_path($folder);
        if (!file_exists($publicPath)) {
            @mkdir($publicPath, 0777, true);
        }

        $rootPath = base_path($folder);
        if (!file_exists($rootPath)) {
            @mkdir($rootPath, 0777, true);
        }

        $fileInPublic = $publicPath . DIRECTORY_SEPARATOR . $filename;
        $fileInRoot = $rootPath . DIRECTORY_SEPARATOR . $filename;

        try {
            $file->move($publicPath, $filename);
            @chmod($fileInPublic, 0644);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("UploadHelper: Move file to public failed, trying root: " . $e->getMessage());
            try {
                $file->move($rootPath, $filename);
                @chmod($fileInRoot, 0644);
            } catch (\Throwable $e2) {
                \Illuminate\Support\Facades\Log::error("UploadHelper: Move file to root also failed: " . $e2->getMessage());
                throw $e2;
            }
        }

        if (file_exists($fileInPublic) && !file_exists($fileInRoot)) {
            @copy($fileInPublic, $fileInRoot);
            @chmod($fileInRoot, 0644);
        } elseif (file_exists($fileInRoot) && !file_exists($fileInPublic)) {
            @copy($fileInRoot, $fileInPublic);
            @chmod($fileInPublic, 0644);
        }

        return $folder . '/' . $filename;
    }
}
