<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'adminsmkmaulanahasanudin@gmail.com',
            'password' => bcrypt('smk-MaulanaHasanudin12'),
            'role' => 'admin',
        ]);
    }
}
