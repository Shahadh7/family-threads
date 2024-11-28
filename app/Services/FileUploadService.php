<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileUploadService
{
    public function uploadToSpaces(UploadedFile $file, $path = '', $filename = null)
    {
        try {

            $fileContent = File::get($file);

            $filename = $filename ?: Str::random(32) . '.' . $file->getClientOriginalExtension();

            $filePath = $path . '/' . $filename;
            Storage::disk('do')->put($filePath, $fileContent);

            $fileUrl = Storage::disk('do')->url($filePath);

            return $fileUrl;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to upload file.'], 500);
        }
    }

    public function downloadFromSpaces($path)
    {
        try {
            return Storage::disk('do')->get($path);
        } catch (\Exception $e) {
            \Log::error('Error in downloadFromSpaces: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to download file.'], 500);
        }
    }

    public function updateFileInSpaces($oldFilePath, UploadedFile $newFile, $path = '')
    {
        try {
            Storage::disk('do')->delete($oldFilePath);

            return $this->uploadToSpaces($newFile, $path);

        } catch (\Exception $e) {
            \Log::error('Error in updateFileInSpaces: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to update file.'], 500);
        }
    }

    public function deleteFileFromSpaces($path)
    {
        try {
            return Storage::disk('do')->delete($path);
        } catch (\Exception $e) {
            \Log::error('Error in delete File From Spaces: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to delete file.'], 500);
        }
    }
}
