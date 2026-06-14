<?php

// ============================================================
// database/migrations/xxxx_create_notifications_table.php
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('message_key', 50);            // clé du message (blocking, urgent...)
            $table->string('message_text');               // texte complet du message
            $table->string('ip_hash', 64)->nullable();   // IP signalant (hashée)
            $table->boolean('is_read')->default(false);  // lu ou non
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_notifications');
    }
};
