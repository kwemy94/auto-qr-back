<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Paiements Mobile Money des abonnements.
 * (La table "payments" existante est liée aux messages et n'est pas utilisée.)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();                 // notre référence (exposée à l'app)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider', 30);                     // fake, notchpay, campay…
            $table->string('provider_reference')->nullable();   // référence côté agrégateur
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XAF');
            $table->string('operator', 20);                     // mtn, orange
            $table->string('phone', 20);
            $table->string('status', 20)->default('pending');   // pending, success, failed
            $table->string('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
