<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderByDesc('id')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif,avif|max:5120',
            'emoji'       => 'nullable|string|max:20',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'sold'        => 'nullable|integer|min:0',
        ]);

        $data = $this->buildPayload($request, [
            'rating' => 5.0,
            'sold' => 0,
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif,avif|max:5120',
            'emoji'       => 'nullable|string|max:20',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'sold'        => 'nullable|integer|min:0',
        ]);

        $data = $this->buildPayload($request, [
            'rating' => $menu->rating,
            'sold' => $menu->sold,
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $this->deleteStoredImage($menu->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $this->deleteStoredImage($menu->image_url);
        Menu::destroy($menu->getKey());

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function buildPayload(Request $request, array $defaults): array
    {
        return [
            'name'         => $request->input('name'),
            'category'     => $request->input('category'),
            'price'        => (int) $request->input('price'),
            'description'  => $request->input('description'),
            'is_featured'   => $request->has('is_featured') ? (int) $request->boolean('is_featured') : 0,
            'is_available'  => $request->has('is_available') ? (int) $request->boolean('is_available') : 0,
            'emoji'         => $request->input('emoji'),
            'rating'        => $request->input('rating', $defaults['rating']),
            'sold'          => $request->input('sold', $defaults['sold']),
        ];
    }

    private function storeImage(UploadedFile $file): string
    {
        $ext = $file->extension() ?: $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $destination = public_path('uploads/menus');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $filename);

        return 'uploads/menus/' . $filename;
    }

    private function deleteStoredImage(?string $imagePath): void
    {
        if (!$imagePath || filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return;
        }

        $localPath = public_path($imagePath);
        if (File::exists($localPath)) {
            File::delete($localPath);
        }
    }
}
