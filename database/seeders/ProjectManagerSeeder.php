<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Payment;
use App\Models\PriceCategory;
use App\Models\Project;
use App\Models\ProjectFeature;
use Illuminate\Database\Seeder;

class ProjectManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create price categories with fixed data
        $categories = [
            ['name' => 'CRUD Sederhana', 'base_price' => 30000, 'description' => 'Fitur CRUD standar untuk master data'],
            ['name' => 'CRUD Kompleks', 'base_price' => 50000, 'description' => 'CRUD dengan relasi dan validasi kompleks'],
            ['name' => 'Implementasi Algoritma', 'base_price' => 350000, 'description' => 'Algoritma SAW, AHP, TOPSIS, dll'],
            ['name' => 'Integrasi API', 'base_price' => 150000, 'description' => 'Integrasi dengan API eksternal'],
            ['name' => 'Export PDF', 'base_price' => 75000, 'description' => 'Fitur export laporan ke PDF'],
            ['name' => 'Export Excel', 'base_price' => 50000, 'description' => 'Fitur export data ke Excel'],
            ['name' => 'Dashboard & Statistik', 'base_price' => 100000, 'description' => 'Dashboard dengan chart dan statistik'],
            ['name' => 'Authentikasi', 'base_price' => 40000, 'description' => 'Login, register, reset password'],
            ['name' => 'Upload File', 'base_price' => 35000, 'description' => 'Fitur upload dan manajemen file'],
            ['name' => 'Notifikasi Email', 'base_price' => 60000, 'description' => 'Pengiriman email notifikasi'],
        ];

        foreach ($categories as $category) {
            PriceCategory::create($category);
        }

        $priceCategories = PriceCategory::all();

        // Create 10 clients
        $clients = Client::factory()->count(10)->create();

        // Make some clients referred by others
        $clients[2]->update(['referred_by_client_id' => $clients[0]->id]);
        $clients[3]->update(['referred_by_client_id' => $clients[0]->id]);
        $clients[4]->update(['referred_by_client_id' => $clients[0]->id]);
        $clients[5]->update(['referred_by_client_id' => $clients[1]->id]);
        $clients[6]->update(['referred_by_client_id' => $clients[1]->id]);
        $clients[7]->update(['referred_by_client_id' => $clients[2]->id]);

        // Project names
        $projectNames = [
            'Sistem Informasi Penjualan',
            'Aplikasi Inventory',
            'SPK Pemilihan Karyawan Terbaik',
            'Website Company Profile',
            'E-Commerce Toko Online',
            'Sistem Pakar Diagnosa Penyakit',
            'Aplikasi Kasir POS',
            'Sistem Manajemen Perpustakaan',
        ];

        // Create projects with features
        foreach ($projectNames as $index => $projectName) {
            $client = $clients[$index % count($clients)];
            $status = ['pending', 'in_progress', 'completed', 'paid'][rand(0, 3)];

            $project = Project::create([
                'client_id' => $client->id,
                'project_name' => $projectName,
                'status' => $status,
                'notes' => rand(0, 1) ? 'Deadline bulan depan' : null,
            ]);

            // Add 2-5 features per project
            $featureCount = rand(2, 5);
            $usedCategories = $priceCategories->random($featureCount);

            foreach ($usedCategories as $category) {
                ProjectFeature::create([
                    'project_id' => $project->id,
                    'price_category_id' => $category->id,
                    'description' => null,
                    'custom_price' => rand(0, 10) > 8 ? rand(25000, 100000) : null,
                ]);
            }

            // Finalize completed/paid projects
            if (in_array($status, ['completed', 'paid'])) {
                $discount = 0;

                // Apply referral discount for some
                if ($client->available_referral_credit > 0 && rand(0, 1)) {
                    $discount = min($client->available_referral_credit, $project->base_price);
                    $client->useReferralCredit($discount);
                }

                $project->finalize($discount);
            }

            // Add payments for completed/paid projects
            if ($status === 'paid') {
                $totalToPay = $project->final_price ?? $project->base_price;
                $paymentCount = rand(1, 3);
                $paidAmount = 0;

                for ($i = 0; $i < $paymentCount; $i++) {
                    $isLast = ($i === $paymentCount - 1);
                    $amount = $isLast ? ($totalToPay - $paidAmount) : rand(50000, (int) (($totalToPay - $paidAmount) / 2));

                    if ($amount > 0) {
                        Payment::create([
                            'project_id' => $project->id,
                            'amount' => $amount,
                            'payment_method' => ['transfer', 'cash', 'ewallet'][rand(0, 2)],
                            'notes' => $isLast ? 'Pelunasan' : 'DP',
                            'payment_date' => now()->subDays(rand(1, 30)),
                        ]);
                        $paidAmount += $amount;
                    }
                }
            } elseif ($status === 'completed' && rand(0, 1)) {
                // Some completed projects have partial payments
                $totalToPay = $project->final_price ?? $project->base_price;
                $amount = rand(50000, (int) ($totalToPay / 2));

                Payment::create([
                    'project_id' => $project->id,
                    'amount' => $amount,
                    'payment_method' => ['transfer', 'cash'][rand(0, 1)],
                    'notes' => 'DP',
                    'payment_date' => now()->subDays(rand(1, 14)),
                ]);
            }
        }
    }
}
