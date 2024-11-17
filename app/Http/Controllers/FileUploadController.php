<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * Handle file upload and save to DigitalOcean Spaces.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        try {

            $file = $request->file('file');


            $encryptedContent = encrypt(file_get_contents($file));


            $path = 'uploads/' . uniqid() . '-' . $file->getClientOriginalName();


            Storage::disk('spaces')->put($path, $encryptedContent, 'private');

            return response()->json([
                'message' => 'File uploaded successfully!',
                'path' => $path,
                'url' => Storage::disk('spaces')->url($path),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete file from DigitalOcean Spaces.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {

            $path = $request->input('path');

            if (!Storage::disk('spaces')->exists($path)) {
                return response()->json([
                    'message' => 'File not found.',
                ], 404);
            }

            Storage::disk('spaces')->delete($path);

            return response()->json([
                'message' => 'File deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
