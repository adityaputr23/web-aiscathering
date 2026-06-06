@extends('layouts.admin')

@section('title', 'Edit Menu: ' . $menu->name)
@section('header_title', 'Edit Menu')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="px-2">
        <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center text-[10px] sm:text-xs font-black text-[var(--text-muted)] hover:text-emerald-500 transition-colors uppercase tracking-widest mb-4">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <h1 class="text-2xl sm:text-3xl font-poppins font-bold text-[var(--text-main)]">Edit Menu Paket</h1>
        <p class="text-[var(--text-muted)] text-xs sm:text-sm font-medium mt-1">Perbarui: <span class="text-emerald-500 font-bold">{{ $menu->name }}</span> — Web & App Sync.</p>
    </div>

    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-4">
        <ul class="text-sm text-red-500 list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-[var(--bg-card)] p-5 sm:p-10 rounded-3xl sm:rounded-[2.5rem] shadow-[var(--card-shadow)] border border-[var(--border)] relative overflow-hidden">
        <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-8">
            @csrf
            @method('PUT')

            {{-- Nama & Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Nama Paket Menu</label>
                    <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 transition-all outline-none font-medium text-[var(--text-main)]">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Kategori Layanan</label>
                    <select id="category_select" required class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                        @php
                            $predefined = ['Prasmanan', 'Nasi Box', 'Nasi Kotak', 'Tumpeng', 'Snack', 'Minuman', 'Aqiqah', 'Lauk Pauk'];
                            $cat = old('category', $menu->category);
                            $isCustom = !empty($cat) && !in_array($cat, $predefined) && $cat !== 'Lain-lain';
                        @endphp
                        <option value="Prasmanan"  {{ ($cat == 'Prasmanan' && !$isCustom)  ? 'selected' : '' }}>🍽️ Prasmanan (Buffet)</option>
                        <option value="Nasi Box"   {{ ($cat == 'Nasi Box' && !$isCustom)   ? 'selected' : '' }}>🍱 Nasi Box / Kotak</option>
                        <option value="Nasi Kotak" {{ ($cat == 'Nasi Kotak' && !$isCustom) ? 'selected' : '' }}>🍱 Nasi Kotak</option>
                        <option value="Tumpeng"    {{ ($cat == 'Tumpeng' && !$isCustom)    ? 'selected' : '' }}>🎋 Tumpeng</option>
                        <option value="Snack"      {{ ($cat == 'Snack' && !$isCustom)      ? 'selected' : '' }}>☕ Snack &amp; Coffee Break</option>
                        <option value="Minuman"    {{ ($cat == 'Minuman' && !$isCustom)    ? 'selected' : '' }}>🧋 Minuman</option>
                        <option value="Aqiqah"     {{ ($cat == 'Aqiqah' && !$isCustom)     ? 'selected' : '' }}>🐑 Aqiqah</option>
                        <option value="Lauk Pauk"  {{ ($cat == 'Lauk Pauk' && !$isCustom)  ? 'selected' : '' }}>🍗 Lauk Pauk</option>
                        <option value="Lain-lain"  {{ ($cat == 'Lain-lain' || $isCustom)  ? 'selected' : '' }}>🎁 Lain-lain</option>
                    </select>

                    <div id="custom_category_container" class="space-y-3 mt-4 {{ $isCustom ? '' : 'hidden' }} transition-all duration-300">
                        <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Ketik Kategori Kustom</label>
                        <input type="text" id="custom_category_input" value="{{ $isCustom ? $cat : '' }}" placeholder="Ketik nama kategori..."
                            class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:bg-[var(--bg-card)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                    </div>
                    <!-- Hidden input for category -->
                    <input type="hidden" name="category" id="category_hidden_input" value="{{ $cat }}">
                </div>
            </div>

            {{-- Harga & Emoji --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Harga Satuan (IDR)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-6 text-[var(--text-muted)] font-bold text-sm">Rp</span>
                        <input type="text" id="price_display" value="{{ number_format(old('price', (int)$menu->price), 0, ',', '.') }}" required
                            class="w-full pl-14 pr-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 transition-all outline-none font-extrabold text-lg text-[var(--text-main)]"
                            oninput="formatPrice(this)">
                        <input type="hidden" name="price" id="price_value" value="{{ old('price', (int)$menu->price) }}">
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Emoji Menu <span class="normal-case text-[var(--text-muted)] font-medium">(tampil di Android)</span></label>
                    <input type="text" name="emoji" value="{{ old('emoji', $menu->emoji) }}" maxlength="20"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 transition-all outline-none font-medium text-2xl text-[var(--text-main)]"
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
            </script>

            {{-- Rating & Sold --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Rating (0–5) ⭐</label>
                    <input type="number" name="rating" value="{{ old('rating', $menu->rating) }}" min="0" max="5" step="0.1"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Jumlah Terjual 🛒</label>
                    <input type="number" name="sold" value="{{ old('sold', $menu->sold) }}" min="0"
                        class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 transition-all outline-none font-bold text-[var(--text-main)]">
                </div>
            </div>

            {{-- Status, Deskripsi & Foto --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-4 p-2 bg-[var(--bg-main)] rounded-2xl border border-[var(--border)]">
                            <label class="flex-1 flex items-center justify-center px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-emerald-500 text-[var(--text-muted)]">
                                <input type="radio" name="is_featured" value="1" class="hidden" {{ $menu->is_featured ? 'checked' : '' }}>
                                <span class="text-sm font-bold">⭐ Unggulan</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-[var(--text-main)] text-[var(--text-muted)]">
                                <input type="radio" name="is_featured" value="0" class="hidden" {{ !$menu->is_featured ? 'checked' : '' }}>
                                <span class="text-sm font-bold">Regular</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Ketersediaan Menu</label>
                        <div class="flex items-center space-x-4 p-2 bg-[var(--bg-main)] rounded-2xl border border-[var(--border)]">
                            <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-emerald-500 text-[var(--text-muted)]">
                                <input type="radio" name="is_available" value="1" class="hidden" {{ old('is_available', $menu->is_available) == '1' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">✅ Tersedia</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 rounded-xl cursor-pointer transition-all has-[:checked]:bg-[var(--bg-card)] has-[:checked]:shadow-sm has-[:checked]:text-red-500 text-[var(--text-muted)]">
                                <input type="radio" name="is_available" value="0" class="hidden" {{ old('is_available', $menu->is_available) == '0' ? 'checked' : '' }}>
                                <span class="text-sm font-bold">🚫 Kosong</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Deskripsi Menu</label>
                        <textarea name="description" rows="5"
                            class="w-full px-6 py-4 rounded-2xl bg-[var(--bg-main)] border border-[var(--border)] focus:border-emerald-500/30 transition-all outline-none font-medium leading-relaxed text-[var(--text-main)]">{{ old('description', $menu->description) }}</textarea>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] ml-1">Foto Menu</label>
                    <div class="relative group h-[calc(100%-24px)] min-h-[200px]">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                        <div onclick="document.getElementById('image-input').click()"
                            class="w-full h-full border-2 border-dashed border-[var(--border)] rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-[var(--bg-main)] transition-all group-hover:border-emerald-500/50">
                            <div id="image-preview" class="flex flex-col items-center justify-center p-4 w-full">
                                @if($menu->image_url && file_exists(public_path($menu->image_url)))
                                    <img src="{{ asset($menu->image_url) }}" class="w-full h-48 object-cover rounded-xl shadow-lg">
                                    <span class="text-xs text-[var(--text-muted)] mt-2">Klik untuk Ganti Foto</span>
                                @else
                                    <svg class="w-10 h-10 text-[var(--text-muted)] mb-2 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-bold text-[var(--text-muted)]">Klik untuk Unggah Foto</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('image-input').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            document.getElementById('image-preview').innerHTML =
                                `<img src="${e.target.result}" class="w-full h-48 object-cover rounded-xl shadow-lg"><span class="text-xs text-slate-400 mt-2">Foto baru dipilih</span>`;
                        };
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

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-green-500 text-white font-extrabold rounded-2xl shadow-xl shadow-green-500/20 hover:bg-green-600 hover:scale-[1.01] active:scale-[0.99] transition-all text-sm sm:text-lg flex items-center justify-center space-x-3">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-orange-500/5 rounded-full blur-3xl"></div>
    </div>
</div>
@endsection
