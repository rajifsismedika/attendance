<?php

namespace App\Http\Controllers;

use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $startDate = now()->subDays(07);
        $attendances = $user->attendances()->where('date' , '>=', $startDate)->get()->keyBy('date');

        $periods = CarbonPeriod::create($startDate, now())->toArray();
        usort($periods, function ($a, $b) {
            return $b->timestamp - $a->timestamp; // Compare timestamps for descending order
        });

        $dates = [];
        foreach ($periods as $date) {
            $attendance = $attendances[$date->format('Y-m-d')] ?? null;
            $dates[] = $attendance
                ? $attendance
                : ['date' => $date->format('Y-m-d')];
        }

        return $dates;
    }

    public function checkIn()
    {
        $user = Auth::user();
        $attendance = $user->attendances()
            ->where('date', now()->format('Y-m-d'))
            ->first();

        if ($attendance && !empty($attendance->check_in_time)) {
            return response()->json( ['message' => 'Sudah Absen Hari ini !', 'attendance' => $attendance], 202);
        }

        $attendance = $user->attendances()->updateOrCreate([
            'date' => now()->format('Y-m-d')
        ], [
            'date' => now()->format('Y-m-d'),
            'check_in_time' => now(),
        ]);

        return response()->json( ['message' => 'Berhasil Absen!', 'attendance' => $attendance], 202);
    }

    public function checkOut()
    {
        $user = Auth::user();
        $attendance = $user->attendances()
            ->where('date', now()->format('Y-m-d'))
            ->first();

        if (empty($attendance) || empty($attendance->check_in_time)) {
            return response()->json( ['message' => 'Belum Check In Bro!', 'attendance' => $attendance], 403);
        }

        if ($attendance) {
            $attendance->check_out_time = now();
            $attendance->save();
        }

        return response()->json( ['message' => 'Berhasil Check Out!', 'attendance' => $attendance], 202);

        // return redirect(route('dashboard'));
    }
}
