<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectNames = [
            'Sistem Informasi Penjualan',
            'Aplikasi Inventory',
            'SPK Pemilihan Karyawan Terbaik',
            'Website Company Profile',
            'E-Commerce Toko Online',
            'Sistem Pakar Diagnosa Penyakit',
            'Aplikasi Kasir POS',
            'Sistem Manajemen Perpustakaan',
            'Portal Berita Online',
            'Aplikasi Presensi Karyawan',
            'Sistem Rental Mobil',
            'Aplikasi Reservasi Hotel',
            'Sistem Informasi Akademik',
            'Website Portfolio',
            'Dashboard Admin Panel',
        ];

        return [
            'client_id' => Client::factory(),
            'project_name' => fake()->randomElement($projectNames),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'paid']),
            'final_price' => null,
            'discount_applied' => 0,
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Project is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Project is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Project is completed.
     */
    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Project is paid.
     */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
        ]);
    }
}
