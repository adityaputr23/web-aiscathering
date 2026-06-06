<?php

namespace App\Services;

use App\Models\OperationalHour;
use Carbon\Carbon;

class OperationalStatusService
{
    public function current(?Carbon $now = null): array
    {
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $now = ($now ?: Carbon::now($timezone))->copy()->timezone($timezone);

        $today = OperationalHour::where('day_index', $now->dayOfWeek)->first();
        $currentTime = $now->format('H:i');
        $isOpen = false;
        $openTime = null;
        $closeTime = null;

        if ($today && !$today->is_closed && $today->open_time && $today->close_time) {
            $openTime = $this->normalizeTime($today->open_time);
            $closeTime = $this->normalizeTime($today->close_time);
            $isOpen = $currentTime >= $openTime && $currentTime < $closeTime;
        }

        $hours = OperationalHour::orderBy('day_index')->get()->map(function (OperationalHour $hour) {
            return [
                'day_index' => $hour->day_index,
                'day_name' => $hour->day_name,
                'open_time' => $hour->is_closed ? null : $this->normalizeTime($hour->open_time),
                'close_time' => $hour->is_closed ? null : $this->normalizeTime($hour->close_time),
                'is_closed' => (bool) $hour->is_closed,
                'label' => $hour->is_closed
                    ? 'Tutup'
                    : $this->normalizeTime($hour->open_time) . ' - ' . $this->normalizeTime($hour->close_time),
            ];
        })->values();

        return [
            'is_open' => $isOpen,
            'status' => $isOpen ? 'open' : 'closed',
            'title' => $isOpen ? 'Aish Catering BUKA!' : 'Aish Catering TUTUP',
            'body' => $isOpen
                ? 'Siap melayani pesanan Anda!'
                : 'Pesanan baru akan diproses saat toko buka.',
            'today' => [
                'day_index' => $now->dayOfWeek,
                'day_name' => $today?->day_name,
                'open_time' => $openTime,
                'close_time' => $closeTime,
                'is_closed' => !$today || (bool) $today->is_closed,
                'label' => (!$today || $today->is_closed) ? 'Tutup' : "{$openTime} - {$closeTime}",
            ],
            'current_time' => $currentTime,
            'timezone' => $timezone,
            'schedule' => $hours,
        ];
    }

    private function normalizeTime(?string $time): ?string
    {
        if (!$time) {
            return null;
        }

        return Carbon::parse($time)->format('H:i');
    }
}
