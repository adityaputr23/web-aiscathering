<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\OperationalStatusService;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/operational-status', function (OperationalStatusService $service) {
    return response()->json($service->current());
});

// Android App API Endpoints (migrated from c:\xampp\htdocs\aish_api)
use App\Http\Controllers\Api\AndroidApiController;

Route::post('/register.php', [AndroidApiController::class, 'register']);
Route::post('/login.php', [AndroidApiController::class, 'login']);
Route::post('/google_auth.php', [AndroidApiController::class, 'googleAuth']);
Route::post('/update_profile.php', [AndroidApiController::class, 'updateProfile']);
Route::post('/update_profile_with_image.php', [AndroidApiController::class, 'updateProfileWithImage']);
Route::post('/delete_profile_image.php', [AndroidApiController::class, 'deleteProfileImage']);
Route::post('/get_notifications.php', [AndroidApiController::class, 'getNotifications']);
Route::post('/send_message.php', [AndroidApiController::class, 'sendMessage']);
Route::post('/get_chat_history.php', [AndroidApiController::class, 'getChatHistory']);
Route::post('/get_chat_list.php', [AndroidApiController::class, 'getChatList']);
Route::post('/get_active_vouchers.php', [AndroidApiController::class, 'getActiveVouchers']);
Route::post('/get_user_stats.php', [AndroidApiController::class, 'getUserStats']);
Route::get('/get_reviews.php', [AndroidApiController::class, 'getReviews']);
Route::post('/add_review.php', [AndroidApiController::class, 'addReview']);
Route::post('/join_membership.php', [AndroidApiController::class, 'joinMembership']);
Route::post('/delete_message.php', [AndroidApiController::class, 'deleteMessage']);
Route::post('/edit_message.php', [AndroidApiController::class, 'editMessage']);
Route::post('/get_menus.php', [AndroidApiController::class, 'getMenus']);
Route::post('/save_menu.php', [AndroidApiController::class, 'saveMenu']);
Route::post('/delete_menu.php', [AndroidApiController::class, 'deleteMenu']);
Route::post('/save_schedule.php', [AndroidApiController::class, 'saveSchedule']);
Route::get('/get_schedules.php', [AndroidApiController::class, 'getSchedules']);
Route::get('/get_users.php', [AndroidApiController::class, 'getUsers']);
Route::post('/save_user.php', [AndroidApiController::class, 'saveUser']);
Route::post('/delete_user.php', [AndroidApiController::class, 'deleteUser']);
