<?php

namespace Database\Factories;

use App\Models\TblRumahSakit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TblRumahSakitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TblRumahSakit::class;

    public function definition(): array
    {
        $rumahsakit = [
            'RS Harapan Sehat',
            'RS Mitra Keluarga',
            'RS Citra Medika',
            'RS Kasih Ibu',
            'RS Bhakti Husada',
            'RS Siloam',
            'RS Hermina',
            'RS Premier Jatinegara',
            'RSUD Dr. Soetomo',
            'RSUP Fatmawati',
            'RS Umum Daerah Sultan Fatah Karangawen Demak',
            'RSUD Budi Rahayu',
            'RSU Surakarta BKPM',
            'RS PKU Aisyiyah Jepara',
            'RS Gigi dan Mulut Unimus',
            'RS Onkologi Solo',
            'RSU Charlie Hospital',
            'RS Harapan Sehat Bumiayu',
            'RS Islam Gigi dan Mulut Sultan Agung',
            'RS "JIH" Solo',
            'RS Harapan Sehat Pemalang',
            'RSU Umum Daerah Bung Karno Kota Surakarta',
            'RS Hawari Essa',
            'RS Harapan Sehat Jatibarang',
            'RS Harapan Sehat Slawi',
            'RS Umum Aghisna Medika Sidareja',
            'RS Umum PKU Muhammadiyah Banjarnegara',
            'Siloam Hospitals Semarang',
            'RS Umum Syubbanul Wathon',
            'RSU PKU Muhammadiyah Delanggu',
        ];

        return [
            'nama_rumah_sakit' => $this->faker->unique()->randomElement($rumahsakit),
            'alamat' => $this->faker->address(),
            'email'=> fake()->unique()->safeEmail(),
            'telepon' => $this->faker->numerify('628########'),
        ];
    }
}
