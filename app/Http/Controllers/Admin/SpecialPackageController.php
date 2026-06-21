<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialPackage;
use Illuminate\Http\Request;

class SpecialPackageController extends Controller
{
    public function index()
    {
        $packages = SpecialPackage::orderBy('order')->get();
        return view('admin.special_packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.special_packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'badge'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif,avif,webp|max:5120',
            'features'    => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        // Build data manually — never use $request->all() with file fields
        $featuresText = $request->input('features');
        $data = [
            'title'       => $request->input('title'),
            'badge'       => $request->input('badge'),
            'description' => $request->input('description'),
            'order'       => $request->input('order', 0),
            'features'    => $featuresText
                ? array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $featuresText)))))
                : [],
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file     = $request->file('image');
            $ext      = $file->extension() ?: $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $file->move(public_path('uploads/packages'), $filename);
            $data['image'] = 'uploads/packages/' . $filename;
        }

        SpecialPackage::create($data);
        return redirect()->route('admin.special_packages.index')->with('success', 'Paket spesial berhasil ditambahkan.');
    }

    public function edit(SpecialPackage $specialPackage)
    {
        return view('admin.special_packages.edit', compact('specialPackage'));
    }

    public function update(Request $request, SpecialPackage $specialPackage)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'badge'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif,avif,webp|max:5120',
            'features'    => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        // Build data manually — never use $request->all() with file fields
        $featuresText = $request->input('features');
        $data = [
            'title'       => $request->input('title'),
            'badge'       => $request->input('badge'),
            'description' => $request->input('description'),
            'order'       => $request->input('order', $specialPackage->order ?? 0),
            'features'    => $featuresText
                ? array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $featuresText)))))
                : [],
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image from website path
            if ($specialPackage->image && !filter_var($specialPackage->image, FILTER_VALIDATE_URL)
                && file_exists(public_path($specialPackage->image))) {
                @unlink(public_path($specialPackage->image));
            }

            $file     = $request->file('image');
            $ext      = $file->extension() ?: $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $file->move(public_path('uploads/packages'), $filename);
            $data['image'] = 'uploads/packages/' . $filename;
        }

        $specialPackage->update($data);
        return redirect()->route('admin.special_packages.index')->with('success', 'Paket spesial berhasil diperbarui.');
    }

    public function destroy(SpecialPackage $specialPackage)
    {
        if ($specialPackage->image && !filter_var($specialPackage->image, FILTER_VALIDATE_URL)
            && file_exists(public_path($specialPackage->image))) {
            @unlink(public_path($specialPackage->image));
        }
        $specialPackage->delete();
        return redirect()->route('admin.special_packages.index')->with('success', 'Paket spesial berhasil dihapus.');
    }
}
