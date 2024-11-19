<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $userName = '"igor"'; // Alterar o nome conforme desejado



        // User::create([
        //     'name' => $userName,
        //     'email' => 'usuario@example.com', // Email do usuário
        //     'password' => Hash::make('123'), // Defina uma senha segura
        // ]);

        // \App\Models\User::factory(10)->create();
    }
}
