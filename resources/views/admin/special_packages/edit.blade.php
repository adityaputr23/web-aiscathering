@extends('layouts.admin')

@section('title', 'Edit Paket: ' . $specialPackage->title)
@section('header_title', 'Edit Paket')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div>
        <a href="{{ route('admin.special_packages.index') }}" class="inline-flex items-center text-xs font-bold text-[var(--text-muted)] hover:text-emerald-500 transition-colors uppercase tracking-widest mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Paket
        </a>
        <h1 class="text-3xl font-poppins font-bold text-[var(--text-main)]">Edit Rincian Paket</h1>
        <p class="text-[var(--text-muted)] text-sm font-medium mt-1">Perbarui informasi paket: <span class="text-emerald-500 font-bold">{{ $specialPackage->title }}</span></p>
    </div>

    @if($errors->any())
    <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4">
        <ul class="text-sm text-rose-500 list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="p-3 bg-green-500/10 border border-green-500/20 text-green-600 rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span class="text-xs font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-[var(--bg-card)] p-10 rounded-[2.5rem] shadow-[var(--card-shadow)] border border-[var(--border)] relative overflow-hidden">
        <form action="{{ route('admin.special_packages.update', $specialPackage) }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Judul Paket</label>
                    <input type="text" name="title" value="{{ $specialPackage->title }}" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)]">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Badge / Label</label>
                    <input type="text" name="badge" value="{{ $specialPackage->badge }}"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                </div>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Deskripsi Singkat</label>
                <textarea name="description" rows="3" required
                    class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium leading-relaxed text-[var(--text-main)]">{{ $specialPackage->description }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Fitur Utama (Satu per baris)</label>
                    <textarea name="features" rows="6"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold leading-relaxed text-sm text-[var(--text-main)]">{{ implode("\n", $specialPackage->features ?? []) }}</textarea>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Foto Cover Paket</label>
                    <div id="pkg-upload-zone"
                        class="relative min-h-[220px] rounded-3xl border-2 border-dashed border-[var(--border)] overflow-hidden cursor-pointer hover:border-emerald-500/60 transition-all duration-300 group">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                        <p id="image-error" class="hidden absolute bottom-3 left-3 right-3 z-20 text-[11px] font-bold text-rose-500 bg-rose-500/10 border border-rose-500/20 rounded-xl px-3 py-2"></p>

                        {{-- Existing image preview --}}
                        @if($specialPackage->image)
                        <img id="preview-img"
                            src="{{ filter_var($specialPackage->image, FILTER_VALIDATE_URL) ? $specialPackage->image : asset($specialPackage->image) }}"
                            class="absolute inset-0 w-full h-full object-cover">
                        <div id="hover-overlay"
                            class="absolute inset-0 bg-black/55 flex flex-col items-center justify-center z-10 pointer-events-none transition-opacity duration-300" style="opacity:0">
                            <svg class="w-10 h-10 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-white font-black text-sm" id="overlay-text">Klik untuk Ganti Foto</span>
                        </div>
                        @else
                        {{-- Empty state --}}
                        <div id="img-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-4">
                            <svg class="w-12 h-12 text-[var(--text-muted)] mb-3 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-bold text-[var(--text-muted)] group-hover:text-emerald-600 transition-colors">Klik untuk Unggah Foto</span>
                        </div>
                        @endif

                        {{-- Selected badge (shown after new file picked) --}}
                        <div id="selected-badge" class="hidden absolute top-3 right-3 z-20 bg-emerald-500 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg items-center gap-1.5">
                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Foto Baru Dipilih
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-green-500 text-white font-extrabold rounded-2xl shadow-xl shadow-green-500/20 hover:bg-green-600 hover:scale-[1.01] active:scale-[0.99] transition-all text-lg flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const zone      = document.getElementById('pkg-upload-zone');
    const input     = document.getElementById('image-input');
    const overlay   = document.getElementById('hover-overlay');
    const badge     = document.getElementById('selected-badge');
    const errorBox  = document.getElementById('image-error');
    const maxBytes  = 5 * 1024 * 1024;

    // Clicking the zone triggers file picker
    zone.addEventListener('click', () => input.click());

    // Hover effect for existing image overlay
    if (overlay) {
        zone.addEventListener('mouseenter', () => overlay.style.opacity = '1');
        zone.addEventListener('mouseleave', () => overlay.style.opacity = '0');
    }

    // Immediate preview when new file is selected
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

            // Create or update overlay
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
            } else {
                const span = ov.querySelector('span');
                if (span) span.textContent = 'Klik untuk Ganti Lagi';
            }

            // Show success badge
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
