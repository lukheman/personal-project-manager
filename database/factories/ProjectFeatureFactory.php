<?php

namespace Database\Factories;

use App\Models\PriceCategory;
use App\Models\Project;
use App\Models\ProjectFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectFeature>
 */
class ProjectFeatureFactory extends Factory
{
    protected $model = ProjectFeature::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $descriptions = [
            'Login & Register User',
            'Manajemen Data Produk',
            'Manajemen Data Pelanggan',
            'Laporan Penjualan Bulanan',
            'Perhitungan Metode SAW',
            'Perhitungan Metode AHP',
            'Chart Dashboard',
            'Export ke PDF',
            'Export ke Excel',
            'Integrasi Payment Gateway',
            'Notifikasi WhatsApp',
            'Upload Gambar Produk',
            'Keranjang Belanja',
            'Checkout & Invoice',
            'Manajemen Stok',
        ];

        return [
            'project_id' => Project::factory(),
            'price_category_id' => PriceCategory::factory(),
            'description' => fake()->randomElement($descriptions),
            'custom_price' => fake()->optional(0.2)->numberBetween(25000, 500000),
        ];
    }
}
