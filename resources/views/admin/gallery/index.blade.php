@extends('layouts.admin')

@section('title', 'Galeri Foto')
@section('header_title', 'Kelola Galeri Foto')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-[var(--bg-card)] p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-[var(--border)] shadow-[var(--card-shadow)] gap-4 sm:gap-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-poppins font-bold text-[var(--text-main)]">Momen & Hidangan Kami</h1>
            <p class="text-[var(--text-muted)] text-xs sm:text-sm font-medium mt-1">Tambahkan foto-foto terbaik acara dan hidangan AISH Catering.</p>
        </div>
        <button onclick="document.getElementById('add-photo-modal').classList.remove('hidden')" class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 bg-green-500 text-white rounded-xl sm:rounded-2xl font-bold hover:bg-green-600 transition-all shadow-lg shadow-green-200">
            + Tambah Foto
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/10 text-green-600 dark:text-green-400 p-6 rounded-2xl border border-green-100 dark:border-green-900/20 font-bold animate-fade-in">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Photo Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-8">
        @forelse($galleries as $photo)
            <div class="group bg-[var(--bg-card)] rounded-2xl sm:rounded-[2.5rem] overflow-hidden border border-[var(--border)] shadow-[var(--card-shadow)] hover:shadow-xl transition-all relative flex flex-col">
                <div class="aspect-square relative overflow-hidden">
                    <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col items-center justify-center space-y-4">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button onclick="openEditModal({{ $photo->id }}, '{{ $photo->title }}', '{{ asset($photo->image_path) }}')" class="p-2.5 sm:p-4 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-all transform scale-50 opacity-0 group-hover:scale-100 group-hover:opacity-100 duration-500 delay-75 shadow-md">
                                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <form action="{{ route('admin.gallery.destroy', $photo) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 sm:p-4 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all transform scale-50 opacity-0 group-hover:scale-100 group-hover:opacity-100 duration-500 shadow-md">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @if($photo->title)
                    <div class="p-3 sm:p-6 flex-grow flex items-center justify-center">
                        <h4 class="font-bold text-[10px] sm:text-base text-[var(--text-main)] text-center line-clamp-2">{{ $photo->title }}</h4>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-16 sm:py-24 text-center bg-[var(--bg-card)] rounded-3xl sm:rounded-[3rem] border-2 border-dashed border-[var(--border)] shadow-[var(--card-shadow)]">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[var(--bg-main)] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 text-2xl sm:text-3xl">🖼️</div>
                <h3 class="text-lg sm:text-xl font-bold text-[var(--text-main)]">Belum ada foto</h3>
                <p class="text-[10px] sm:text-sm text-[var(--text-muted)] mt-1 sm:mt-2">Klik tombol "Tambah Foto" untuk mulai mengisi galeri.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Photo Modal -->
<div id="add-photo-modal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] sm:w-full max-w-xl">
        <div class="bg-[var(--bg-card)] rounded-3xl sm:rounded-[3rem] shadow-2xl border border-[var(--border)] overflow-hidden animate-reveal">
            <div class="p-6 sm:p-8 space-y-4 sm:space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl sm:text-2xl font-bold text-[var(--text-main)]">Tambah Foto</h3>
                    <button onclick="document.getElementById('add-photo-modal').classList.add('hidden')" class="text-[var(--text-muted)] hover:text-[var(--text-main)] transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Pilih Foto (Max 2MB)</label>
                            <div class="relative group">
                                <input type="file" name="image" required accept="image/jpeg,image/png,image/jpg,image/gif,image/avif" class="w-full text-xs text-[var(--text-muted)] file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all cursor-pointer">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Judul / Caption (Opsional)</label>
                            <input type="text" name="title" placeholder="Misal: Prasmanan Pernikahan Rina & Andi" class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-green-500 focus:bg-[var(--bg-card)] focus:ring-0 transition-all font-medium text-[var(--text-main)]">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="document.getElementById('add-photo-modal').classList.add('hidden')" class="flex-1 py-4 bg-[var(--bg-main)] text-[var(--text-muted)] rounded-2xl font-bold hover:bg-[var(--border)] transition-all uppercase text-[10px] tracking-widest">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-green-500 text-white rounded-2xl font-bold hover:bg-green-600 transition-all shadow-xl shadow-green-200 uppercase text-[10px] tracking-widest">Simpan Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Photo Modal -->
<div id="edit-photo-modal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] sm:w-full max-w-xl">
        <div class="bg-white dark:bg-slate-900 rounded-3xl sm:rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-reveal">
            <div class="p-6 sm:p-8 space-y-4 sm:space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100">Edit Foto</h3>
                    <button onclick="document.getElementById('edit-photo-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <form id="edit-gallery-form" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <!-- Current Preview -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Foto Saat Ini</label>
                            <div class="w-full h-40 rounded-2xl overflow-hidden bg-slate-100">
                                <img id="edit-preview" src="" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ganti Foto (Opsional - Max 2MB)</label>
                            <div class="relative group">
                                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/avif" class="w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul / Caption</label>
                            <input type="text" name="title" id="edit-title" class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-transparent focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-0 transition-all font-medium">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="document.getElementById('edit-photo-modal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all uppercase text-[10px] tracking-widest">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-blue-500 text-white rounded-2xl font-bold hover:bg-blue-600 transition-all shadow-xl shadow-blue-200 uppercase text-[10px] tracking-widest">Perbarui Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const fileInput = this.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const fileSizeMB = file.size / (1024 * 1024);
                const serverLimit = 2; // MB

                if (fileSizeMB > serverLimit) {
                    e.preventDefault();
                    alert(`⚠️ Ukuran file terlalu besar (${fileSizeMB.toFixed(1)}MB).\n\nLimit server (PHP) saat ini adalah ${serverLimit}MB.\n\nSilakan gunakan foto yang lebih kecil atau perkecil ukuran foto Anda.`);
                }
            }
        });
    });

    function openEditModal(id, title, imgSrc) {
        const modal = document.getElementById('edit-photo-modal');
        const form = document.getElementById('edit-gallery-form');
        const preview = document.getElementById('edit-preview');
        const titleInput = document.getElementById('edit-title');

        form.action = `/admin/gallery/${id}`;
        preview.src = imgSrc;
        titleInput.value = title === 'null' ? '' : title;

        modal.classList.remove('hidden');
    }
</script>
@endpush
@endsection
