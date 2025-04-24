<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "Admin auto QR",
                "email" => "grantshell0@gmail.com",
                "phone" => "672517118",
                "password" => "$2y$10$5lQATLlEJyzjgPovHFIJoOo.af3DbqrDps49zKtd/F5V3sX3W0KLm",
            ],
        ];

        foreach ($users as $key => $user) {
            $existUser = User::where('name', $user['name'])->exists();

            if(!$existUser){
                User::create($user);
            }
        }
    }
}
