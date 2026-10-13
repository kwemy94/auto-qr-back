<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Réponse du propriétaire à un signalement (« J'arrive », « Dans 5 min »…).
 * public_id : identifiant non devinable remis au signaleur anonyme pour
 * suivre le statut de SON signalement (sans exposer l'id interne).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_notifications', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->unique()->after('id');
            $table->string('response_key', 20)->nullable()->after('read_at');
            $table->timestamp('responded_at')->nullable()->after('response_key');
        });
    }

    public function down(): void
    {
        Schema::table('qr_notifications', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn(['public_id', 'response_key', 'responded_at']);
        });
    }
};
