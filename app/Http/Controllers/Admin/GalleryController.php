<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('order')->get();
        return view('admin.gallery.index', compact('galleries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,avif|max:5120',
            'title' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $ext = $request->image->extension() ?: $request->image->getClientOriginalExtension();
            $imageName = time() . '_' . uniqid() . '.' . $ext;
            $request->image->move(public_path('uploads/gallery'), $imageName);
            $path = 'uploads/gallery/' . $imageName;

            Gallery::create([
                'image_path' => $path,
                'title' => $request->title,
                'order' => Gallery::count() + 1
            ]);

            return redirect()->back()->with('success', 'Foto galeri berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,avif|max:5120',
            'title' => 'nullable|string|max:255',
        ]);

        $data = ['title' => $request->title];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path && file_exists(public_path($gallery->image_path))) {
                @unlink(public_path($gallery->image_path));
            }

            $ext = $request->image->extension() ?: $request->image->getClientOriginalExtension();
            $imageName = time() . '_' . uniqid() . '.' . $ext;
            $request->image->move(public_path('uploads/gallery'), $imageName);
            $data['image_path'] = 'uploads/gallery/' . $imageName;
        }

        $gallery->update($data);

        return redirect()->back()->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image_path && file_exists(public_path($gallery->image_path))) {
            @unlink(public_path($gallery->image_path));
        }
        $gallery->delete();

        return redirect()->back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}
