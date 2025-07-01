<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\EnergiListrik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EnergiListrik>
 */
class EnergiListrikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = EnergiListrik::class;
    public function definition()
    {
        static $minutes = 0;
        return [
            //
            'waktu' => Carbon::now()->subMinutes(119)->addMinutes($minutes++),
            'tegangan_r' => $this->faker->randomFloat(2, 200, 250),
            'tegangan_s' => $this->faker->randomFloat(2, 200, 250),
            'tegangan_t' => $this->faker->randomFloat(2, 200, 250),
            'arus_r' => $this->faker->randomFloat(2, 1, 5),
            'arus_s' => $this->faker->randomFloat(2, 1, 5),
            'arus_t' => $this->faker->randomFloat(2, 1, 5),
            'daya_r' => $this->faker->randomFloat(2, 187, 935),
            'daya_s' => $this->faker->randomFloat(2, 187, 935),
            'daya_t' => $this->faker->randomFloat(2, 187, 935),
            'energi_r' => $this->faker->randomFloat(2, 500, 1000),
            'energi_s' => $this->faker->randomFloat(2, 500, 1000),
            'energi_t' => $this->faker->randomFloat(2, 500, 1000),
            'faktor_daya_r' => 0.85,
            'faktor_daya_s' => 0.85,
            'faktor_daya_t' => 0.85,
            'frekuensi_r' => 50,
            'frekuensi_s' => 50,
            'frekuensi_t' => 50,
        ];
    }
}
