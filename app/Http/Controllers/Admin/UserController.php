<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'role'          => ['required', 'string', Rule::in(['admin', 'user'])],
            'password'      => ['required', 'confirmed', Password::defaults()],
            'profile_photo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,avif', 'max:2048'],
        ]);

        $data = [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'address'  => $validated['address'],
            'role'     => $validated['role'],
            'is_admin' => $validated['role'] === 'admin' ? 1 : 0,
            'password' => Hash::make($validated['password']),
        ];

        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $data['profile_photo'] = $this->storeProfilePhoto($request->file('profile_photo'));
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'role'          => ['required', 'string', Rule::in(['admin', 'user'])],
            'password'      => ['nullable', 'confirmed', Password::defaults()],
            'profile_photo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,avif', 'max:2048'],
        ]);

        $data = [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'address'  => $validated['address'],
            'role'     => $validated['role'],
            'is_admin' => $validated['role'] === 'admin' ? 1 : 0,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $this->deleteStoredProfilePhoto($user->profile_photo);
            $data['profile_photo'] = $this->storeProfilePhoto($request->file('profile_photo'));
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Don't allow currently authenticated admin to delete themselves!
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->deleteStoredProfilePhoto($user->profile_photo);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    private function storeProfilePhoto(UploadedFile $file): string
    {
        $ext = $file->extension() ?: $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $destination = public_path('uploads/profile');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $filename);

        return $filename;
    }

    private function deleteStoredProfilePhoto(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        $localPath = public_path('uploads/profile/' . $filename);
        if (File::exists($localPath)) {
            File::delete($localPath);
        }

    }
}
