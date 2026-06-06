<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_hours', function (Blueprint $table) {
            $table->id();
            $table->integer('day_index'); // 0 = Sunday, 1 = Monday, etc.
            $table->string('day_name');
            $table->string('open_time')->nullable(); // e.g. "08:00"
            $table->string('close_time')->nullable(); // e.g. "20:00"
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_hours');
    }
};
