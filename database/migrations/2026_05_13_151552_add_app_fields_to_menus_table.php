<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sync menus table with Android App structure.
     * Adds: emoji, rating, sold columns.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'emoji')) {
                $table->string('emoji', 20)->nullable()->after('category');
            }
            if (!Schema::hasColumn('menus', 'rating')) {
                $table->float('rating')->default(5.0)->after('emoji');
            }
            if (!Schema::hasColumn('menus', 'sold')) {
                $table->integer('sold')->default(0)->after('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['emoji', 'rating', 'sold']);
        });
    }
};
