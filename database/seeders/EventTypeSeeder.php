<?php

namespace Database\Seeders;

use App\Domains\Agenda\Models\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventType::create([
            'name' => 'Seminário',
        ]);

        EventType::create([
            'name' => 'Reunião',
        ]);
    }
}
