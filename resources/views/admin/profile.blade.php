@extends('layouts.admin')

@section('title', 'Admin Profile')

@section('content')
<div class="max-w-5xl mx-auto space-y-4 sm:space-y-10 animate-in px-0 sm:px-0">

    <!-- HEADER PROFILE -->
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-lg sm:text-3xl font-black text-[var(--text-main)] tracking-tight">Pengaturan Akun</h2>
            <p class="text-[var(--text-muted)] text-[10px] sm:text-base font-medium hidden sm:block">Kelola identitas administratif dan keamanan sistem Anda.</p>
        </div>
        <div class="flex items-center gap-1.5 text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-emerald-500 bg-emerald-500/10 px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-full border border-emerald-500/20 whitespace-nowrap">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
            Verified
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/20 p-5 rounded-[2rem] flex items-center gap-4 text-emerald-500 animate-in">
        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center text-xl">✅</div>
        <p class="text-sm font-bold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10">
        
        <!-- SIDEBAR INFO -->
        <div class="space-y-6">
            <div class="bg-[var(--bg-card)] border border-[var(--border)] p-5 sm:p-8 rounded-[1.5rem] sm:rounded-[3rem] text-center relative overflow-hidden group shadow-[var(--card-shadow)]">
                <div class="relative z-10">
                    <div class="relative inline-block group/avatar">
                        <div class="w-20 h-20 sm:w-28 sm:h-28 bg-gradient-to-tr from-emerald-500 to-emerald-300 rounded-2xl sm:rounded-[2.5rem] mx-auto flex items-center justify-center text-white text-3xl sm:text-5xl font-black shadow-xl sm:shadow-2xl shadow-emerald-500/20 mb-4 sm:mb-6 group-hover:scale-110 transition duration-500 overflow-hidden">
                            @if(auth()->user()->profile_photo && file_exists(public_path('uploads/profile/' . auth()->user()->profile_photo)))
                                <img src="{{ asset('uploads/profile/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(auth()->user()->name, 0, 1) }}
                            @endif
                        </div>
                    </div>
                    <h3 class="text-lg sm:text-xl font-black text-[var(--text-main)] tracking-tight">{{ auth()->user()->name }}</h3>
                    <p class="text-[9px] sm:text-[10px] text-emerald-500 font-bold uppercase tracking-[0.2em] mt-1 sm:mt-2 mb-6 sm:mb-8">Administrator</p>
                    
                    <div class="space-y-4 pt-6 border-t border-[var(--border)]">
                        <div class="flex items-center gap-4 text-left">
                            <div class="w-10 h-10 rounded-2xl bg-[var(--bg-main)] flex items-center justify-center text-[var(--text-muted)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-[var(--text-muted)] font-black uppercase tracking-tighter">Primary Email</p>
                                <p class="text-sm font-bold text-[var(--text-main)]">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DECOR -->
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-500/10 blur-[50px] rounded-full"></div>
            </div>

            <div class="bg-amber-500/5 border border-amber-500/10 p-5 sm:p-6 rounded-2xl sm:rounded-[2rem]">
                <div class="flex gap-4">
                    <span class="text-2xl">🛡️</span>
                    <p class="text-[11px] text-amber-500/80 font-medium leading-relaxed">
                        <b>Privasi Terjamin:</b> Data login Anda dienkripsi secara end-to-end melintasi server Aish Management.
                    </p>
                </div>
            </div>
        </div>

        <!-- MAIN FORM -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-8">
                @csrf
                @method('PUT')

                <!-- BASIC SECTION -->
                <div class="bg-[var(--bg-card)] border border-[var(--border)] p-4 sm:p-8 lg:p-10 rounded-xl sm:rounded-[3rem] relative overflow-hidden shadow-[var(--card-shadow)]">
                    <div class="flex items-center gap-2.5 sm:gap-4 mb-4 sm:mb-8">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-emerald-500/10 rounded-lg sm:rounded-2xl flex items-center justify-center text-base sm:text-xl">👤</div>
                        <h4 class="text-sm sm:text-lg font-bold text-[var(--text-main)] tracking-tight">Informasi Dasar</h4>
                    </div>

                    <div class="grid md:grid-cols-2 gap-3 sm:gap-8">
                        <div class="space-y-1 sm:space-y-3">
                            <label class="text-[9px] sm:text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full bg-[var(--bg-main)] border border-[var(--border)] px-3 py-2 sm:px-5 sm:py-4 rounded-lg sm:rounded-2xl text-xs sm:text-sm font-bold text-[var(--text-main)] outline-none focus:ring-2 ring-emerald-500/50 transition" required>
                            @error('name') <p class="text-[9px] text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1 sm:space-y-3">
                            <label class="text-[9px] sm:text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full bg-[var(--bg-main)] border border-[var(--border)] px-3 py-2 sm:px-5 sm:py-4 rounded-lg sm:rounded-2xl text-xs sm:text-sm font-bold text-[var(--text-main)] outline-none focus:ring-2 ring-emerald-500/50 transition" required>
                            @error('email') <p class="text-[9px] text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-widest ml-1">Foto Profil</label>
                            <!-- Compact single-row photo section -->
                            <div class="flex items-center gap-3 p-3 bg-[var(--bg-main)] border border-[var(--border)] rounded-xl">
                                <div class="w-11 h-11 rounded-xl bg-[var(--bg-card)] border border-[var(--border)] flex items-center justify-center overflow-hidden flex-shrink-0" id="photo-preview">
                                    @if(auth()->user()->profile_photo && file_exists(public_path('uploads/profile/' . auth()->user()->profile_photo)))
                                        <img src="{{ asset('uploads/profile/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-lg">📷</span>
                                    @endif
                                </div>
                                <input type="file" id="photo-input" class="hidden" accept="image/*">
                                <div class="flex-1 flex items-center gap-2">
                                    <button type="button" onclick="document.getElementById('photo-input').click()" class="flex-1 text-[9px] font-black text-[var(--text-main)] uppercase tracking-wider bg-[var(--bg-card)] border border-[var(--border)] px-3 py-1.5 rounded-lg hover:bg-[var(--bg-main)] transition">Pilih Foto</button>
                                    @if(auth()->user()->profile_photo)
                                        <button type="button" id="delete-photo-btn" class="text-[9px] font-black text-rose-500 uppercase tracking-wider bg-rose-500/5 border border-rose-500/20 px-3 py-1.5 rounded-lg hover:bg-rose-500/10 transition">Hapus</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.getElementById('photo-input').addEventListener('change', function(e) {
                            const preview = document.getElementById('photo-preview');
                            const file = e.target.files[0];
                            if (file) {
                                // Show preview
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                                }
                                reader.readAsDataURL(file);

                                // Auto upload
                                const formData = new FormData();
                                formData.append('profile_photo', file);
                                formData.append('_token', '{{ csrf_token() }}');

                                preview.classList.add('opacity-50');
                                
                                fetch('{{ route("admin.profile.photo") }}', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    preview.classList.remove('opacity-50');
                                    if (data.success) {
                                        // Update sidebar photo if exists
                                        const sidebarPhotos = document.querySelectorAll('img[src*="uploads/profile/"]');
                                        sidebarPhotos.forEach(img => img.src = data.photo_url);
                                        
                                        // Show success toast or alert
                                        alert('Foto profil berhasil diperbarui!');
                                    } else {
                                        alert(data.message || 'Gagal memperbarui foto.');
                                    }
                                })
                                .catch(error => {
                                    preview.classList.remove('opacity-50');
                                    alert('Terjadi kesalahan saat mengunggah.');
                                });
                            }
                        });

                        const deleteBtn = document.getElementById('delete-photo-btn');
                        if (deleteBtn) {
                            deleteBtn.addEventListener('click', function() {
                                if (!confirm('Apakah Anda yakin ingin menghapus foto profil?')) return;

                                const preview = document.getElementById('photo-preview');
                                preview.classList.add('opacity-50');

                                fetch('{{ route("admin.profile.photo.delete") }}', {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    preview.classList.remove('opacity-50');
                                    if (data.success) {
                                        preview.innerHTML = '<span class="text-2xl">📷</span>';
                                        
                                        // Update sidebar photo/initials
                                        const sidebarPhotos = document.querySelectorAll('.overflow-hidden img[src*="uploads/profile/"]');
                                        sidebarPhotos.forEach(img => {
                                            const container = img.parentElement;
                                            const name = '{{ auth()->user()->name }}';
                                            container.innerHTML = `<span class="text-emerald-500 font-black text-xs uppercase">${name.charAt(0)}</span>`;
                                        });

                                        deleteBtn.remove();
                                        alert('Foto profil berhasil dihapus!');
                                        location.reload(); // Reload to refresh all instances
                                    } else {
                                        alert(data.message || 'Gagal menghapus foto.');
                                    }
                                })
                                .catch(error => {
                                    preview.classList.remove('opacity-50');
                                    alert('Terjadi kesalahan saat menghapus.');
                                });
                            });
                        }
                    </script>
                </div>

                <!-- SECURITY SECTION -->
                <div class="bg-[var(--bg-card)] border border-[var(--border)] p-4 sm:p-8 lg:p-10 rounded-xl sm:rounded-[3rem] relative overflow-hidden shadow-[var(--card-shadow)]">
                    <div class="flex items-center gap-2.5 sm:gap-4 mb-4 sm:mb-8">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-orange-500/10 rounded-lg sm:rounded-2xl flex items-center justify-center text-base sm:text-xl">🔐</div>
                        <div>
                            <h4 class="text-sm sm:text-lg font-bold text-[var(--text-main)] tracking-tight">Keamanan & Password</h4>
                            <p class="text-[8px] sm:text-[10px] text-[var(--text-muted)] font-medium">Kosongkan jika tidak ingin ganti password</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-3 sm:gap-8">
                        <div class="space-y-1 sm:space-y-3">
                            <label class="text-[9px] sm:text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest ml-1">Password Baru</label>
                            <input type="password" name="password" class="w-full bg-[var(--bg-main)] border border-[var(--border)] px-3 py-2 sm:px-5 sm:py-4 rounded-lg sm:rounded-2xl text-xs sm:text-sm font-bold text-[var(--text-main)] outline-none focus:ring-2 ring-orange-500/50 transition" placeholder="••••••••">
                            @error('password') <p class="text-[9px] text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1 sm:space-y-3">
                            <label class="text-[9px] sm:text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest ml-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full bg-[var(--bg-main)] border border-[var(--border)] px-3 py-2 sm:px-5 sm:py-4 rounded-lg sm:rounded-2xl text-xs sm:text-sm font-bold text-[var(--text-main)] outline-none focus:ring-2 ring-orange-500/50 transition" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit" class="group bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-5 sm:px-10 py-2 sm:py-4 rounded-lg sm:rounded-2xl shadow shadow-emerald-500/20 transition-all active:scale-95 flex items-center gap-2 text-xs sm:text-base">
                        <span>Simpan Perubahan</span>
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
