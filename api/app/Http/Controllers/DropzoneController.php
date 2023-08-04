<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DropzoneController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['attachments.*' => 'required|image|max:5000']);

        $filenames = collect($request->attachments)->map(function ($attachment) {
            $path = $attachment->store('temp', 'public');
            return basename($path);
        });

        return response()->json(['data' => $filenames], 201);
    }
}
