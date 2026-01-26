<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    public function create(JobPosition $job)
    {
        return Inertia::render('Public/Applications/Create', [
            'job' => $job,
        ]);
    }

    public function store(Request $request, JobPosition $job)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
        ]);

        // Find or create applicant by email
        $applicant = Applicant::firstOrCreate(
            ['email' => $validated['email']],
            ['full_name' => $validated['full_name']]
        );

        // Store CV file
        $cvPath = $request->file('cv')->store('cvs', 'public');

        // Create application
        Application::create([
            'job_position_id' => $job->id,
            'applicant_id' => $applicant->id,
            'cv_path' => $cvPath,
            'cover_letter' => $validated['cover_letter'] ?? null,
        ]);

        return redirect()->route('applications.success');
    }

    public function success()
    {
        return Inertia::render('Public/Applications/Success');
    }

    public function index(Request $request)
    {
        $query = Application::with(['applicant', 'jobPosition', 'interview'])
            ->latest();

        // Filter by job position
        if ($request->filled('job_position_id')) {
            $query->where('job_position_id', $request->job_position_id);
        }

        // Filter by applicant
        if ($request->filled('applicant_id')) {
            $query->where('applicant_id', $request->applicant_id);
        }

        $applications = $query->get();

        return Inertia::render('Dashboard/Applications/Index', [
            'applications' => $applications,
            'jobPositions' => JobPosition::all(['id', 'title']),
            'applicants' => Applicant::all(['id', 'full_name']),
            'filters' => $request->only(['job_position_id', 'applicant_id']),
        ]);
    }

    public function updateNote(Request $request, Application $application): void
    {
        $validated = $request->validate([
            'recruiter_note' => 'nullable|string',
        ]);

        $application->update([
            'recruiter_note' => $validated['recruiter_note'],
        ]);
    }
}
