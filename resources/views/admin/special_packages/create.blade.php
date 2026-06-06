@extends('layouts.admin')

@section('title', 'Tambah Paket Spesial')
@section('header_title', 'Tambah Paket')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div>
        <a href="{{ route('admin.special_packages.index') }}" class="inline-flex items-center text-xs font-bold text-[var(--text-muted)] hover:text-emerald-500 transition-colors uppercase tracking-widest mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Paket
        </a>
        <h1 class="text-3xl font-poppins font-bold text-[var(--text-main)]">Buat Paket Baru</h1>
        <p class="text-[var(--text-muted)] text-sm font-medium mt-1">Tambahkan kategori paket spesial baru ke halaman landing utama.</p>
    </div>

    <div class="bg-[var(--bg-card)] p-10 rounded-[2.5rem] shadow-[var(--card-shadow)] border border-[var(--border)] relative overflow-hidden">
        <form action="{{ route('admin.special_packages.store') }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Judul Paket</label>
                    <input type="text" name="title" required placeholder="Misal: Catering Pernikahan"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)]">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Badge / Label</label>
                    <input type="text" name="badge" placeholder="Misal: TERPOPULER"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                </div>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Deskripsi Singkat</label>
                <textarea name="description" rows="3" required placeholder="Berikan penjelasan singkat mengenai layanan paket ini..."
                    class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium leading-relaxed text-[var(--text-main)]"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Fitur Utama (Satu per baris)</label>
                    <textarea name="features" rows="6" placeholder="Menu Lengkap&#10;Peralatan Makan&#10;Pelayanan Profesional"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold leading-relaxed text-sm text-[var(--text-main)]"></textarea>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Foto Cover Paket</label>
                    <div id="pkg-upload-zone"
                        class="relative min-h-[220px] rounded-3xl border-2 border-dashed border-[var(--border)] overflow-hidden cursor-pointer hover:border-emerald-500/60 transition-all duration-300 group">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                        <p id="image-error" class="hidden absolute bottom-3 left-3 right-3 z-20 text-[11px] font-bold text-rose-500 bg-rose-500/10 border border-rose-500/20 rounded-xl px-3 py-2"></p>

                        {{-- Empty state --}}
                        <div id="img-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-4">
                            <svg class="w-12 h-12 text-[var(--text-muted)] mb-3 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-bold text-[var(--text-muted)] group-hover:text-emerald-600 transition-colors">Klik untuk Unggah Foto</span>
                        </div>

                        {{-- Selected badge --}}
                        <div id="selected-badge" class="hidden absolute top-3 right-3 z-20 bg-emerald-500 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg items-center gap-1.5">
                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Foto Baru Dipilih
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-green-500 text-white font-extrabold rounded-2xl shadow-xl shadow-green-500/20 hover:bg-green-600 hover:scale-[1.01] active:scale-[0.99] transition-all text-lg flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Terbitkan Paket</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const zone      = document.getElementById('pkg-upload-zone');
    const input     = document.getElementById('image-input');
    const badge     = document.getElementById('selected-badge');
    const errorBox  = document.getElementById('image-error');
    const maxBytes  = 5 * 1024 * 1024;

    zone.addEventListener('click', () => input.click());

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (errorBox) {
            errorBox.classList.add('hidden');
            errorBox.textContent = '';
        }

        if (file.size > maxBytes) {
            if (errorBox) {
                errorBox.textContent = 'Ukuran foto maksimal 5MB.';
                errorBox.classList.remove('hidden');
            }
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(ev) {
            // Create or update preview img
            let img = document.getElementById('preview-img');
            if (!img) {
                img = document.createElement('img');
                img.id = 'preview-img';
                img.className = 'absolute inset-0 w-full h-full object-cover';
                zone.prepend(img);
            }
            img.src = ev.target.result;

            // Hide placeholder
            const ph = document.getElementById('img-placeholder');
            if (ph) ph.style.display = 'none';

            // Add change-again hover overlay
            let ov = document.getElementById('hover-overlay');
            if (!ov) {
                ov = document.createElement('div');
                ov.id = 'hover-overlay';
                ov.className = 'absolute inset-0 bg-black/55 flex flex-col items-center justify-center z-10 pointer-events-none transition-opacity duration-300';
                ov.style.opacity = '0';
                ov.innerHTML = '<svg class="w-10 h-10 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span class="text-white font-black text-sm">Klik untuk Ganti Lagi</span>';
                zone.appendChild(ov);
                zone.addEventListener('mouseenter', () => ov.style.opacity = '1');
                zone.addEventListener('mouseleave', () => ov.style.opacity = '0');
            }

            if (badge) { badge.classList.remove('hidden'); badge.classList.add('flex'); }
            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.textContent = '';
            }
        };
        reader.readAsDataURL(file);
    });
})();
</script>
@endsection
