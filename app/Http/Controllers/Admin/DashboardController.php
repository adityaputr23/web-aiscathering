<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\LandingPageContent;
use App\Models\OperationalHour;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stats dari database yang sama dengan Android App ──
        $total_menus      = Menu::count();
        $total_categories = Menu::distinct()->count('category');
        $total_content    = LandingPageContent::count();
        $total_hours      = OperationalHour::count();

        // Data dari tabel orders (Android App)
        $total_orders  = DB::table('orders')->count();
        $active_orders = DB::table('orders')
            ->whereNotIn('status', ['Selesai', 'Dibatalkan', 'COMPLETED', 'CANCELLED', 'Ditolak', 'REJECTED'])
            ->count();
        $selesai_orders = DB::table('orders')
            ->whereIn('status', ['Selesai', 'COMPLETED'])
            ->count();

        // Revenue dari pesanan selesai
        $total_revenue = DB::table('orders')
            ->whereIn('status', ['Selesai', 'COMPLETED'])
            ->sum('total_price');

        // Total pelanggan unik (dari tabel users Android app)
        $total_customers = DB::table('users')
            ->where('is_admin', 0)
            ->count();

        // Chat belum dibaca (dari tabel chats Android app)
        $unread_chats = DB::table('chats')
            ->where('receiver_email', 'aishcatering2@gmail.com')
            ->where('is_read', 0)
            ->count();

        // Top menu berdasarkan kolom `sold`
        $top_menus = Menu::orderBy('sold', 'desc')->take(5)->get();

        // Menu terbaru
        $latest_menus = Menu::latest()->take(5)->get();

        // Pesanan terbaru (dari Android App)
        $latest_orders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Status pesanan breakdown
        $order_stats = [
            'diproses' => DB::table('orders')->whereIn('status', ['Diproses', 'PROCESSING', 'Diterima', 'ACCEPTED'])->count(),
            'dikirim'  => DB::table('orders')->whereIn('status', ['Dikirim', 'SHIPPED'])->count(),
            'selesai'  => DB::table('orders')->whereIn('status', ['Selesai', 'COMPLETED'])->count(),
            'pending'  => DB::table('orders')->whereIn('status', ['Pending', 'PENDING'])->count(),
        ];

        $stats = [
            'total_menu'       => $total_menus,
            'total_categories' => $total_categories,
            'total_content'    => $total_content,
            'total_hours'      => $total_hours,
            'total_orders'     => $total_orders,
            'active_orders'    => $active_orders,
            'selesai_orders'   => $selesai_orders,
            'total_revenue'    => $total_revenue,
            'total_customers'  => $total_customers,
            'unread_chats'     => $unread_chats,
        ];

        return view('admin.dashboard', compact(
            'stats', 'latest_menus', 'top_menus', 'latest_orders', 'order_stats'
        ));
    }
}
