<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /** Offres en vente (le prix et la durée restent modifiables en base). */
    public const PLANS = [
        [
            'name' => 'Mensuel',
            'duration' => 1,
            'amount' => 1000,
            'description' => 'Alertes illimitées pendant 1 mois',
            'sort_order' => 1,
        ],
        [
            'name' => 'Trimestriel',
            'duration' => 3,
            'amount' => 2500,
            'description' => 'Alertes illimitées pendant 3 mois',
            'sort_order' => 2,
        ],
        [
            'name' => 'Annuel',
            'duration' => 12,
            'amount' => 8000,
            'description' => 'Alertes illimitées pendant 12 mois',
            'sort_order' => 3,
        ],
    ];

    /** Anciennes offres : retirées de la vente, conservées pour l'historique. */
    public const RETIRED = ['Premium 3', 'VIP Ndop'];

    public function run(): void
    {
        foreach (self::PLANS as $plan) {
            // Ne crée que les offres absentes : un prix modifié en base n'est pas écrasé
            Package::firstOrCreate(
                ['name' => $plan['name']],
                $plan + ['is_active' => true],
            );
        }

        Package::whereIn('name', self::RETIRED)->update(['is_active' => false]);
    }
}
