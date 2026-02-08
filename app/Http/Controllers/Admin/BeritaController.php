<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::with('author')->latest()->paginate(10);
        return view('pages.admin.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('pages.admin.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB per file
            'status' => 'required|in:draft,published'
        ]);

        $data = $request->except('gambar');
        $data['slug'] = Str::slug($request->judul) . '-' . Str::random(5);
        $data['excerpt'] = Str::limit(strip_tags($request->konten), 150);
        $data['user_id'] = Auth::id();

        $imagePaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $imagePaths[] = $file->store('berita', 'public');
            }
        }
        $data['gambar'] = $imagePaths; // Store array, Model casts to JSON

        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diterbitkan.');
    }

    public function edit(Berita $beritum)
    {
        return view('pages.admin.berita.edit', compact('beritum'));
    }

    public function update(Request $request, Berita $beritum)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:draft,published'
        ]);

        $data = $request->except('gambar');
        $data['slug'] = Str::slug($request->judul) . '-' . Str::random(5);
        $data['excerpt'] = Str::limit(strip_tags($request->konten), 150);

        if ($request->hasFile('gambar')) {
            // Option: Add to existing or Replace?
            // For simplicity and typical CMS behavior, let's append new images to existing ones
            // OR if user wants to replace, they can delete old ones first. 
            // Here we will MERGE new images with old ones.
            
            $existingImages = $beritum->gambar ?? [];
            if (is_string($existingImages)) $existingImages = [$existingImages]; // Handle legacy

            $newImages = [];
            foreach ($request->file('gambar') as $file) {
                $newImages[] = $file->store('berita', 'public');
            }
            
            $data['gambar'] = array_merge($existingImages, $newImages);
        } else {
             // Keep existing images if no new ones uploaded
             // Unless we handle deletion separately (which we should via a separate endpoint/hidden input, but for now preserve)
             $data['gambar'] = $beritum->gambar;
        }

        // Logic to remove images if requested (via checkbox in edit form, handled later)
        if ($request->has('delete_images')) {
            $imagesToDelete = $request->input('delete_images');
            $currentImages = $data['gambar'] ?? [];
            
            foreach ($imagesToDelete as $deletePath) {
                if (($key = array_search($deletePath, $currentImages)) !== false) {
                   unset($currentImages[$key]);
                   Storage::disk('public')->delete($deletePath);
                }
            }
            $data['gambar'] = array_values($currentImages); // Reindex array
        }

        $beritum->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $beritum)
    {
        if ($beritum->gambar) {
            Storage::disk('public')->delete($beritum->gambar);
        }
        $beritum->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita dihapus.');
    }
}
