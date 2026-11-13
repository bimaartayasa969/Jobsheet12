<?php

// Nama   : Bima Artayasa
// Nim    : 3202416151
// Kelas  : TIF 5 C

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@siakad.test'],
            [
                'name'      => 'Administrator SIAKAD',
                'password'  => Hash::make('Admin@12345'),
                'role'      => 'admin',
            ]
        );
    }
}
