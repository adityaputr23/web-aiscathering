<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return back()->with('success', 'Pesanan berhasil dihapus.');
    }

    public function getLatestOrders()
    {
        $latest_orders = Order::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total_menu' => Menu::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('is_admin', 0)->count(),
        ];

        return response()->json([
            'orders' => $latest_orders,
            'stats' => $stats
        ]);
    }
}
