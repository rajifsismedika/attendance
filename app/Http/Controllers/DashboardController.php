<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $attendance = Auth::user()->attendances()
            ->where('date', now()->format('Y-m-d'))
            ->first();

        return Inertia::render('Dashboard', compact('attendance'));
    }
}
