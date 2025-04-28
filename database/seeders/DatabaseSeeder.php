<?php

namespace Database\Seeders;

use App\Models\paket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // User::create([
        //     'name' => 'Owner',
        //     'email' => 'owner@gmail.com',
        //     'password' => Hash::make('owner123'),
        //     'role' => 'owner',
        // ]);

        paket::create([
            'nama_paket' => 'Paket A',
            'harga' => 100000,
            'deskripsi' => 'Paket A adalah paket dasar',
        ]);
        paket::create([
            'nama_paket' => 'Paket B',
            'harga' => 200000,
            'deskripsi' => 'Paket B adalah paket menengah',
        ]);
        paket::create([
            'nama_paket' => 'Paket C',
            'harga' => 300000,
            'deskripsi' => 'Paket C adalah paket premium',
        ]);
    }
}
