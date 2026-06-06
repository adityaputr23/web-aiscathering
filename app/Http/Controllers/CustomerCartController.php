<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerCartController extends Controller
{
    public function index()
    {
        $cart = $this->getCartItems();
        $cartSummary = $this->buildSummary($cart);

        return view('cart.index', [
            'cartItems' => $cart,
            'summary' => $cartSummary,
        ]);
    }

    public function add(Request $request, Menu $menu)
    {
        if (!$menu->is_available) {
            return back()->with('error', 'Menu ini sedang tidak tersedia.');
        }

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = $this->getCartItems();

        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['quantity'] += $quantity;
        } else {
            $cart[$menu->id] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => (int) $menu->price,
                'image_url' => $menu->image_url,
                'category' => $menu->category,
                'quantity' => $quantity,
            ];
        }

        session()->put('customer_cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil masuk ke keranjang.',
                'cart_count' => $this->getCartCount(),
            ]);
        }

        if ($request->boolean('return_cart')) {
            return redirect()->route('cart.index')->with('success', 'Menu berhasil masuk ke keranjang.');
        }

        return back()->with('success', 'Menu berhasil masuk ke keranjang.');
    }

    public function update(Request $request, string $menuId)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $cart = $this->getCartItems();

        if (!isset($cart[$menuId])) {
            return back()->with('error', 'Item keranjang tidak ditemukan.');
        }

        $cart[$menuId]['quantity'] = (int) $validated['quantity'];
        session()->put('customer_cart', $cart);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(string $menuId)
    {
        $cart = $this->getCartItems();

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session()->put('customer_cart', $cart);
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('customer_cart');

        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan.');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk checkout.');
        }

        $cart = $this->getCartItems();

        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'max:2000'],
            'payment_method' => ['required', 'string', 'max:100'],
        ]);

        $summary = $this->buildSummary($cart);
        $items = array_values($cart);

        $order = Order::create([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'items_title' => collect($items)->pluck('name')->implode(', '),
            'items_json' => json_encode($items),
            'total_price' => $summary['total_price'],
            'status' => 'Pending',
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'UNPAID',
            'fcm_token_user' => $user->fcm_token ?? null,
        ]);

        session()->forget('customer_cart');

        return redirect()->route('cart.index')->with('success', 'Pesanan berhasil dibuat. Menunggu proses pembayaran.');
    }

    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount(),
        ]);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    private function getCartItems(): array
    {
        return session('customer_cart', []);
    }

    private function getCartCount(): int
    {
        return collect($this->getCartItems())->sum('quantity');
    }

    private function buildSummary(array $cart): array
    {
        $subtotal = collect($cart)->sum(function (array $item) {
            return ((int) $item['price']) * ((int) $item['quantity']);
        });

        $deliveryFee = $subtotal > 0 ? 15000 : 0;
        $serviceFee = $subtotal > 0 ? (int) round($subtotal * 0.02) : 0;

        return [
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'service_fee' => $serviceFee,
            'total_price' => $subtotal + $deliveryFee + $serviceFee,
            'item_count' => $this->getCartCount(),
        ];
    }
}
