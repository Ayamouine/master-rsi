<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::paginate(20);
        return response()->json($images);
    }

    public function show($id)
    {
        $image = Image::findOrFail($id);
        return response()->json($image);
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'type'        => 'required|in:etudiant,projet,document',
        ]);

        $path = $request->file('image')->store('images', 'public');

        $image = Image::create([
            'path'        => $path,
            'description' => $validated['description'] ?? null,
            'type'        => $validated['type'],
            'size'        => $request->file('image')->getSize(),
        ]);

        return response()->json($image, 201);
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return response()->json(['message' => 'Image supprimée']);
    }
}

