<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
            'folder' => 'nullable|string' // opsional, default ke 'foto-sopir'
        ]);


        $folder = $request->input('folder', 'foto-sopir');


        $folder = trim(preg_replace('/[^a-zA-Z0-9_\-]/', '', $folder));

        $path = $request->file('foto')->store("public/{$folder}");
        $url = Storage::url($path);

        return response()->json(['path' => $url]);
    }
}
