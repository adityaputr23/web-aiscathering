<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Nasi Kotak Premium A', 'category' => 'box', 'price' => 35000, 'description' => 'Nasi putih, ayam bakar, sambal terasi, lalapan, tahu tempe.', 'is_featured' => true],
            ['name' => 'Prasmanan Wedding Luxury', 'category' => 'prasmanan', 'price' => 85000, 'description' => 'Lengkap dengan karedok, rendang daging, kakap asam manis, sup kimlo.', 'is_featured' => true],
            ['name' => 'Snack Box Tradisional', 'category' => 'snack', 'price' => 15000, 'description' => 'Lemper ayam, risoles rogout, lapis legit, air mineral.', 'is_featured' => true],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
