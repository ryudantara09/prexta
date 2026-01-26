<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard/JobPositions/Index', [
            'jobPositions' => JobPosition::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dashboard/JobPositions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        JobPosition::create($validated);

        return redirect()->route('dashboard.job-positions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPosition $jobPosition)
    {
        return Inertia::render('Dashboard/JobPositions/Show', [
            'jobPosition' => $jobPosition
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobPosition $jobPosition)
    {
        return Inertia::render('Dashboard/JobPositions/Edit', [
            'jobPosition' => $jobPosition,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobPosition $jobPosition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $jobPosition->update($validated);

        return redirect()->route('dashboard.job-positions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPosition $jobPosition)
    {
        $jobPosition->delete();

        return redirect()->route('dashboard.job-positions.index');
    }
}
