<?php

namespace Database\Seeders;

use App\Models\OperationalHour;
use Illuminate\Database\Seeder;

class OperationalHourSeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            ['index' => 1, 'name' => 'Senin', 'open' => '08:00', 'close' => '20:00'],
            ['index' => 2, 'name' => 'Selasa', 'open' => '08:00', 'close' => '20:00'],
            ['index' => 3, 'name' => 'Rabu', 'open' => '08:00', 'close' => '20:00'],
            ['index' => 4, 'name' => 'Kamis', 'open' => '08:00', 'close' => '20:00'],
            ['index' => 5, 'name' => 'Jumat', 'open' => '08:00', 'close' => '20:00'],
            ['index' => 6, 'name' => 'Sabtu', 'open' => '09:00', 'close' => '18:00'],
            ['index' => 0, 'name' => 'Minggu', 'open' => '09:00', 'close' => '18:00'],
        ];

        foreach ($days as $day) {
            OperationalHour::create([
                'day_index' => $day['index'],
                'day_name' => $day['name'],
                'open_time' => $day['open'],
                'close_time' => $day['close'],
                'is_closed' => false,
            ]);
        }
    }
}
