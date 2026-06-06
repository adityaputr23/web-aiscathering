<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationalHour;
use Illuminate\Http\Request;

class OperationalHourController extends Controller
{
    public function index()
    {
        $hours = OperationalHour::orderBy('day_index')->get();
        return view('admin.hours.index', compact('hours'));
    }

    public function update(Request $request, OperationalHour $hour)
    {
        $request->validate([
            'open_time' => 'nullable',
            'close_time' => 'nullable',
        ]);

        $hour->update([
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'is_closed' => $request->has('is_closed'),
        ]);

        return redirect()->back()->with('success', 'Jam operasional berhasil diperbarui.');
    }
}
