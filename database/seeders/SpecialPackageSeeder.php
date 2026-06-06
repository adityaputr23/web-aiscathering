<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'title' => 'Paket Nikahan',
                'badge' => 'Elegan & Mewah',
                'description' => 'Paket prasmanan lengkap dengan layanan pelayanan profesional untuk hari bahagia Anda.',
                'image' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2074&auto=format&fit=crop',
                'features' => ['Prasmanan & Gubukan', 'Waiter Profesional', 'Alat Makan Lengkap'],
                'order' => 1,
            ],
            [
                'title' => 'Paket Ulang Tahun',
                'badge' => 'Ceria & Seru',
                'description' => 'Sajian menu favorit anak-anak dan dewasa untuk merayakan pertambahan usia.',
                'image' => 'https://images.unsplash.com/photo-1530103043960-ef38714abb15?q=80&w=2069&auto=format&fit=crop',
                'features' => ['Bento Box Custom', 'Snack & Mini Buffet', 'Kemasan Unik'],
                'order' => 2,
            ],
            [
                'title' => 'Paket Kantor',
                'badge' => 'Praktis & Profesional',
                'description' => 'Konsumsi rapat atau acara kantor yang disajikan secara praktis, tepat waktu, dan berkelas.',
                'image' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop',
                'features' => ['Premium Lunch Box', 'Coffee Break Set', 'Pengiriman Tepat Waktu'],
                'order' => 3,
            ],
        ];

        foreach ($packages as $package) {
            \App\Models\SpecialPackage::create($package);
        }
    }
}
