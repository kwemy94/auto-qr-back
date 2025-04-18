<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                "name" => "Premium 3",
                "description" => "Vous avez accès à l'application gratuitement pendant 3 mois",
                "amount" => 7000
            ],
            [
                "name" => "VIP Ndop",
                "description" => "Vous avez accès à l'application gratuitement pendant 1 ans",
                "amount" => 15000
            ],
        ];

        foreach ($packages as $key => $package) {
            $existPackage = DB::table('packages')->where('name', $package['name'])->exists();

            if(!$existPackage){
                Package::create($package);
            }
        }
    }
}
