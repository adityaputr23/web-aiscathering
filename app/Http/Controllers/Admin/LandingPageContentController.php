<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageContent;
use Illuminate\Http\Request;

class LandingPageContentController extends Controller
{
    public function index()
    {
        $contents = LandingPageContent::all();
        return view('admin.content.index', compact('contents'));
    }

    public function update(Request $request, LandingPageContent $content)
    {
        // 1. If it's a file type (image/video)
        if ($content->type === 'image' || $content->type === 'video') {
            
            // If a file is uploaded
            if ($request->hasFile('value')) {
                $rules = $content->type === 'image' 
                    ? 'file|mimes:jpeg,png,jpg,gif,svg,avif|max:5120' 
                    : 'mimes:mp4,mov,ogg,qt|max:30720'; // Increased to 30MB

                $request->validate([
                    'value' => $rules,
                ]);

                // Delete old file if exists locally
                if ($content->value && !filter_var($content->value, FILTER_VALIDATE_URL)) {
                    $oldPath = public_path($content->value);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $ext = $request->value->extension() ?: $request->value->getClientOriginalExtension();
                $filename = time() . '_' . $content->key . '.' . $ext;
                $request->value->move(public_path('uploads/contents'), $filename);
                $path = 'uploads/contents/' . $filename;
                
                $content->update(['value' => $path]);
            } 
            // If no file but there's text input (URL)
            elseif ($request->filled('value_text')) {
                $content->update(['value' => $request->value_text]);
            }
        } 
        // 2. Regular text types
        else {
            $request->validate([
                'value' => 'required',
            ]);
            $content->update(['value' => $request->value]);
        }

        return redirect()->back()->with('success', 'Konten berhasil diperbarui.');
    }
}
