@extends('layouts.admin')

@section('title', 'Konten Website')
@section('header_title', 'Kelola Konten Website')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6 bg-[var(--bg-card)] p-6 sm:p-10 rounded-[2rem] sm:rounded-[3rem] border border-[var(--border)] shadow-[var(--card-shadow)] relative overflow-hidden transition-all duration-500">
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-poppins font-black text-[var(--text-main)] leading-tight">Kustomisasi Konten Web</h1>
            <p class="text-[var(--text-muted)] text-xs sm:text-sm font-medium mt-1 sm:mt-2 max-w-xl">Ubah teks, deskripsi, dan foto di landing page untuk memberikan kesan terbaik bagi pelanggan Anda.</p>
        </div>
        <div class="relative z-10 flex gap-3">
            <a href="{{ route('home') }}" target="_blank" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-[var(--bg-main)] hover:bg-emerald-500/10 text-[var(--text-main)] text-sm sm:text-base border border-[var(--border)] rounded-xl sm:rounded-2xl font-bold transition flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Lihat Web
            </a>
        </div>
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-emerald-500/10 blur-[100px] rounded-full"></div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-6 rounded-[2rem] font-bold flex items-center gap-3">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-6 rounded-[2rem] font-bold space-y-2">
            <div class="flex items-center gap-3 mb-2">
                <span>⚠️</span> Ada masalah saat menyimpan:
            </div>
            <ul class="list-disc list-inside text-xs font-medium ml-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Content Sections -->
    <div class="space-y-12">
        
        @php
            $sections = [
                'hero' => ['title' => '🚀 Bagian Beranda (Hero)', 'icon' => '🏠'],
                'about' => ['title' => 'ℹ️ Tentang Kami (About)', 'icon' => '🍲'],
                'contact' => ['title' => '📞 Kontak & Lokasi', 'icon' => '📍'],
                'gallery' => ['title' => '🖼️ Galeri Beranda', 'icon' => '📸'],
                'packages' => ['title' => '🎁 Foto Banner Paket', 'icon' => '📦'],
                'footer' => ['title' => '🔖 Footer & Lainnya', 'icon' => '✨'],
            ];

            $names = [
                'hero_title_1' => 'Judul Utama (Baris 1)',
                'hero_title_2' => 'Judul Utama (Baris 2)',
                'hero_subtitle' => 'Sub-judul Beranda',
                'hero_image' => 'Foto Hero Utama (Background)',
                'hero_video' => 'URL Video Background Hero (MP4)',
                'about_title' => 'Judul Tentang Kami',
                'about_description' => 'Deskripsi Lengkap Tentang Kami',
                'about_image' => 'Foto Utama Tentang Kami',
                'whatsapp_number' => 'Nomor WhatsApp (Contoh: 62812...)',
                'email' => 'Alamat Email Bisnis',
                'footer_copy' => 'Teks Hak Cipta Footer',
            ];
        @endphp

        @foreach($sections as $secKey => $secInfo)
        <section class="space-y-4 sm:space-y-6">
            <div class="flex items-center gap-3 sm:gap-4 px-2">
                <span class="text-xl sm:text-2xl">{{ $secInfo['icon'] }}</span>
                <h3 class="text-base sm:text-xl font-black text-[var(--text-main)] uppercase tracking-widest">{{ $secInfo['title'] }}</h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">
                @foreach($contents->where('section', $secKey) as $content)
                <div class="bg-[var(--bg-card)] p-5 sm:p-8 rounded-[1.5rem] sm:rounded-[2.5rem] border border-[var(--border)] hover:border-emerald-500/20 shadow-[var(--card-shadow)] transition-all group relative overflow-hidden duration-500">
                    <form action="{{ route('admin.content.update', $content) }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-2">
                            <h4 class="text-[9px] sm:text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] leading-snug">
                                {{ $names[$content->key] ?? str_replace('_', ' ', $content->key) }}
                            </h4>
                        </div>

                        @if($content->type == 'image')
                        <div class="space-y-4">
                            @if($content->value)
                                <div class="relative w-full h-48 rounded-2xl overflow-hidden bg-white/5 border border-white/10 group/img">
                                    <img src="{{ filter_var($content->value, FILTER_VALIDATE_URL) ? $content->value : asset($content->value) }}" class="w-full h-full object-cover transition-transform group-hover/img:scale-105 duration-700">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity">
                                        <span class="text-[10px] font-black text-white uppercase tracking-widest bg-emerald-500 px-3 py-1 rounded-full">Ganti Foto</span>
                                    </div>
                                </div>
                            @endif
                            <div class="space-y-3">
                                <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Upload File Baru - Max 2MB</label>
                                <input type="file" name="value" accept="image/jpeg,image/png,image/jpg,image/gif,image/avif" class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-white/5 file:text-slate-300 hover:file:bg-white/10 transition-all cursor-pointer">
                                <div class="relative flex items-center">
                                    <div class="flex-grow border-t border-[var(--border)]"></div>
                                    <span class="flex-shrink mx-4 text-[9px] font-bold text-[var(--text-muted)] uppercase">Atau Gunakan URL</span>
                                    <div class="flex-grow border-t border-[var(--border)]"></div>
                                </div>
                                <input type="text" name="value_text" placeholder="https://..." class="w-full px-4 py-3 rounded-xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 text-[var(--text-main)] text-[10px] font-medium outline-none transition-all">
                            </div>
                        </div>
                        @elseif($content->type == 'video')
                        <div class="space-y-4">
                            @if($content->value)
                                <div class="relative w-full h-48 rounded-2xl overflow-hidden bg-white/5 border border-white/10 group/vid">
                                    <video src="{{ filter_var($content->value, FILTER_VALIDATE_URL) ? $content->value : asset($content->value) }}" class="w-full h-full object-cover" muted loop autoplay></video>
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/vid:opacity-100 transition-opacity">
                                        <span class="text-[10px] font-black text-white uppercase tracking-widest bg-emerald-500 px-3 py-1 rounded-full">Ganti Video</span>
                                    </div>
                                </div>
                            @endif
                            <div class="space-y-3">
                                <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Upload Video (MP4) - Max 2MB</label>
                                <input type="file" name="value" accept="video/mp4,video/quicktime,video/ogg" class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-white/5 file:text-slate-300 hover:file:bg-white/10 transition-all cursor-pointer">
                                <div class="relative flex items-center">
                                    <div class="flex-grow border-t border-[var(--border)]"></div>
                                    <span class="flex-shrink mx-4 text-[9px] font-bold text-[var(--text-muted)] uppercase">Atau Gunakan URL</span>
                                    <div class="flex-grow border-t border-[var(--border)]"></div>
                                </div>
                                <input type="text" name="value_text" placeholder="https://..." class="w-full px-4 py-3 rounded-xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 text-[var(--text-main)] text-[10px] font-medium outline-none transition-all">
                            </div>
                        </div>
                        @elseif($content->type == 'text' || strlen($content->value) > 50)
                        <textarea name="value" rows="5" class="w-full px-4 py-3 sm:px-6 sm:py-5 rounded-xl sm:rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 text-[var(--text-main)] text-sm sm:text-base font-medium outline-none transition-all leading-relaxed">{{ $content->value }}</textarea>
                        @else
                        <input type="text" name="value" value="{{ $content->value }}" class="w-full px-4 py-3 sm:px-6 sm:py-4 rounded-xl sm:rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 text-[var(--text-main)] text-sm sm:text-base font-bold outline-none transition-all">
                        @endif
                        
                        <div class="flex items-end justify-between pt-4 border-t border-[var(--border)]">
                            <div class="text-[8px] sm:text-[9px] font-bold text-[var(--text-muted)] uppercase tracking-widest flex flex-col gap-1.5">
                                <span>Key: <code class="text-emerald-500/50">{{ $content->key }}</code></span>
                                <span>Aktif: {{ $content->updated_at?->diffForHumans() ?? 'Sekarang' }}</span>
                            </div>
                            <button type="submit" class="px-5 py-2.5 sm:px-6 sm:py-3 bg-emerald-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-widest rounded-xl sm:rounded-2xl hover:bg-emerald-600 transition-all shadow-md sm:shadow-lg shadow-emerald-500/20 shrink-0">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
                @endforeach
            </div>
        </section>
        @endforeach
    </div>

    <!-- Additional Photos Section (Galeri) Shortcut -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 p-10 rounded-[3rem] shadow-2xl text-white flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="space-y-3">
            <h3 class="text-2xl font-black font-poppins">Butuh menambah lebih banyak foto?</h3>
            <p class="text-emerald-100 text-sm font-medium">Kelola galeri utama untuk menampilkan dokumentasi acara dan hidangan terbaik Anda secara berkelanjutan.</p>
        </div>
        <a href="{{ route('admin.gallery.index') }}" class="px-10 py-5 bg-white text-emerald-600 rounded-[2rem] font-black uppercase tracking-widest text-xs hover:scale-105 active:scale-95 transition shadow-2xl">
            Buka Manajemen Galeri 🖼️
        </a>
    </div>

</div>
@push('scripts')
<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const fileInput = this.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const isVideo = fileInput.name === 'value' && form.querySelector('video') !== null;
                const fileSizeMB = file.size / (1024 * 1024);
                
                // Allowed extensions check
                const allowedExtensions = isVideo ? ['mp4', 'mov', 'ogg', 'qt'] : ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif'];
                const fileExt = file.name.split('.').pop().toLowerCase();
                
                if (!allowedExtensions.includes(fileExt)) {
                    e.preventDefault();
                    alert(`⚠️ Format file tidak didukung (.${fileExt}).\n\nFormat yang diizinkan: ${allowedExtensions.join(', ')}`);
                    return;
                }

                // Server limits check (from php -i)
                const serverLimit = 2; // MB (upload_max_filesize)
                
                if (fileSizeMB > serverLimit) {
                    e.preventDefault();
                    alert(`⚠️ Ukuran file terlalu besar (${fileSizeMB.toFixed(1)}MB).\n\nLimit server (PHP) saat ini adalah ${serverLimit}MB.\n\nSaran:\n1. Gunakan video dengan ukuran di bawah ${serverLimit}MB.\n2. Atau gunakan opsi "Gunakan URL" dengan memasukkan link video dari luar (Mixkit/Google Drive).`);
                }
            }
        });
    });
</script>
@endpush
@endsection
