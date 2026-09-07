<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->uuid('device_id')->unique();
            $table->string('device_name')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->text('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->text('gpu')->nullable();
            $table->text('storage')->nullable();
            $table->string('windows_version')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
