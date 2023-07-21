<?php

namespace Database\Seeders;

use App\Domains\User\Models\Event;
use App\Domains\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@agenda.com.br',
            'password' => bcrypt('admin')
        ]);
    }
}
