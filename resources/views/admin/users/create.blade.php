@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('header_title', 'Tambah Pengguna')

@section('content')
<div class="space-y-8 animate-fade-in max-w-5xl mx-auto p-4 sm:p-0">
    <div class="px-2">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-[10px] sm:text-xs font-black text-[var(--text-muted)] hover:text-emerald-500 transition-colors uppercase tracking-widest mb-4">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <h1 class="text-2xl sm:text-3xl font-poppins font-bold text-[var(--text-main)]">Tambah Pengguna Baru</h1>
        <p class="text-[var(--text-muted)] text-xs sm:text-sm font-medium mt-1">Buat akun admin website baru atau data pelanggan aplikasi Android.</p>
    </div>

    @if($errors->any())
    <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4">
        <ul class="text-sm text-rose-500 list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-[var(--bg-card)] p-5 sm:p-10 rounded-3xl sm:rounded-[2.5rem] shadow-[var(--card-shadow)] border border-[var(--border)] relative overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-8">
            @csrf

            {{-- Baris 1: Nama & Email --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50"
                        placeholder="Contoh: Aditya Pratama">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50"
                        placeholder="Contoh: aditya@gmail.com">
                </div>
            </div>

            {{-- Baris 2: Telepon & Hak Akses (Role) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Nomor Telepon / WA</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50"
                        placeholder="Contoh: 081234567890">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Hak Akses / Peran</label>
                    <div class="flex items-center space-x-4 p-2 bg-[var(--bg-main)] border border-[var(--border)] rounded-2xl">
                        <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-emerald-500 text-[var(--text-muted)]">
                            <input type="radio" name="role" value="user" class="hidden" {{ old('role', 'user') == 'user' ? 'checked' : '' }}>
                            <span class="text-xs sm:text-sm font-bold">📱 Pelanggan App</span>
                        </label>
                        <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-emerald-500 text-[var(--text-muted)]">
                            <input type="radio" name="role" value="admin" class="hidden" {{ old('role') == 'admin' ? 'checked' : '' }}>
                            <span class="text-xs sm:text-sm font-bold">👑 Administrator</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Baris 3: Password & Konfirmasi Password --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Kata Sandi (Password)</label>
                    <input type="password" name="password" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50"
                        placeholder="Minimal 8 karakter...">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50"
                        placeholder="Ulangi kata sandi...">
                </div>
            </div>

            {{-- Baris 4: Alamat & Foto Profil --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Alamat Lengkap</label>
                    <textarea name="address" rows="5"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium leading-relaxed text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50"
                        placeholder="Tulis alamat tempat tinggal lengkap...">{{ old('address') }}</textarea>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Foto Profil</label>
                    <div class="relative group h-[calc(100%-28px)] min-h-[160px]">
                        <input type="file" name="profile_photo" id="photo-input" class="hidden" accept="image/*">
                        <div onclick="document.getElementById('photo-input').click()"
                            class="w-full h-full border-2 border-dashed border-[var(--border)] rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-[var(--bg-main)] transition-all group-hover:border-emerald-500/50">
                            <div id="photo-preview" class="flex flex-col items-center justify-center p-4">
                                <svg class="w-10 h-10 text-[var(--text-muted)] mb-2 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-[var(--text-muted)] group-hover:text-emerald-600 transition-colors">Pilih Foto Profil</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('photo-input').addEventListener('change', function(e) {
                    const preview = document.getElementById('photo-preview');
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" class="w-28 h-28 object-cover rounded-2xl shadow-lg border border-[var(--border)]">`;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            </script>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-green-500 text-white font-extrabold rounded-2xl shadow-xl shadow-green-500/20 hover:bg-green-600 hover:scale-[1.01] active:scale-[0.99] transition-all text-sm sm:text-lg flex items-center justify-center space-x-3">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>Simpan Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
