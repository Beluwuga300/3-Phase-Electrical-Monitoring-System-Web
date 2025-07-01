<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\EnergiListrik;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EnergiListrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        EnergiListrik::factory()->count(120)->create();
        $start = Carbon::now()->subMinutes(119); // mulai dari 19 menit yang lalu

        for ($i = 0; $i < 20; $i++) {
            EnergiListrik::factory()->create([
                'waktu' => $start->copy()->addMinutes($i), // tambah 1 menit tiap data
            ]);
    }
    }
}
