<?php

use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDash;
use App\Http\Controllers\Admin\MenuController as AdminMenu;
use App\Http\Controllers\Admin\OperationalHourController as AdminHour;
use App\Http\Controllers\Admin\LandingPageContentController as AdminContent;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public Guest Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/chat', [ChatbotController::class, 'chat']);
Route::post('/chat-live/send', [\App\Http\Controllers\LiveChatController::class, 'sendMessage']);
Route::get('/chat-live/messages', [\App\Http\Controllers\LiveChatController::class, 'getMessages']);
Route::post('/chat-live/clear', [\App\Http\Controllers\LiveChatController::class, 'clearChat']);
Route::delete('/chat-live/message/{message}', [\App\Http\Controllers\LiveChatController::class, 'userDeleteMessage']);

// Dedicated Admin Routes (Hidden)
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/login', [AdminAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuth::class, 'login']);
    Route::get('/register', [AdminAuth::class, 'showRegister'])->name('register');
    Route::post('/register', [AdminAuth::class, 'register']);
    Route::get('/forgot-password', [AdminAuth::class, 'showForgotPassword'])->name('password.request');
    
    // Auth Protected Admin Area
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDash::class, 'index'])->name('admin.dashboard');
        Route::resource('menus', AdminMenu::class, ['as' => 'admin']);
        Route::get('/hours', [AdminHour::class, 'index'])->name('admin.hours.index');
        Route::put('/hours/{hour}', [AdminHour::class, 'update'])->name('admin.hours.update');
        Route::get('/content', [AdminContent::class, 'index'])->name('admin.content.index');
        Route::put('/content/{content}', [AdminContent::class, 'update'])->name('admin.content.update');
        
        Route::get('/profile', [AdminProfile::class, 'index'])->name('admin.profile');
        Route::put('/profile', [AdminProfile::class, 'update'])->name('admin.profile.update');
        Route::post('/profile/photo', [AdminProfile::class, 'updatePhoto'])->name('admin.profile.photo');
        Route::delete('/profile/photo', [AdminProfile::class, 'deletePhoto'])->name('admin.profile.photo.delete');
        
        Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class, ['as' => 'admin']);
        Route::resource('special_packages', \App\Http\Controllers\Admin\SpecialPackageController::class, ['as' => 'admin']);

        // Chat Admin
        Route::get('/chats', [\App\Http\Controllers\LiveChatController::class, 'adminIndex'])->name('admin.chats.index');
        Route::get('/chats/raw-list', [\App\Http\Controllers\LiveChatController::class, 'adminChatsRaw'])->name('admin.chats.index_raw');
        Route::get('/chats/unread-count', [\App\Http\Controllers\LiveChatController::class, 'getUnreadCount'])->name('admin.chats.unread_count');
        Route::get('/chats/{sessionId}', [\App\Http\Controllers\LiveChatController::class, 'adminChat'])->name('admin.chats.show');
        Route::get('/chats/{sessionId}/raw', [\App\Http\Controllers\LiveChatController::class, 'getRawMessages'])->name('admin.chats.raw');
        Route::post('/chats/{sessionId}/reply', [\App\Http\Controllers\LiveChatController::class, 'adminReply'])->name('admin.chats.reply');
        Route::put('/chats/message/{message}', [\App\Http\Controllers\LiveChatController::class, 'updateMessage'])->name('admin.chats.update');
        Route::delete('/chats/message/{message}', [\App\Http\Controllers\LiveChatController::class, 'deleteMessage'])->name('admin.chats.delete');
        Route::delete('/chats/{sessionId}/delete-all', [\App\Http\Controllers\LiveChatController::class, 'adminDeleteChat'])->name('admin.chats.delete_all');
        Route::post('/chats/save-token', [\App\Http\Controllers\LiveChatController::class, 'saveFcmToken'])->name('admin.chats.save_token');
        
        // Orders Management
        Route::get('/orders', [AdminOrder::class, 'index'])->name('admin.orders.index');
        Route::put('/orders/{id}', [AdminOrder::class, 'updateStatus'])->name('admin.orders.update');
        Route::delete('/orders/{id}', [AdminOrder::class, 'destroy'])->name('admin.orders.destroy');
        Route::get('/orders/raw-latest', [AdminOrder::class, 'getLatestOrders'])->name('admin.orders.latest_raw');
        
        // Users Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);

        Route::post('/logout', [AdminAuth::class, 'logout'])->name('logout');
    });
});
