<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class S3ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('image');

        // Define a file path
        $filePath = 'images/' . time() . '_' . $file->getClientOriginalName();

        // Upload the file to S3
        $path = Storage::disk('s3')->putFileAs('images', $file, $filePath, 'public');

        // Get the file URL
        $url = Storage::disk('s3')->url($filePath);

        return response()->json(['url' => $url]);
    }
}
