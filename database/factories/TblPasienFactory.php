<?php

namespace Database\Factories;

use App\Models\TblPasien;
use Illuminate\Database\Eloquent\Factories\Factory;

class TblPasienFactory extends Factory
{
    protected $model = TblPasien::class;

    public function definition(): array
    {
        return [
            'nama_pasien' => $this->faker->name(),
            'id_rumah_sakit' => $this->faker->numberBetween(1, 30),
            'alamat' => $this->faker->address(),
            'no_telp' => $this->faker->phoneNumber(),
        ];
    }
}
