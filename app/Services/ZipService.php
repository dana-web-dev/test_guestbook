<?php

namespace App\Services;

use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ZipService
{
    /**
     * Compress a file to .zip
     *
     * @param string $sourceFile Absolute path to the file
     * @param bool $deleteOriginal Whether to delete original file after zip
     * @return string|null Path to .zip file or null on failure
     */
    public function zipFile(string $sourceFile, bool $deleteOriginal = false): ?string
    {
        $zipPath = "{$sourceFile}.zip";
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($sourceFile, basename($sourceFile));
            $zip->close();

            if ($deleteOriginal) {
                File::delete($sourceFile);
            }

            Log::info("File zipped: {$zipPath}");
            return $zipPath;
        }

        Log::warning("Failed to create zip for: {$sourceFile}");
        return null;
    }
}
