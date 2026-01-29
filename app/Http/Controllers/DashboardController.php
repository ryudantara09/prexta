<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_jobs' => JobPosition::count(),
                'applicants' => [
                    'weekly' => Application::where('created_at', '>=', now()->subWeek())->count(),
                    'biweekly' => Application::where('created_at', '>=', now()->subWeeks(2))->count(),
                    'monthly' => Application::where('created_at', '>=', now()->subMonth())->count(),
                    'total' => Application::count(),
                ],
                'upcoming_appointments' => \App\Models\Interview::where('status', 'scheduled')
                    ->where('scheduled_at', '>=', now())
                    ->count(),
            ],
            'recent_applications' => Application::with(['applicant', 'jobPosition', 'interview'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
