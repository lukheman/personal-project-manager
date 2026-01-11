<?php

namespace Database\Factories;

use App\Models\PriceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceCategory>
 */
class PriceCategoryFactory extends Factory
{
    protected $model = PriceCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            ['name' => 'CRUD Sederhana', 'price' => 30000, 'desc' => 'Fitur CRUD standar untuk master data'],
            ['name' => 'CRUD Kompleks', 'price' => 50000, 'desc' => 'CRUD dengan relasi dan validasi kompleks'],
            ['name' => 'Implementasi Algoritma', 'price' => 350000, 'desc' => 'Algoritma SAW, AHP, TOPSIS, dll'],
            ['name' => 'Integrasi API', 'price' => 150000, 'desc' => 'Integrasi dengan API eksternal'],
            ['name' => 'Export PDF', 'price' => 75000, 'desc' => 'Fitur export laporan ke PDF'],
            ['name' => 'Export Excel', 'price' => 50000, 'desc' => 'Fitur export data ke Excel'],
            ['name' => 'Dashboard & Statistik', 'price' => 100000, 'desc' => 'Dashboard dengan chart dan statistik'],
            ['name' => 'Authentikasi', 'price' => 40000, 'desc' => 'Login, register, reset password'],
            ['name' => 'Upload File', 'price' => 35000, 'desc' => 'Fitur upload dan manajemen file'],
            ['name' => 'Notifikasi Email', 'price' => 60000, 'desc' => 'Pengiriman email notifikasi'],
        ];

        $category = fake()->randomElement($categories);

        return [
            'name' => $category['name'],
            'base_price' => $category['price'],
            'description' => $category['desc'],
        ];
    }
}
