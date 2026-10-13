<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Lancement de l'abonnement : sans ça, les utilisateurs inscrits depuis plus
 * de 30 jours perdraient leurs alertes le jour même du déploiement.
 * Chaque compte existant garde donc au moins 30 jours d'essai à partir
 * d'aujourd'hui (les essais plus longs ne sont pas raccourcis).
 */
return new class extends Migration
{
    public function up(): void
    {
        $launchTrialEnd = now()->addDays(30)->toDateString();

        DB::table('users')
            ->where(function ($q) use ($launchTrialEnd) {
                $q->whereNull('end_trial_period')
                    ->orWhere('end_trial_period', '<', $launchTrialEnd);
            })
            ->update(['end_trial_period' => $launchTrialEnd]);
    }

    public function down(): void
    {
        // Données : pas de retour arrière (on ne raccourcit pas les essais)
    }
};
