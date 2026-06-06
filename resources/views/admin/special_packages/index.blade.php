@extends('layouts.admin')

@section('title', 'Manajemen Paket Spesial')
@section('header_title', 'Paket Spesial')

@section('content')
<div class="space-y-4 sm:space-y-8 animate-fade-in">
    <!-- Compact Mobile Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base sm:text-3xl font-poppins font-bold text-[var(--text-main)] leading-tight">Pilihan Paket</h1>
            <p class="text-[var(--text-muted)] text-[9px] sm:text-sm font-medium mt-0.5">Kelola paket spesial di halaman utama.</p>
        </div>
        <a href="{{ route('admin.special_packages.create') }}"
           class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-2 sm:py-3 bg-green-500 text-white font-bold rounded-xl sm:rounded-2xl shadow shadow-green-500/30 hover:bg-green-600 active:scale-95 transition-all text-[10px] sm:text-sm whitespace-nowrap">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">Tambah Paket Baru</span>
            <span class="sm:hidden">Tambah</span>
        </a>
    </div>

    @if(session('success'))
    <div class="p-3 bg-green-500/10 border border-green-500/20 text-green-600 rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span class="text-xs font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-8">
        @forelse($packages as $package)
        <div class="group bg-[var(--bg-card)] rounded-xl sm:rounded-[2.5rem] overflow-hidden border border-[var(--border)] shadow-[var(--card-shadow)] hover:shadow-lg transition-all duration-500 flex flex-col">
            <!-- Image Area -->
            <div class="relative h-24 sm:h-48 overflow-hidden flex-shrink-0">
                @if($package->image)
                    <img src="{{ filter_var($package->image, FILTER_VALIDATE_URL) ? $package->image : asset($package->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-green-500/10 to-emerald-500/5 flex items-center justify-center">
                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-green-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <!-- Badge -->
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-white/90 backdrop-blur rounded-full text-[7px] sm:text-[10px] font-black text-green-600 uppercase tracking-widest shadow-sm">
                        {{ $package->badge }}
                    </span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-3 sm:p-8 flex flex-col flex-grow">
                <h3 class="text-[11px] sm:text-xl font-black text-[var(--text-main)] leading-tight mb-1 sm:mb-2 line-clamp-2">{{ $package->title }}</h3>

                {{-- Show description & features only on larger screens --}}
                <p class="hidden sm:block text-[var(--text-muted)] text-xs leading-relaxed mb-6 line-clamp-2">{{ $package->description }}</p>
                <div class="hidden sm:block space-y-2 mb-8">
                    @foreach($package->features ?? [] as $feature)
                    <div class="flex items-center text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider">
                        <span class="w-4 h-4 bg-green-500/10 text-green-500 rounded-full flex items-center justify-center mr-2 text-[8px] shrink-0">✓</span>
                        {{ $feature }}
                    </div>
                    @endforeach
                </div>

                {{-- Mobile: compact feature count badge --}}
                <p class="sm:hidden text-[9px] text-[var(--text-muted)] mb-2">
                    {{ count($package->features ?? []) }} fitur tersedia
                </p>

                <!-- Actions -->
                <div class="flex items-center gap-1.5 sm:gap-3 pt-3 sm:pt-6 border-t border-[var(--border)] mt-auto">
                    <a href="{{ route('admin.special_packages.edit', $package) }}" class="flex-1 flex items-center justify-center py-2 sm:py-3 bg-[var(--bg-main)] text-[var(--text-muted)] font-bold rounded-lg sm:rounded-xl hover:bg-green-500 hover:text-white transition-all text-[9px] sm:text-xs gap-1 sm:gap-2">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.special_packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')" class="flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 sm:w-11 sm:h-11 flex items-center justify-center bg-[var(--bg-main)] text-rose-500 rounded-lg sm:rounded-xl hover:bg-rose-500 hover:text-white transition-all">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-[var(--bg-card)] rounded-[2.5rem] border border-dashed border-[var(--border)] shadow-[var(--card-shadow)] flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-[var(--bg-main)] rounded-3xl flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-[var(--text-muted)]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <p class="text-[var(--text-muted)] font-bold">Belum ada paket spesial</p>
            <a href="{{ route('admin.special_packages.create') }}" class="mt-4 text-green-500 font-black text-xs uppercase tracking-widest hover:underline">Tambah Sekarang</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
