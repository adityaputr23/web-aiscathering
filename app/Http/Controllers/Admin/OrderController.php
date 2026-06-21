<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Events\OrderStatusUpdated;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::orderBy('created_at', 'desc');

        // Filter by status
        $filter = $request->get('filter', 'all');
        if ($filter !== 'all') {
            $statusMap = [
                'pending'    => ['Pending', 'PENDING'],
                'diproses'   => ['Diproses', 'PROCESSING', 'Diterima', 'ACCEPTED'],
                'dikirim'    => ['Dikirim', 'SHIPPED'],
                'selesai'    => ['Selesai', 'COMPLETED'],
                'dibatalkan' => ['Dibatalkan', 'CANCELLED', 'Ditolak', 'REJECTED'],
            ];
            if (isset($statusMap[$filter])) {
                $query->whereIn('status', $statusMap[$filter]);
            }
        }

        $orders = $query->paginate(15)->appends(['filter' => $filter]);
        $menus = Menu::orderBy('name', 'asc')->get();
        $users = User::where('is_admin', 0)->orderBy('name', 'asc')->get();

        // Order statistics
        $orderStats = [
            'total'      => Order::count(),
            'pending'    => Order::whereIn('status', ['Pending', 'PENDING'])->count(),
            'diproses'   => Order::whereIn('status', ['Diproses', 'PROCESSING', 'Diterima', 'ACCEPTED'])->count(),
            'dikirim'    => Order::whereIn('status', ['Dikirim', 'SHIPPED'])->count(),
            'selesai'    => Order::whereIn('status', ['Selesai', 'COMPLETED'])->count(),
            'dibatalkan' => Order::whereIn('status', ['Dibatalkan', 'CANCELLED', 'Ditolak', 'REJECTED'])->count(),
            'revenue'    => Order::whereIn('status', ['Selesai', 'COMPLETED'])->sum('total_price'),
        ];

        return view('admin.orders.index', compact('orders', 'menus', 'users', 'orderStats', 'filter'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'cancel_reason' => 'nullable|string'
        ]);

        $order = Order::findOrFail($id);
        
        $updateData = [
            'status' => $request->status,
            'notified_user' => 0, // Reset to trigger notification in user's app
        ];

        if ($request->status === 'Dibatalkan' || $request->status === 'CANCELLED') {
            $updateData['cancel_reason'] = $request->cancel_reason;
        } else {
            $updateData['cancel_reason'] = null;
        }

        $order->update($updateData);

        // Send FCM push notification to consumer's device for real-time update
        $this->sendOrderStatusNotification($order);

        // Broadcast websocket event
        event(new OrderStatusUpdated($order));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui.',
                'order' => $order
            ]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    protected function sendOrderStatusNotification($order)
    {
        $token = $order->fcm_token_user;
        if (!$token && $order->user_id) {
            $user = User::find($order->user_id);
            if ($user) {
                $token = $user->fcm_token;
            }
        }
        if (!$token && $order->user_email) {
            $user = User::where('email', $order->user_email)->first();
            if ($user) {
                $token = $user->fcm_token;
            }
        }

        if ($token) {
            try {
                $title = "Update Pesanan #" . ($order->order_id ?? $order->id);
                $body = "Status pesanan Anda telah diperbarui menjadi: " . $order->status;
                if ($order->status === 'Dibatalkan' || $order->status === 'CANCELLED') {
                    if ($order->cancel_reason) {
                        $body .= " (" . $order->cancel_reason . ")";
                    }
                }

                $fcm = new \App\Services\FcmService();
                $fcm->sendPush($token, $title, $body, [
                    'type' => 'order_status_update',
                    'order_id' => (string)($order->order_id ?? $order->id),
                    'status' => $order->status
                ]);
                \Log::info("FCM order status notification sent to user " . $order->user_email);
            } catch (\Exception $e) {
                \Log::error("Failed to send FCM order status update: " . $e->getMessage());
            }
        }
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
