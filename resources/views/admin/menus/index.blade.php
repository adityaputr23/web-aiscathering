@extends('layouts.admin')

@section('title', 'Kelola Menu')
@section('header_title', 'Pengelolaan Menu')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-4 sm:p-0">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-2">
        <div class="flex-1">
            <h1 class="text-xl sm:text-2xl font-poppins font-bold text-[var(--text-main)]">Daftar Paket Catering</h1>
            <p class="text-[var(--text-muted)] text-[10px] sm:text-sm font-medium mt-0.5">Management menu katalog.</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- View Toggles (Mobile Only) -->
            <div class="flex md:hidden bg-[var(--bg-main)] p-1 rounded-xl border border-[var(--border)] shrink-0">
                <button onclick="setMobileView('list')" id="btn-view-list" class="p-1.5 rounded-lg bg-[var(--bg-card)] shadow-sm text-emerald-500 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <button onclick="setMobileView('card')" id="btn-view-card" class="p-1.5 rounded-lg text-[var(--text-muted)] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                </button>
            </div>
            <a href="{{ route('admin.menus.create') }}" class="flex-1 sm:flex-none text-center px-6 py-2.5 bg-green-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-green-500/20 hover:scale-105 transition-all">
                + Tambah Menu
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 p-4 sm:p-6 rounded-2xl border border-emerald-500/20 font-bold text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif    <div class="bg-[var(--bg-card)] rounded-2xl sm:rounded-[2.5rem] shadow-[var(--card-shadow)] border border-[var(--border)] overflow-hidden transition-all duration-500">
        {{-- Desktop Table --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[var(--bg-main)] border-b border-[var(--border)]">
                        <th class="px-10 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] w-1/2">Informasi Menu</th>
                        <th class="px-8 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em]">Kategori</th>
                        <th class="px-8 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] text-right">Harga Satuan</th>
                        <th class="px-10 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @foreach($menus as $menu)
                    <tr class="hover:bg-[var(--bg-main)] transition-all group">
                        <td class="px-10 py-8">
                            <div class="flex items-center space-x-7">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-[var(--bg-main)] rounded-3xl shadow-sm border border-[var(--border)] overflow-hidden flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                        @if($menu->image_url && file_exists(public_path($menu->image_url)))
                                            <img src="{{ asset($menu->image_url) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-3xl">{{ $menu->emoji ?: '🍱' }}</span>
                                        @endif
                                    </div>
                                    @if($menu->is_featured)
                                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-orange-500 rounded-full border-4 border-white dark:border-slate-900 flex items-center justify-center shadow-lg" title="Unggulan">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                    </div>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-[var(--text-main)] text-base flex items-center">
                                        {{ $menu->name }}
                                        @if($menu->is_featured)
                                        <span class="ml-3 px-2.5 py-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 rounded-lg text-[9px] font-black uppercase tracking-widest">Unggulan</span>
                                        @endif
                                    </h4>
                                    <div class="flex items-center gap-3 text-[10px] font-bold">
                                        <span class="text-yellow-500">⭐ {{ number_format($menu->rating, 1) }}</span>
                                        <span class="text-[var(--text-muted)]">🛒 {{ number_format($menu->sold) }} terjual</span>
                                        <span class="text-[var(--border)]">|</span>
                                        <p class="text-[var(--text-muted)] font-medium line-clamp-1 max-w-[200px]">{{ $menu->description }}</p>
                                    </div>
                                    @if(!$menu->is_available)
                                    <div class="mt-2">
                                        <span class="px-2.5 py-1 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-red-100 dark:border-red-900/30">Tidak Tersedia</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-8">
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-colors
                                {{ str_contains(strtolower($menu->category), 'prasmanan') ? 'bg-orange-50/50 text-orange-600 border-orange-100 dark:bg-orange-900/10 dark:border-orange-900/30' : 
                                   (str_contains(strtolower($menu->category), 'box') ? 'bg-blue-50/50 text-blue-600 border-blue-100 dark:bg-blue-900/10 dark:border-blue-900/30' : 
                                   'bg-purple-50/50 text-purple-600 border-purple-100 dark:bg-purple-900/10 dark:border-purple-900/30') }}">
                                <span class="w-1 h-1 rounded-full mr-2 {{ str_contains(strtolower($menu->category), 'prasmanan') ? 'bg-orange-500' : (str_contains(strtolower($menu->category), 'box') ? 'bg-blue-500' : 'bg-purple-500') }}"></span>
                                {{ $menu->category }}
                            </span>
                        </td>
                        <td class="px-8 py-8 text-right">
                            <span class="text-sm font-black text-[var(--text-main)]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="w-11 h-11 bg-[var(--bg-main)] text-[var(--text-muted)] hover:text-emerald-500 hover:bg-[var(--bg-card)] rounded-2xl flex items-center justify-center transition-all border border-transparent hover:border-[var(--border)] shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-11 h-11 bg-[var(--bg-main)] text-[var(--text-muted)] hover:text-red-500 hover:bg-[var(--bg-card)] rounded-2xl flex items-center justify-center transition-all border border-transparent hover:border-[var(--border)] shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile View --}}
        <div id="mobile-menu-container" class="md:hidden">
            <!-- List View (Default) -->
            <div id="mobile-list-view" class="divide-y divide-[var(--border)]">
                @foreach($menus as $menu)
                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-[var(--bg-main)] rounded-2xl overflow-hidden flex items-center justify-center border border-[var(--border)] shrink-0 shadow-inner">
                            @if($menu->image_url && file_exists(public_path($menu->image_url)))
                                <img src="{{ asset($menu->image_url) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xl">{{ $menu->emoji ?: '🍱' }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-[var(--text-main)] text-sm truncate">{{ $menu->name }}</h4>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-widest">{{ $menu->category }}</span>
                                @if(!$menu->is_available)
                                    <span class="text-[8px] text-red-500 font-black uppercase px-1.5 py-0.5 bg-red-500/10 rounded">OFF</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[13px] font-black text-[var(--text-main)]">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex gap-3 text-[10px] font-bold">
                            <span class="text-yellow-500 flex items-center gap-1">⭐ {{ number_format($menu->rating, 1) }}</span>
                            <span class="text-[var(--text-muted)]">{{ number_format($menu->sold) }} terjual</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.menus.edit', $menu) }}" class="p-2 bg-[var(--bg-main)] text-[var(--text-muted)] hover:text-emerald-500 rounded-lg border border-[var(--border)] shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus?')" class="m-0">
                                @csrf @method('DELETE')
                                <button class="p-2 bg-red-500/10 text-red-500 rounded-lg border border-red-500/20 shadow-sm"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Card View -->
            <div id="mobile-card-view" class="hidden grid grid-cols-2 gap-3 p-4 bg-[var(--bg-card)]">
                @foreach($menus as $menu)
                <div class="p-3 bg-[var(--bg-main)] rounded-2xl border border-[var(--border)] relative shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="absolute top-2 right-2 flex gap-1 z-10">
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="p-1.5 bg-[var(--bg-card)] text-[var(--text-muted)] hover:text-emerald-500 rounded-lg shadow-sm border border-[var(--border)]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                    
                    <div class="flex flex-col items-center text-center mt-1">
                        <div class="w-14 h-14 bg-[var(--bg-card)] rounded-full overflow-hidden flex items-center justify-center border border-[var(--border)] shadow-inner mb-2">
                            @if($menu->image_url && file_exists(public_path($menu->image_url)))
                                <img src="{{ asset($menu->image_url) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl">{{ $menu->emoji ?: '🍱' }}</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-[var(--text-main)] text-xs line-clamp-2 leading-tight px-1">{{ $menu->name }}</h4>
                        <span class="text-[8px] font-black text-[var(--text-muted)] uppercase tracking-widest mt-1">{{ $menu->category }}</span>
                        @if(!$menu->is_available)
                            <span class="text-[8px] text-red-500 font-black uppercase mt-0.5">Tidak Tersedia</span>
                        @endif
                    </div>
                    
                    <div class="mt-3 pt-2 border-t border-[var(--border)] flex flex-col gap-1 items-center">
                        <span class="text-[11px] font-black text-emerald-500">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        <div class="flex gap-2 text-[9px]">
                            <span class="text-yellow-500 font-bold">⭐{{ number_format($menu->rating, 1) }}</span>
                            <span class="text-[var(--text-muted)]">{{ number_format($menu->sold) }} trjl</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function setMobileView(view) {
    const listView = document.getElementById('mobile-list-view');
    const cardView = document.getElementById('mobile-card-view');
    const btnList = document.getElementById('btn-view-list');
    const btnCard = document.getElementById('btn-view-card');

    if (view === 'card') {
        listView.classList.add('hidden');
        cardView.classList.remove('hidden');
        
        btnCard.classList.add('bg-[var(--bg-card)]', 'shadow-sm', 'text-emerald-500');
        btnCard.classList.remove('text-[var(--text-muted)]');
        
        btnList.classList.remove('bg-[var(--bg-card)]', 'shadow-sm', 'text-emerald-500');
        btnList.classList.add('text-[var(--text-muted)]');
        
        localStorage.setItem('admin_menu_mobile_view', 'card');
    } else {
        cardView.classList.add('hidden');
        listView.classList.remove('hidden');
        
        btnList.classList.add('bg-[var(--bg-card)]', 'shadow-sm', 'text-emerald-500');
        btnList.classList.remove('text-[var(--text-muted)]');
        
        btnCard.classList.remove('bg-[var(--bg-card)]', 'shadow-sm', 'text-emerald-500');
        btnCard.classList.add('text-[var(--text-muted)]');
        
        localStorage.setItem('admin_menu_mobile_view', 'list');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const savedView = localStorage.getItem('admin_menu_mobile_view') || 'list';
    setMobileView(savedView);
});
</script>
@endpush
