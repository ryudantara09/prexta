<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use Inertia\Inertia;

use Illuminate\Http\Request;

class PublicJobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosition::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Public/Jobs/Index', [
            'jobs' => $query->latest()->get(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(JobPosition $job)
    {
        return Inertia::render('Public/Jobs/Show', [
            'job' => $job,
        ]);
    }
}
