@extends('layouts.admin')

@section('title', 'Tambah Menu')
@section('header_title', 'Tambah Menu')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="px-2">
        <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center text-[10px] sm:text-xs font-black text-[var(--text-muted)] hover:text-emerald-500 transition-colors uppercase tracking-widest mb-4">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <h1 class="text-2xl sm:text-3xl font-poppins font-bold text-[var(--text-main)]">Tambah Menu Baru</h1>
        <p class="text-[var(--text-muted)] text-xs sm:text-sm font-medium mt-1">Buat paket catering baru — data akan sinkron ke Web & Android.</p>
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
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-8">
            @csrf

            {{-- Baris 1: Nama & Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Nama Paket Menu</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50"
                        placeholder="Contoh: Nasi Kotak Premium">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Kategori Layanan</label>
                    <select id="category_select" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                        <option value="Prasmanan" {{ old('category') == 'Prasmanan' ? 'selected' : '' }}>🍽️ Prasmanan (Buffet)</option>
                        <option value="Nasi Box" {{ old('category') == 'Nasi Box' ? 'selected' : '' }}>🍱 Nasi Box / Kotak</option>
                        <option value="Nasi Kotak" {{ old('category') == 'Nasi Kotak' ? 'selected' : '' }}>🍱 Nasi Kotak</option>
                        <option value="Tumpeng" {{ old('category') == 'Tumpeng' ? 'selected' : '' }}>🎋 Tumpeng</option>
                        <option value="Snack" {{ old('category') == 'Snack' ? 'selected' : '' }}>☕ Snack &amp; Coffee Break</option>
                        <option value="Minuman" {{ old('category') == 'Minuman' ? 'selected' : '' }}>🧋 Minuman</option>
                        <option value="Aqiqah" {{ old('category') == 'Aqiqah' ? 'selected' : '' }}>🐑 Aqiqah</option>
                        <option value="Lauk Pauk" {{ old('category') == 'Lauk Pauk' ? 'selected' : '' }}>🍗 Lauk Pauk</option>
                        <option value="Lain-lain" {{ old('category') == 'Lain-lain' ? 'selected' : '' }}>🎁 Lain-lain</option>
                    </select>

                    <div id="custom_category_container" class="space-y-3 mt-4 hidden transition-all duration-300">
                        <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Ketik Kategori Kustom</label>
                        <input type="text" id="custom_category_input" placeholder="Ketik nama kategori..."
                            class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                    </div>
                    <!-- Hidden input for category -->
                    <input type="hidden" name="category" id="category_hidden_input" value="{{ old('category') }}">
                </div>
            </div>

            {{-- Baris 2: Harga & Emoji --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Harga Satuan (IDR)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-6 text-[var(--text-muted)] font-bold text-sm">Rp</span>
                        <input type="text" id="price_display" value="{{ old('price') }}" required
                            class="w-full pl-14 pr-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-extrabold text-lg text-[var(--text-main)]"
                            placeholder="0" oninput="formatPrice(this)">
                        <input type="hidden" name="price" id="price_value" value="{{ old('price') }}">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Emoji Menu <span class="text-[var(--text-muted)]/50">(tampil di app Android)</span></label>
                    <input type="text" name="emoji" value="{{ old('emoji') }}" maxlength="20"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium text-2xl text-[var(--text-main)]"
                        placeholder="🍱">
                </div>
            </div>

            <script>
                function formatPrice(input) {
                    let val = input.value.replace(/\D/g, '');
                    document.getElementById('price_value').value = val;
                    if(val) {
                        input.value = new Intl.NumberFormat('id-ID').format(val);
                    } else {
                        input.value = '';
                    }
                }
                document.addEventListener('DOMContentLoaded', function() {
                    let input = document.getElementById('price_display');
                    if (input && input.value) { formatPrice(input); }
                });
            </script>

            {{-- Baris 3: Rating & Terjual --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Rating Awal (0–5) ⭐</label>
                    <input type="number" name="rating" value="{{ old('rating', 5.0) }}" min="0" max="5" step="0.1"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]"
                        placeholder="5.0">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Jumlah Terjual 🛒</label>
                    <input type="number" name="sold" value="{{ old('sold', 0) }}" min="0"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]"
                        placeholder="0">
                </div>
            </div>

            {{-- Baris 4: Label Status & Deskripsi & Foto --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-4 p-2 bg-[var(--bg-main)] border border-[var(--border)] rounded-2xl">
                            <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-emerald-500 text-[var(--text-muted)]">
                                <input type="radio" name="is_featured" value="1" class="hidden" {{ old('is_featured') == '1' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">⭐ Unggulan</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-[var(--text-main)] text-[var(--text-muted)]">
                                <input type="radio" name="is_featured" value="0" class="hidden" {{ old('is_featured', '0') == '0' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">Regular</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Ketersediaan Menu</label>
                        <div class="flex items-center space-x-4 p-2 bg-[var(--bg-main)] border border-[var(--border)] rounded-2xl">
                            <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-emerald-500 text-[var(--text-muted)]">
                                <input type="radio" name="is_available" value="1" class="hidden" {{ old('is_available', '1') == '1' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">✅ Tersedia</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-rose-500 text-[var(--text-muted)]">
                                <input type="radio" name="is_available" value="0" class="hidden" {{ old('is_available') == '0' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">🚫 Kosong</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Rincian Menu (Deskripsi)</label>
                        <textarea name="description" rows="5"
                            class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-medium leading-relaxed text-[var(--text-main)]"
                            placeholder="Sebutkan menu masakan, minuman, atau fasilitas yang didapat...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Foto Menu</label>
                    <div class="relative group h-[calc(100%-24px)] min-h-[200px]">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                        <div onclick="document.getElementById('image-input').click()"
                            class="w-full h-full border-2 border-dashed border-[var(--border)] rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-[var(--bg-main)] transition-all group-hover:border-emerald-500/50">
                            <div id="image-preview" class="flex flex-col items-center justify-center p-4">
                                <svg class="w-10 h-10 text-[var(--text-muted)] mb-2 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-[var(--text-muted)] group-hover:text-emerald-600 transition-colors">Klik untuk Unggah Foto</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('image-input').addEventListener('change', function(e) {
                    const preview = document.getElementById('image-preview');
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-48 object-cover rounded-xl shadow-lg">`;
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Custom category handling
                const categorySelect = document.getElementById('category_select');
                const customCategoryContainer = document.getElementById('custom_category_container');
                const customCategoryInput = document.getElementById('custom_category_input');
                const categoryHiddenInput = document.getElementById('category_hidden_input');

                function updateCategoryValue() {
                    if (categorySelect.value === 'Lain-lain') {
                        customCategoryContainer.classList.remove('hidden');
                        customCategoryInput.required = true;
                        categoryHiddenInput.value = customCategoryInput.value.trim();
                    } else {
                        customCategoryContainer.classList.add('hidden');
                        customCategoryInput.required = false;
                        categoryHiddenInput.value = categorySelect.value;
                    }
                }

                categorySelect.addEventListener('change', updateCategoryValue);
                customCategoryInput.addEventListener('input', () => {
                    if (categorySelect.value === 'Lain-lain') {
                        categoryHiddenInput.value = customCategoryInput.value.trim();
                    }
                });

                // Run initial check
                updateCategoryValue();
            </script>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-green-500 text-white font-extrabold rounded-2xl shadow-xl shadow-green-500/20 hover:bg-green-600 hover:scale-[1.01] active:scale-[0.99] transition-all text-sm sm:text-lg flex items-center justify-center space-x-3">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>Simpan Menu</span>
                </button>
            </div>
        </form>

        <div class="absolute -top-10 -right-10 w-40 h-40 bg-green-500/5 rounded-full blur-3xl"></div>
    </div>
</div>
@endsection
