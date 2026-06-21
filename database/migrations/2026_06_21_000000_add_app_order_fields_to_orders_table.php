<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_id')) {
                $table->string('order_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_email');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'items_subtitle')) {
                $table->string('items_subtitle')->nullable()->after('items_title');
            }
            if (!Schema::hasColumn('orders', 'emoji')) {
                $table->string('emoji')->nullable()->after('items_json');
            }
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->integer('subtotal')->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->integer('shipping_cost')->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->integer('discount')->default(0)->after('shipping_cost');
            }
            if (!Schema::hasColumn('orders', 'member_discount')) {
                $table->integer('member_discount')->default(0)->after('discount');
            }
            if (!Schema::hasColumn('orders', 'delivery_date')) {
                $table->string('delivery_date')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'notified_admin')) {
                $table->tinyInteger('notified_admin')->default(0)->after('fcm_token_user');
            }
            if (!Schema::hasColumn('orders', 'notified_user')) {
                $table->tinyInteger('notified_user')->default(0)->after('notified_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $cols = [
                'order_id', 'customer_name', 'customer_phone',
                'items_subtitle', 'emoji', 'subtotal', 'shipping_cost',
                'discount', 'member_discount', 'delivery_date',
                'cancel_reason', 'notified_admin', 'notified_user',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
