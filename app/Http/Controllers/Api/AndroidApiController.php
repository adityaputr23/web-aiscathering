<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Message;
use App\Models\OperationalHour;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AndroidApiController extends Controller
{
    // POST register.php
    public function register(Request $request)
    {
        $name = trim($request->input('name', ''));
        $email = trim($request->input('email', ''));
        $phone = trim($request->input('phone', ''));
        $address = trim($request->input('address', ''));
        $password = trim($request->input('password', ''));

        if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password)) {
            return response()->json(["status" => "error", "message" => "Data tidak lengkap"]);
        }

        $existing = User::where('email', $email)->first();
        if ($existing) {
            return response()->json(["status" => "error", "message" => "Email sudah terdaftar"]);
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'password' => Hash::make($password),
            'role' => 'user',
            'is_admin' => 0,
            'is_member' => 0
        ]);

        return response()->json(["status" => "success", "message" => "Pendaftaran Berhasil"]);
    }

    // POST login.php
    public function login(Request $request)
    {
        $email = trim($request->input('email', ''));
        $password = trim($request->input('password', ''));

        if (empty($email) || empty($password)) {
            return response()->json(["status" => "error", "message" => "Email dan Password tidak boleh kosong"]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(["status" => "error", "message" => "Email belum terdaftar"]);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(["status" => "error", "message" => "Password salah"]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Login Berhasil",
            "user" => [
                "id" => (int) $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "address" => $user->address,
                "photo" => $user->photo ?? $user->profile_photo,
                "is_admin" => (bool) $user->is_admin,
                "is_member" => (bool) $user->is_member
            ]
        ]);
    }

    // POST google_auth.php
    public function googleAuth(Request $request)
    {
        $name = $request->input('name', '');
        $email = $request->input('email', '');

        if (empty($email)) {
            return response()->json(["status" => "error", "message" => "Email Google tidak didapatkan"]);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json([
                "status" => "success",
                "message" => "Login Google Berhasil",
                "user" => [
                    "id" => (int) $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "phone" => $user->phone,
                    "address" => $user->address,
                    "photo" => $user->photo ?? $user->profile_photo,
                    "is_admin" => (bool) $user->is_admin,
                    "is_member" => (bool) $user->is_member
                ]
            ]);
        } else {
            $phone = "000000000000";
            $address = "Alamat dari Google (Harap diupdate di Profil)";
            $dummyPass = Hash::make(bin2hex(random_bytes(10)));

            $newUser = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'password' => $dummyPass,
                'role' => 'user',
                'is_admin' => 0,
                'is_member' => 0
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Register Google Berhasil",
                "user" => [
                    "id" => (int) $newUser->id,
                    "name" => $newUser->name,
                    "email" => $newUser->email,
                    "phone" => $newUser->phone,
                    "address" => $newUser->address,
                    "photo" => null,
                    "is_admin" => false,
                    "is_member" => false
                ]
            ]);
        }
    }

    // POST update_profile.php
    public function updateProfile(Request $request)
    {
        $email = $request->input('email', '');
        $name = $request->input('name', '');
        $phone = $request->input('phone', '');
        $address = $request->input('address', '');
        $newEmail = $request->input('new_email', $email);

        if (empty($email) || empty($name)) {
            return response()->json(["status" => "error", "message" => "Data tidak lengkap"]);
        }

        if ($newEmail !== $email) {
            $check = User::where('email', $newEmail)->where('email', '!=', $email)->first();
            if ($check) {
                return response()->json(["status" => "error", "message" => "Email sudah digunakan akun lain"]);
            }
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(["status" => "error", "message" => "Email tidak ditemukan di database"]);
        }

        $user->update([
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'email' => $newEmail
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Profil berhasil diperbarui",
            "user" => [
                "id" => (int) $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "address" => $user->address,
                "photo" => $user->photo ?? $user->profile_photo,
                "is_admin" => (bool) $user->is_admin,
                "is_member" => (bool) $user->is_member
            ]
        ]);
    }

    // POST update_profile_with_image.php
    public function updateProfileWithImage(Request $request)
    {
        $email = $request->input('email', '');
        $name = $request->input('name', '');
        $phone = $request->input('phone', '');
        $address = $request->input('address', '');
        $newEmail = $request->input('new_email', $email);

        if (empty($email) || empty($name)) {
            return response()->json(["status" => "error", "message" => "Data tidak lengkap"]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(["status" => "error", "message" => "Email tidak ditemukan di database"]);
        }

        $photoName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $photoName = time() . '_' . uniqid() . '.' . $fileExt;
            
            // Simpan ke folder uploads/profile/ di Laravel public/
            $file->move(public_path('uploads/profile'), $photoName);

            // Bersihkan foto lama
            $oldPhoto = $user->photo ?? $user->profile_photo;
            if ($oldPhoto && file_exists(public_path('uploads/profile/' . $oldPhoto))) {
                @unlink(public_path('uploads/profile/' . $oldPhoto));
            }
        }

        $updateData = [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'email' => $newEmail
        ];

        if ($photoName) {
            $updateData['photo'] = $photoName;
            $updateData['profile_photo'] = $photoName; // Sync columns
        }

        $user->update($updateData);
        $user->refresh();

        return response()->json([
            "status" => "success",
            "message" => "Profil berhasil diperbarui",
            "user" => [
                "id" => (int) $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "address" => $user->address,
                "photo" => $user->photo ?? $user->profile_photo,
                "is_admin" => (bool) $user->is_admin,
                "is_member" => (bool) $user->is_member
            ]
        ]);
    }

    // POST delete_profile_image.php
    public function deleteProfileImage(Request $request)
    {
        $email = $request->input('email', '');

        if (empty($email)) {
            return response()->json(["status" => "error", "message" => "Email tidak boleh kosong"]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(["status" => "error", "message" => "User tidak ditemukan"]);
        }

        $photo = $user->photo ?? $user->profile_photo;
        if ($photo && file_exists(public_path('uploads/profile/' . $photo))) {
            @unlink(public_path('uploads/profile/' . $photo));
        }

        $user->update([
            'photo' => null,
            'profile_photo' => null
        ]);

        return response()->json(["status" => "success", "message" => "Foto profil berhasil dihapus"]);
    }

    // POST get_notifications.php
    public function getNotifications(Request $request)
    {
        $email = $request->input('email', '');

        if (empty($email)) {
            return response()->json(["status" => "error", "message" => "Email required"]);
        }

        $user = User::where('email', $email)->first();
        $isAdmin = $user ? (bool)$user->is_admin : false;

        $response = [
            "status" => "success",
            "newOrdersCount" => 0,
            "orderStatusUpdate" => null,
            "storeStatusChanged" => null
        ];

        if ($isAdmin) {
            $newOrdersCount = DB::table('orders')->where('status', 'PENDING')->where('notified_admin', 0)->count();
            $response["newOrdersCount"] = $newOrdersCount;
            
            DB::table('orders')->where('status', 'PENDING')->update(['notified_admin' => 1]);
        } else {
            $latestOrder = DB::table('orders')
                ->where('user_email', $email)
                ->where('notified_user', 0)
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($latestOrder) {
                $response["orderStatusUpdate"] = [
                    "orderId" => "#" . $latestOrder->id,
                    "status" => $latestOrder->status
                ];

                DB::table('orders')->where('id', $latestOrder->id)->update(['notified_user' => 1]);
            }
        }

        // Cek status toko (buka/tutup) berdasarkan jam operasional real
        $now = Carbon::now('Asia/Jakarta');
        $todaySchedule = OperationalHour::where('day_index', $now->dayOfWeek)->first();
        $isOpen = false;
        if ($todaySchedule && !$todaySchedule->is_closed) {
            $currentTime = $now->format('H:i');
            $open = Carbon::parse($todaySchedule->open_time)->format('H:i');
            $close = Carbon::parse($todaySchedule->close_time)->format('H:i');
            $isOpen = $currentTime >= $open && $currentTime < $close;
        }
        $response["storeStatusChanged"] = $isOpen ? "OPEN" : "CLOSE";

        // Cek & Auto-Generate Promo & Diskon Harian
        $todayDate = date("Y-m-d");
        $promo = DB::table('promos')
            ->whereDate('created_at', $todayDate)
            ->where('is_active', 1)
            ->first();

        if (!$promo) {
            $promosTemplates = [
                [
                    "title" => "Promo Hari Ini: Diskon Spesial 20%! 🍱",
                    "message" => "Dapatkan potongan 20% khusus paket Katering Keluarga hari ini dengan kode voucher AISHDISH20.",
                    "code" => "AISHDISH20",
                    "percent" => 20,
                    "val" => 20000,
                    "min" => 100000
                ],
                [
                    "title" => "Jumat Berkah: Diskon Rp 20.000! 🌟",
                    "message" => "Rayakan hari Jumat dengan potongan langsung Rp 20.000 untuk pesanan minimal Rp 100.000 dengan kode JUMATBERKAH.",
                    "code" => "JUMATBERKAH",
                    "percent" => 15,
                    "val" => 20000,
                    "min" => 80000
                ],
                [
                    "title" => "Promo Gajian: Gratis Ongkir Singkawang! 🚚",
                    "message" => "Catering lezat tanpa ongkir se-Singkawang hari ini. Gunakan voucher AISHPAYDAY saat checkout.",
                    "code" => "AISHPAYDAY",
                    "percent" => 10,
                    "val" => 15000,
                    "min" => 120000
                ],
                [
                    "title" => "Happy Hour: Potongan 15% Makan Siang! 🍛",
                    "message" => "Pesan katering makan siang sehat Anda sekarang dan hemat 15% menggunakan kode AISHLUNCH15.",
                    "code" => "AISHLUNCH15",
                    "percent" => 15,
                    "val" => 15000,
                    "min" => 60000
                ],
                [
                    "title" => "Catering Sehat Hemat 10%! 🥦",
                    "message" => "Mulai hidup sehat bersama Aish Catering. Nikmati diskon 10% untuk semua menu diet hari ini dengan kode AISHFIT10.",
                    "code" => "AISHFIT10",
                    "percent" => 10,
                    "val" => 10000,
                    "min" => 50000
                ]
            ];

            $dayIndex = (int)date("d") % count($promosTemplates);
            $selectedTemplate = $promosTemplates[$dayIndex];

            $promoId = DB::table('promos')->insertGetId([
                'title' => $selectedTemplate["title"],
                'message' => $selectedTemplate["message"],
                'discount_percent' => $selectedTemplate["percent"],
                'discount_value' => $selectedTemplate["val"],
                'min_spend' => $selectedTemplate["min"],
                'voucher_code' => $selectedTemplate["code"],
                'is_active' => 1,
                'created_at' => Carbon::now('Asia/Jakarta')
            ]);

            $promo = (object)[
                "id" => $promoId,
                "title" => $selectedTemplate["title"],
                "message" => $selectedTemplate["message"],
                "voucher_code" => $selectedTemplate["code"]
            ];
        }

        $response["latestPromo"] = [
            "id" => (int)$promo->id,
            "title" => $promo->title,
            "message" => $promo->message,
            "voucherCode" => $promo->voucher_code
        ];

        return response()->json($response);
    }

    // POST send_message.php
    public function sendMessage(Request $request)
    {
        $sender = $request->input('sender_email', '');
        $receiver = $request->input('receiver_email', 'admin@aishcatering.com');
        $messageText = $request->input('message', '');
        $type = $request->input('sender_type', 'USER');

        if (empty($sender) || empty($messageText)) {
            return response()->json(["status" => "error", "message" => "Invalid data"]);
        }

        $msg = Message::create([
            'sender_email' => $sender,
            'receiver_email' => $receiver,
            'message' => $messageText,
            'sender_type' => $type,
            'is_read' => 0
        ]);

        event(new \App\Events\ChatMessageSent($msg));

        return response()->json(["status" => "success", "message" => "Message sent"]);
    }

    // POST get_chat_history.php
    public function getChatHistory(Request $request)
    {
        $email = $request->input('user_email', '');

        if (empty($email)) {
            return response()->json(["status" => "error", "message" => "User email required"]);
        }

        // Tandai sudah dibaca
        Message::where('sender_email', $email)->where('receiver_email', 'admin@aishcatering.com')->where('is_read', 0)->update(['is_read' => 1]);
        Message::where('sender_email', 'admin@aishcatering.com')->where('receiver_email', $email)->where('is_read', 0)->update(['is_read' => 1]);

        $chats = Message::where(function($q) use ($email) {
            $q->where('sender_email', $email)->where('receiver_email', 'admin@aishcatering.com');
        })->orWhere(function($q) use ($email) {
            $q->where('sender_email', 'admin@aishcatering.com')->where('receiver_email', $email);
        })->orderBy('created_at', 'asc')->get();

        $formatted = $chats->map(function($chat) {
            return [
                "id" => (int)$chat->id,
                "sender_email" => $chat->sender_email,
                "receiver_email" => $chat->receiver_email,
                "message" => $chat->message,
                "sender_type" => $chat->sender_type,
                "created_at" => (string)$chat->created_at
            ];
        });

        return response()->json(["status" => "success", "messages" => $formatted]);
    }

    // POST get_chat_list.php
    public function getChatList()
    {
        // Ambil daftar user unik
        $chatListRaw = DB::select("
            SELECT 
                c.sender_email AS email,
                COALESCE(u.name, IF(c.sender_email LIKE 'guest_%', REPLACE(c.sender_email, 'guest_', ''), 'User Baru')) AS name,
                MAX(c.created_at) AS last_chat,
                (SELECT COUNT(*) FROM chats WHERE sender_email = c.sender_email AND receiver_email = 'admin@aishcatering.com' AND is_read = 0) AS unread_count
            FROM chats c
            LEFT JOIN users u ON c.sender_email = u.email
            WHERE c.sender_type = 'USER'
            GROUP BY c.sender_email
            ORDER BY last_chat DESC
        ");

        $chat_list = [];
        foreach ($chatListRaw as $row) {
            $chat_list[] = [
                "name" => $row->name,
                "email" => $row->email,
                "last_chat" => $row->last_chat ?? date('Y-m-d H:i:s'),
                "unread_count" => (int)$row->unread_count
            ];
        }

        return response()->json([
            "status" => "success",
            "chat_list" => $chat_list
        ]);
    }

    // POST get_active_vouchers.php
    public function getActiveVouchers()
    {
        $promos = DB::table('promos')->where('is_active', 1)->orderBy('created_at', 'desc')->get();
        $vouchers = [];

        foreach ($promos as $row) {
            $vouchers[] = [
                "id" => (int)$row->id,
                "title" => $row->title,
                "message" => $row->message,
                "discountPercent" => (int)$row->discount_percent,
                "discountValue" => (int)$row->discount_value,
                "minSpend" => (int)$row->min_spend,
                "voucherCode" => $row->voucher_code
            ];
        }

        return response()->json([
            "status" => "success",
            "vouchers" => $vouchers
        ]);
    }

    // POST get_user_stats.php
    public function getUserStats(Request $request)
    {
        $email = $request->input('email', '');

        if (empty($email)) {
            return response()->json(["status" => "error", "message" => "Email required"]);
        }

        $res = DB::table('orders')
            ->selectRaw("COUNT(*) as total_orders, IFNULL(SUM(total_price), 0) as total_spend")
            ->where('user_email', $email)
            ->first();

        $activeVouchersCount = DB::table('promos')->where('is_active', 1)->count();

        return response()->json([
            "status" => "success",
            "totalOrders" => (int)$res->total_orders,
            "totalSpend" => (float)$res->total_spend,
            "activeVouchersCount" => (int)$activeVouchersCount
        ]);
    }

    // GET get_reviews.php
    public function getReviews(Request $request)
    {
        $menuId = (int)$request->query('menu_id', 0);
        $menuName = $request->query('menu_name', '');

        if ($menuId <= 0 && !empty($menuName)) {
            $menu = Menu::where('name', $menuName)->first();
            if ($menu) {
                $menuId = (int)$menu->id;
            }
        }

        if ($menuId > 0) {
            $query = DB::table('reviews')->where('menu_id', $menuId);
        } else {
            $query = DB::table('reviews')->where('menu_name', $menuName);
        }

        $reviewsRaw = $query->orderBy('created_at', 'desc')->get();
        $reviews = [];

        foreach ($reviewsRaw as $row) {
            $reviews[] = [
                "id" => (int)$row->id,
                "user_name" => $row->user_name,
                "user_email" => $row->user_email,
                "rating" => (int)$row->rating,
                "review_text" => $row->review_text,
                "created_at" => (string)$row->created_at
            ];
        }

        return response()->json([
            "status" => "success",
            "reviews" => $reviews
        ]);
    }

    // POST add_review.php
    public function addReview(Request $request)
    {
        $menuId = (int)$request->input('menu_id', 0);
        $menuName = $request->input('menu_name', '');
        $userName = $request->input('user_name', '');
        $userEmail = $request->input('user_email', '');
        $rating = (int)$request->input('rating', 5);
        $reviewText = $request->input('review_text', '');

        if (empty($menuName) || empty($userName) || empty($userEmail)) {
            return response()->json(["status" => "error", "message" => "Parameter tidak lengkap"]);
        }

        if ($menuId <= 0 && !empty($menuName)) {
            $menu = Menu::where('name', $menuName)->first();
            if ($menu) {
                $menuId = (int)$menu->id;
            }
        }

        $inserted = DB::table('reviews')->insert([
            'menu_id' => $menuId,
            'menu_name' => $menuName,
            'user_name' => $userName,
            'user_email' => $userEmail,
            'rating' => $rating,
            'review_text' => $reviewText,
            'created_at' => Carbon::now('Asia/Jakarta')
        ]);

        if ($inserted) {
            if ($menuId > 0) {
                $stats = DB::table('reviews')
                    ->selectRaw("AVG(rating) as avg_rating, COUNT(*) as cnt")
                    ->where('menu_id', $menuId)
                    ->first();

                $avgRating = round((float)($stats->avg_rating ?? 5.0), 1);
                $cnt = (int)($stats->cnt ?? 0);

                Menu::where('id', $menuId)->update([
                    'rating' => $avgRating,
                    'review_count' => $cnt
                ]);
            }

            return response()->json(["status" => "success", "message" => "Ulasan berhasil dikirim"]);
        }

        return response()->json(["status" => "error", "message" => "Gagal menyimpan ulasan"]);
    }

    // POST join_membership.php
    public function joinMembership(Request $request)
    {
        $email = $request->input('email', '');

        if (empty($email)) {
            return response()->json(["status" => "error", "message" => "Email required"]);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update(['is_member' => 1]);
            return response()->json(["status" => "success", "message" => "Joined membership successfully"]);
        }

        return response()->json(["status" => "error", "message" => "Failed to update membership"]);
    }

    // POST delete_message.php
    public function deleteMessage(Request $request)
    {
        $id = (int)$request->input('message_id', 0);
        $email = $request->input('sender_email', '');

        if ($id <= 0 || empty($email)) {
            return response()->json(["status" => "error", "message" => "Parameter invalid"]);
        }

        $deleted = Message::where('id', $id)->where('sender_email', $email)->delete();

        if ($deleted) {
            event(new \App\Events\ChatMessageDeleted($id, $email));
            return response()->json(["status" => "success", "message" => "Message deleted"]);
        }

        return response()->json(["status" => "error", "message" => "Failed to delete message"]);
    }

    // POST edit_message.php
    public function editMessage(Request $request)
    {
        $id = (int)$request->input('message_id', 0);
        $email = $request->input('sender_email', '');
        $newMessage = $request->input('new_message', '');

        if ($id <= 0 || empty($email) || empty($newMessage)) {
            return response()->json(["status" => "error", "message" => "Parameter invalid"]);
        }

        $updated = Message::where('id', $id)->where('sender_email', $email)->update([
            'message' => $newMessage
        ]);

        if ($updated) {
            $msg = Message::find($id);
            if ($msg) {
                event(new \App\Events\ChatMessageUpdated($msg));
            }
            return response()->json(["status" => "success", "message" => "Message updated"]);
        }

        return response()->json(["status" => "error", "message" => "Failed to update message"]);
    }

    // POST get_menus.php
    public function getMenus()
    {
        $menus = Menu::orderBy('id', 'desc')->get()->map(function($menu) {
            return [
                "id" => (int)$menu->id,
                "name" => $menu->name,
                "price" => (int)$menu->price,
                "category" => $menu->category,
                "emoji" => $menu->emoji,
                "rating" => (float)$menu->rating,
                "sold" => (int)$menu->sold,
                "description" => $menu->description,
                "image_url" => $menu->image_url,
                "review_count" => (int)($menu->review_count ?? 0),
                "is_available" => (int)($menu->is_available ? 1 : 0),
                "is_featured" => (int)($menu->is_featured ? 1 : 0),
                "created_at" => (string)$menu->created_at
            ];
        });

        return response()->json([
            "status" => "success",
            "menus" => $menus
        ]);
    }

    // POST save_menu.php
    public function saveMenu(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name', '');
        $price = (int)$request->input('price', 0);
        $category = $request->input('category', '');
        $emoji = $request->input('emoji', '');
        $description = $request->input('description', '');
        $isAvailable = (int)$request->input('is_available', 1);

        if (empty($name) || empty($price)) {
            return response()->json(["status" => "error", "message" => "Nama dan harga wajib diisi"]);
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = "menu_" . time() . "_" . uniqid() . "." . $fileExtension;
            $file->move(public_path('uploads/menus'), $fileName);
            $imageUrl = "uploads/menus/" . $fileName;
        }

        if ($id && $id != -1) {
            // Update
            $menu = Menu::find($id);
            if (!$menu) {
                return response()->json(["status" => "error", "message" => "Menu tidak ditemukan"]);
            }

            $updateData = [
                'name' => $name,
                'price' => $price,
                'category' => $category,
                'emoji' => $emoji,
                'description' => $description,
                'is_available' => $isAvailable == 1
            ];

            if ($imageUrl) {
                // Delete old image
                if ($menu->image_url && file_exists(public_path($menu->image_url))) {
                    @unlink(public_path($menu->image_url));
                }
                $updateData['image_url'] = $imageUrl;
            }

            $menu->update($updateData);
        } else {
            // Insert
            $menu = Menu::create([
                'name' => $name,
                'price' => $price,
                'category' => $category,
                'emoji' => $emoji,
                'description' => $description,
                'is_available' => $isAvailable == 1,
                'image_url' => $imageUrl
            ]);
        }

        // Auto-generate promo if description has promo-related words
        $hasPromoWord = (stripos($description, 'promo') !== false || stripos($description, 'diskon') !== false || stripos($description, 'potongan') !== false || stripos($description, '%') !== false);
        if ($hasPromoWord) {
            DB::table('promos')->insert([
                'title' => "Diskon Spesial Menu " . $name . "! 🍱",
                'message' => "Menu favorit baru Aish Catering: " . $name . " diskon sekarang! " . $description,
                'discount_percent' => 10,
                'discount_value' => 10000,
                'min_spend' => 50000,
                'voucher_code' => "AISHPromo" . rand(10, 99),
                'is_active' => 1,
                'created_at' => Carbon::now('Asia/Jakarta')
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Menu berhasil disimpan",
            "image_url" => $imageUrl ?? ($menu->image_url ?? null)
        ]);
    }

    // POST delete_menu.php
    public function deleteMenu(Request $request)
    {
        $id = (int)$request->input('id', 0);

        if ($id <= 0) {
            return response()->json(["status" => "error", "message" => "ID tidak ditemukan"]);
        }

        $menu = Menu::find($id);
        if ($menu) {
            if ($menu->image_url && file_exists(public_path($menu->image_url))) {
                @unlink(public_path($menu->image_url));
            }
            $menu->delete();
            return response()->json(["status" => "success", "message" => "Menu berhasil dihapus"]);
        }

        return response()->json(["status" => "error", "message" => "Gagal menghapus menu"]);
    }

    // POST save_schedule.php
    public function saveSchedule(Request $request)
    {
        $dayName = $request->input('day_name');
        $openTime = $request->input('open_time');
        $closeTime = $request->input('close_time');
        $isOpen = $request->input('is_open');

        if ($dayName === null || $openTime === null || $closeTime === null || $isOpen === null) {
            return response()->json(["status" => "error", "message" => "Parameter tidak lengkap"]);
        }

        $isClosed = ((int)$isOpen === 1) ? 0 : 1;

        $updated = OperationalHour::where('day_name', $dayName)->update([
            'open_time' => $openTime,
            'close_time' => $closeTime,
            'is_closed' => $isClosed
        ]);

        if ($updated) {
            return response()->json(["status" => "success", "message" => "Jadwal berhasil diperbarui"]);
        }

        return response()->json(["status" => "error", "message" => "Gagal memperbarui jadwal di database"]);
    }

    // GET get_schedules.php
    public function getSchedules()
    {
        $schedules = OperationalHour::orderBy('day_index')->get()->map(function($hour) {
            return [
                "id" => (int)$hour->id,
                "day_name" => $hour->day_name,
                "open_time" => (string)$hour->open_time,
                "close_time" => (string)$hour->close_time,
                "is_open" => $hour->is_closed ? 0 : 1
            ];
        });

        return response()->json([
            "status" => "success",
            "schedules" => $schedules
        ]);
    }

    // GET get_users.php
    public function getUsers()
    {
        $users = User::orderBy('is_admin', 'desc')->orderBy('name', 'asc')->get()->map(function($user) {
            return [
                "id" => (int)$user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "address" => $user->address,
                "photo" => $user->photo ?? $user->profile_photo,
                "is_admin" => (bool)$user->is_admin,
                "is_member" => (bool)$user->is_member
            ];
        });

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }

    // POST save_user.php
    public function saveUser(Request $request)
    {
        $id = (int)$request->input('id', 0);
        $name = $request->input('name', '');
        $email = $request->input('email', '');
        $phone = $request->input('phone', '');
        $address = $request->input('address', '');
        $pass = $request->input('password', '');
        $isAdmin = (int)$request->input('is_admin', 0);
        $isMember = (int)$request->input('is_member', 0);

        if (empty($name) || empty($email) || empty($phone) || empty($address)) {
            return response()->json(["status" => "error", "message" => "Nama, Email, HP, dan Alamat tidak boleh kosong"]);
        }

        if ($id > 0) {
            // Edit Mode
            $check = User::where('email', $email)->where('id', '!=', $id)->first();
            if ($check) {
                return response()->json(["status" => "error", "message" => "Email sudah digunakan oleh user lain"]);
            }

            $user = User::find($id);
            if (!$user) {
                return response()->json(["status" => "error", "message" => "User tidak ditemukan"]);
            }

            $updateData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'is_admin' => $isAdmin,
                'is_member' => $isMember
            ];

            if (!empty($pass)) {
                $updateData['password'] = Hash::make($pass);
            }

            $user->update($updateData);
            return response()->json(["status" => "success", "message" => "Data user berhasil diperbarui"]);
        } else {
            // Create Mode
            if (empty($pass)) {
                return response()->json(["status" => "error", "message" => "Password wajib diisi untuk user baru"]);
            }

            $check = User::where('email', $email)->first();
            if ($check) {
                return response()->json(["status" => "error", "message" => "Email sudah terdaftar"]);
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'password' => Hash::make($pass),
                'role' => $isAdmin ? 'admin' : 'user',
                'is_admin' => $isAdmin,
                'is_member' => $isMember
            ]);

            return response()->json(["status" => "success", "message" => "User baru berhasil ditambahkan"]);
        }
    }

    // POST delete_user.php
    public function deleteUser(Request $request)
    {
        $id = (int)$request->input('id', 0);

        if ($id <= 0) {
            return response()->json(["status" => "error", "message" => "ID tidak ditemukan"]);
        }

        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(["status" => "success", "message" => "User berhasil dihapus"]);
        }

        return response()->json(["status" => "error", "message" => "Gagal menghapus user"]);
    }

    public function getWebSocketConfig()
    {
        return response()->json([
            'status' => 'success',
            'config' => [
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => (int)env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'app_key' => env('REVERB_APP_KEY', 'aishcateringwebsocketkey'),
                'app_id' => env('REVERB_APP_ID', '123456')
            ]
        ]);
    }

    // POST place_order.php
    public function placeOrder(Request $request)
    {
        // The Android app sends a highly redundant set of params.
        // We pick the best available value for each field.
        $orderId      = $request->input('order_id', $request->input('id', $request->input('id_pesanan', '')));
        $userId       = (int) $request->input('user_id', $request->input('id_user', $request->input('customer_id', 0)));
        $customerName = $request->input('customer_name', $request->input('name', $request->input('nama_pelanggan', '')));
        $email        = $request->input('customer_email', $request->input('email', $request->input('user_email', '')));
        $phone        = $request->input('customer_phone', $request->input('phone', $request->input('nomor_hp', '')));
        $itemsJson    = $request->input('items_json', $request->input('items', $request->input('cart', $request->input('data_pesanan', '[]'))));
        $itemsTitle   = $request->input('items_title', 'Pesanan');
        $itemsSubtitle = $request->input('items_subtitle', '');
        $totalPrice   = (int) $request->input('total_price', $request->input('total', $request->input('grand_total', 0)));
        $subtotal     = (int) $request->input('subtotal', $request->input('harga_subtotal', $totalPrice));
        $shippingCost = (int) $request->input('shipping_cost', $request->input('shipping_fee', $request->input('ongkir', 0)));
        $discount     = (int) $request->input('discount', $request->input('total_discount', $request->input('potongan', 0)));
        $memberDiscount = (int) $request->input('member_discount', $request->input('diskon_member', 0));
        $address      = $request->input('address', $request->input('alamat_pengiriman', ''));
        $status       = $request->input('status', $request->input('order_status', 'Diproses'));
        $deliveryDate = $request->input('delivery_date', $request->input('tanggal_pengiriman', ''));
        $emoji        = $request->input('emoji', '🍱');
        $paymentMethod = $request->input('payment_method', $request->input('metode_pembayaran', 'COD'));

        // Validate essential fields
        if (empty($orderId) || empty($email) || $totalPrice <= 0) {
            Log::warning('placeOrder: Parameter tidak lengkap', $request->all());
            return response()->json(["status" => "error", "message" => "Parameter tidak lengkap"]);
        }

        // Prevent duplicate orders
        $existing = DB::table('orders')->where('order_id', $orderId)->first();
        if ($existing) {
            return response()->json(["status" => "success", "message" => "Order sudah tersimpan"]);
        }

        // If user_id is 0, try to resolve from email
        if ($userId <= 0 && !empty($email)) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $userId = $user->id;
            }
        }

        try {
            DB::table('orders')->insert([
                'order_id'        => $orderId,
                'user_id'         => $userId > 0 ? $userId : null,
                'user_email'      => $email,
                'customer_name'   => $customerName,
                'customer_phone'  => $phone,
                'items_title'     => $itemsTitle,
                'items_subtitle'  => $itemsSubtitle,
                'items_json'      => is_string($itemsJson) ? $itemsJson : json_encode($itemsJson),
                'emoji'           => $emoji,
                'total_price'     => $totalPrice,
                'subtotal'        => $subtotal,
                'shipping_cost'   => $shippingCost,
                'discount'        => $discount,
                'member_discount' => $memberDiscount,
                'status'          => $status,
                'shipping_address' => $address,
                'delivery_date'   => $deliveryDate ?: null,
                'cancel_reason'   => null,
                'payment_method'  => $paymentMethod,
                'payment_status'  => $status === 'Menunggu Pembayaran' ? 'UNPAID' : 'COD',
                'notified_admin'  => 0,
                'notified_user'   => 0,
                'created_at'      => Carbon::now('Asia/Jakarta'),
                'updated_at'      => Carbon::now('Asia/Jakarta'),
            ]);

            Log::info("placeOrder: Order $orderId saved successfully for $email");
            return response()->json(["status" => "success", "message" => "Pesanan berhasil disimpan"]);
        } catch (\Exception $e) {
            Log::error("placeOrder: Exception - " . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Gagal menyimpan pesanan: " . $e->getMessage()]);
        }
    }

    // POST get_orders.php
    public function getOrders(Request $request)
    {
        $isAdmin    = $request->input('is_admin', '0') === '1';
        $userEmail  = $request->input('user_email', '');
        $adminEmail = $request->input('admin_email', '');

        if (!$isAdmin && empty($userEmail)) {
            return response()->json(["status" => "error", "message" => "Email required"]);
        }

        $query = DB::table('orders');

        if (!$isAdmin) {
            $query->where('user_email', $userEmail);
        }

        $ordersRaw = $query->orderBy('created_at', 'desc')->get();

        $orders = $ordersRaw->map(function ($row) {
            return [
                "id"             => $row->order_id ?? (string) $row->id,
                "order_id"       => $row->order_id ?? (string) $row->id,
                "customer_name"  => $row->customer_name ?? $row->user_email,
                "customer_email" => $row->user_email,
                "items_title"    => $row->items_title,
                "items_subtitle" => $row->items_subtitle ?? '',
                "items_json"     => $row->items_json ?? '[]',
                "emoji"          => $row->emoji ?? '📦',
                "total_price"    => (int) $row->total_price,
                "total"          => (int) $row->total_price,
                "status"         => $row->status,
                "address"        => $row->shipping_address ?? '',
                "delivery_date"  => $row->delivery_date ?? '',
                "cancel_reason"  => $row->cancel_reason ?? null,
                "date"           => Carbon::parse($row->created_at)->timezone('Asia/Jakarta')->format('d M Y  •  H:i'),
            ];
        });

        return response()->json([
            "status" => "success",
            "orders" => $orders,
        ]);
    }

    // POST update_order_status.php
    public function updateOrderStatus(Request $request)
    {
        $orderId      = $request->input('order_id', '');
        $newStatus    = $request->input('status', '');
        $cancelReason = $request->input('cancel_reason');

        if (empty($orderId) || empty($newStatus)) {
            return response()->json(["status" => "error", "message" => "Parameter tidak lengkap"]);
        }

        // Try matching on order_id string first, fallback to numeric id
        $updated = DB::table('orders')
            ->where('order_id', $orderId)
            ->update([
                'status'        => $newStatus,
                'cancel_reason' => $cancelReason,
                'notified_user' => 0,
                'updated_at'    => Carbon::now('Asia/Jakarta'),
            ]);

        if (!$updated) {
            $updated = DB::table('orders')
                ->where('id', $orderId)
                ->update([
                    'status'        => $newStatus,
                    'cancel_reason' => $cancelReason,
                    'notified_user' => 0,
                    'updated_at'    => Carbon::now('Asia/Jakarta'),
                ]);
        }

        if ($updated) {
            return response()->json(["status" => "success", "message" => "Status pesanan diperbarui"]);
        }

        return response()->json(["status" => "error", "message" => "Pesanan tidak ditemukan"]);
    }
}
