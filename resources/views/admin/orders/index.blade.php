@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('header_title', 'Kelola Pesanan')

@section('content')
<div class="space-y-8 animate-fade-in p-4 sm:p-0">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-6 px-2">
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-black text-[var(--text-main)] tracking-tight">Daftar Pesanan</h2>
            <p class="text-[10px] sm:text-sm text-[var(--text-muted)] font-medium mt-0.5">Kelola status pesanan.</p>
        </div>
        <div class="flex items-center gap-2 text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-blue-500 bg-blue-500/10 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full border border-blue-500/20 w-fit">
            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
            App Android Sync
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-3xl font-bold text-sm flex items-center gap-3">
        <span>✅</span> {{ session('success') }}
    </div>
    @endif

    <div class="bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl sm:rounded-[3rem] overflow-hidden shadow-[var(--card-shadow)]">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border)] bg-[var(--bg-main)]/50">
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">ID / Waktu</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Pelanggan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Menu Pesanan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Total Harga</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($orders as $order)
                    <tr class="group hover:bg-[var(--bg-main)]/30 transition-colors">
                        <td class="px-8 py-6">
                            <p class="text-xs font-black text-[var(--text-main)] mb-1">#ORD-{{ $order->id }}</p>
                            <p class="text-[10px] text-[var(--text-muted)] font-medium">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-bold text-[var(--text-main)]">{{ $order->user_email }}</p>
                            <p class="text-[10px] text-[var(--text-muted)] mt-1">App Customer</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="max-w-xs">
                                <p class="text-sm font-bold text-[var(--text-main)] truncate" title="{{ $order->items_title }}">{{ $order->items_title }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-black text-[var(--text-main)]">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="flex justify-center">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" 
                                    class="text-[10px] font-black px-4 py-2 rounded-xl border border-[var(--border)] bg-[var(--bg-main)] text-[var(--text-main)] outline-none cursor-pointer focus:ring-2 ring-emerald-500/20 transition-all
                                    {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'text-emerald-500' :
                                       ($order->status == 'Dikirim' ? 'text-orange-500' :
                                       ($order->status == 'Diproses' ? 'text-blue-500' : 'text-yellow-500')) }}">
                                    <option value="Pending" {{ in_array($order->status, ['Pending','PENDING']) ? 'selected' : '' }}>⏰ Pending</option>
                                    <option value="Diproses" {{ in_array($order->status, ['Diproses','PROCESSING']) ? 'selected' : '' }}>⏳ Diproses</option>
                                    <option value="Dikirim" {{ in_array($order->status, ['Dikirim','SHIPPED']) ? 'selected' : '' }}>🚗 Dikirim</option>
                                    <option value="Selesai" {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="Dibatalkan" {{ in_array($order->status, ['Dibatalkan','CANCELLED']) ? 'selected' : '' }}>🚫 Batal</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus data pesanan ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 bg-rose-500/10 text-rose-500 border border-rose-500/20 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-[var(--bg-main)] rounded-full flex items-center justify-center text-4xl grayscale opacity-30">📦</div>
                                <p class="text-[var(--text-muted)] font-medium">Belum ada pesanan yang masuk dari aplikasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="md:hidden divide-y divide-[var(--border)]">
            @forelse($orders as $order)
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-black text-[var(--text-main)]">#ORD-{{ $order->id }}</p>
                        <p class="text-[9px] text-[var(--text-muted)] font-medium mt-0.5">{{ \Carbon\Carbon::parse($order->created_at)->format('d M, H:i') }}</p>
                        <p class="text-xs font-bold text-[var(--text-main)] mt-1 truncate">{{ $order->user_email }}</p>
                    </div>
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus?')" class="m-0 shrink-0 ml-4">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 bg-rose-500/10 text-rose-500 rounded-lg flex items-center justify-center border border-rose-500/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                <div>
                    <p class="text-[11px] text-[var(--text-muted)] line-clamp-1 italic">{{ $order->items_title }}</p>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <p class="text-[13px] font-black text-emerald-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="m-0">
                        @csrf @method('PUT')
                        <select name="status" onchange="this.form.submit()" 
                            class="text-[9px] font-black px-2.5 py-1.5 rounded-lg border border-[var(--border)] bg-[var(--bg-main)] text-[var(--text-main)] outline-none
                            {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'text-emerald-500' :
                                ($order->status == 'Dikirim' ? 'text-orange-500' :
                                ($order->status == 'Diproses' ? 'text-blue-500' : 'text-yellow-500')) }}">
                            <option value="Pending" {{ in_array($order->status, ['Pending','PENDING']) ? 'selected' : '' }}>Pending</option>
                            <option value="Diproses" {{ in_array($order->status, ['Diproses','PROCESSING']) ? 'selected' : '' }}>Proses</option>
                            <option value="Dikirim" {{ in_array($order->status, ['Dikirim','SHIPPED']) ? 'selected' : '' }}>Kirim</option>
                            <option value="Selesai" {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-10 text-center">
                <p class="text-[var(--text-muted)] text-xs font-medium">Belum ada pesanan.</p>
            </div>
            @endforelse
        </div>
        
        @if($orders->hasPages())
        <div class="px-8 py-6 bg-[var(--bg-main)]/30 border-t border-[var(--border)]">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
