<?php

use Database\Seeders\PackageSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Offres d'abonnement (1, 3 et 12 mois). Faite en migration pour que le
 * déploiement se limite à `php artisan migrate`. Les anciennes offres
 * (« Premium 3 », « VIP Ndop ») sont retirées de la vente, pas supprimées.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new PackageSeeder())->run();
    }

    public function down(): void
    {
        // Données : pas de retour arrière
    }
};
