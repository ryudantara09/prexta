<?php

namespace App\Http\Controllers;

use App\Models\Algorithm;
use App\Models\Application;
use App\Models\Interview;
use App\Models\JobPosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class InterviewController extends Controller
{
    /**
     * Display a listing of upcoming interviews.
     */
    public function index()
    {
        $interviews = Interview::with(['application.applicant', 'application.jobPosition'])
            ->whereNotNull('scheduled_at')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at')
            ->get()
            ->map(function ($interview) {
                if ($interview->application) {
                    $title = 'Interview with ' . $interview->application->applicant->full_name;
                    $applicantName = $interview->application->applicant->full_name;
                    $job = $interview->application->jobPosition->title;
                } else {
                    $title = $interview->meeting_title ?? 'Meeting with ' . ($interview->guest_name ?? 'Guest');
                    $applicantName = $interview->guest_name ?? 'Guest';
                    $job = 'General Meeting';
                }

                return [
                    'id' => $interview->id,
                    'title' => $title,
                    'start' => $interview->scheduled_at->format('Y-m-d H:i:00'),
                    'end' => $interview->scheduled_at->addHour()->format('Y-m-d H:i:00'),
                    'extendedProps' => [
                        'applicant' => $applicantName,
                        'job' => $job,
                        'status' => $interview->status,
                        'is_application' => $interview->application_id !== null,
                        'guest_name' => $interview->guest_name,
                        'meeting_title' => $interview->meeting_title,
                    ]
                ];
            });

        return Inertia::render('Dashboard/Interviews/Index', [
            'events' => $interviews,
            'applications' => Application::with('applicant')
                ->whereDoesntHave('interview', function ($query) {
                    $query->where('status', 'scheduled');
                })
                ->get()
                ->map(function ($app) {
                    return [
                        'id' => $app->id,
                        'name' => $app->applicant->full_name,
                    ];
                }),
            'job_positions' => JobPosition::select('id', 'title')->get()
        ]);
    }

    public function pending()
    {
        $pendingInterviews = Interview::with(['application.applicant'])
            ->where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->latest()
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'token' => $interview->token,
                    'created_at' => $interview->created_at->format('Y-m-d H:i'),
                    'ttl' => $interview->created_at->addHours(24)->diffForHumans(null, true, false, 2),
                    'url' => route('interview.book', $interview->token),
                    'person' => $interview->application ? $interview->application->applicant->full_name : ($interview->guest_name ?? 'Guest/General'),
                ];
            });

        return Inertia::render('Dashboard/Interviews/Pending', [
            'pendingInterviews' => $pendingInterviews,
        ]);
    }

    public function cancel(Interview $interview)
    {
        $interview->update([
            'status' => 'cancelled',
            'scheduled_at' => null,
        ]);

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Interview cancelled successfully.',
        ]);
    }

    public function move(Request $request, Interview $interview)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $interview->update([
            'scheduled_at' => $request->scheduled_at,
        ]);

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Interview rescheduled successfully.',
        ]);
    }

    public function updateDashboard(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date', // Allow past dates for record keeping if admin wants
            'status' => 'required|in:pending,scheduled,cancelled,completed',
            'meeting_title' => 'nullable|string|max:255',
            'guest_name' => 'nullable|string|max:255',
        ]);

        $interview->update([
            'scheduled_at' => $validated['scheduled_at'],
            'status' => $validated['status'],
            'meeting_title' => $validated['meeting_title'] ?? $interview->meeting_title,
            'guest_name' => $validated['guest_name'] ?? $interview->guest_name,
        ]);

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Interview updated successfully.',
        ]);
    }

    public function store(Application $application)
    {
        // Check if interview already exists
        $interview = $application->interview;

        if (!$interview) {
            $interview = $application->interview()->create([
                'token' => Str::upper(Str::random(10)), // Short, readable token
                'status' => 'pending',
            ]);
        }

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Interview link generated!',
            'data' => [
                'interview_url' => route('interview.book', $interview->token)
            ]
        ]);
    }

    /**
     * Generate a general meeting link.
     */
    public function storeGeneral(Request $request)
    {
        $interview = Interview::create([
            'token' => Str::upper(Str::random(10)),
            'status' => 'pending',
            'meeting_title' => $request->meeting_title ?? 'Meeting',
        ]);

        \Illuminate\Support\Facades\Log::info('Meeting link generated', ['url' => route('interview.book', $interview->token)]);

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Meeting link generated!',
            'data' => [
                'interview_url' => route('interview.book', $interview->token)
            ]
        ]);
    }

    /**
     * Manually schedule an interview for an application.
     */
    public function manualSchedule(Request $request, Application $application)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $interview = $application->interview()->updateOrCreate(
            ['application_id' => $application->id],
            [
                'token' => Str::upper(Str::random(10)),
                'scheduled_at' => $request->scheduled_at,
                'status' => 'scheduled',
            ]
        );

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Interview scheduled successfully!',
        ]);
    }

    public function show($token)
    {
        $interview = Interview::where('token', $token)->firstOrFail();

        if ($interview->status === 'cancelled') {
            abort(404, 'Interview cancelled');
        }

        if ($interview->created_at->addHours(24)->isPast()) {
            abort(404, 'Link expired');
        }

        if ($interview->status === 'scheduled') {
             return Inertia::render('Interview/Confirmed', [
                'interview' => $interview->load('application.jobPosition', 'application.applicant'),
            ]);
        }

        $slots = $this->generateAvailabilitySlots();

        return Inertia::render('Interview/Book', [
            'interview' => $interview->load('application.jobPosition', 'application.applicant'),
            'slots' => $slots,
        ]);
    }

    public function update(Request $request, $token)
    {
        $interview = Interview::where('token', $token)->firstOrFail();

        $rules = [
            'scheduled_at' => 'required|date|after:now',
        ];

        if (!$interview->application_id) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        // Verify slot is still available
        $exists = Interview::where('scheduled_at', $request->scheduled_at)
            ->where('id', '!=', $interview->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['scheduled_at' => 'This slot has just been taken. Please choose another.']);
        }

        $interview->update([
            'scheduled_at' => $request->scheduled_at,
            'status' => 'scheduled',
            'guest_name' => $request->guest_name ?? $interview->guest_name,
            'guest_email' => $request->guest_email ?? $interview->guest_email,
        ]);

        return redirect()->route('interview.book', $token)->with('success', 'Meeting scheduled successfully!');
    }

    private function generateAvailabilitySlots()
    {
        $slots = [];
        $startDate = Carbon::now()->addDay()->startOfDay(); // Start from tomorrow
        if ($startDate->isWeekend()) {
            $startDate->next('Monday');
        }

        $daysToCheck = 7; // Offer next 7 working days
        $daysCount = 0;
        $currentDate = $startDate->copy();

        // Admin availability: 9 AM to 5 PM (17:00)
        $startHour = 9;
        $endHour = 17;

        // Fetch existing appointments to exclude
        $existingAppointments = Interview::where('scheduled_at', '>=', $startDate)
            ->where('status', 'scheduled')
            ->get()
            ->pluck('scheduled_at')
            ->map(fn($date) => $date->format('Y-m-d H:i:00'))
            ->toArray();

        while ($daysCount < $daysToCheck) {
            if (!$currentDate->isWeekend()) {
                $daySlots = [];
                for ($hour = $startHour; $hour < $endHour; $hour++) {
                    $slotTime = $currentDate->copy()->setHour($hour)->setMinute(0)->setSecond(0);
                    $slotString = $slotTime->format('Y-m-d H:i:00');

                    if (!in_array($slotString, $existingAppointments)) {
                        $daySlots[] = [
                            'time' => $slotString,
                            'display' => $slotTime->format('h:i A'),
                            'day' => $slotTime->format('l, M j'),
                        ];
                    }
                }
                if (!empty($daySlots)) {
                    $slots[$currentDate->format('Y-m-d')] = $daySlots;
                }
                $daysCount++;
            }
            $currentDate->addDay();
        }

        return $slots;
    }
}
