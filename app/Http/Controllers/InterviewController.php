<?php

namespace App\Http\Controllers;

use App\Models\Algorithm;
use App\Models\Application;
use App\Models\Interview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class InterviewController extends Controller
{
    /**
     * Generate an interview link for an application.
     */
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

        // Return the booking URL to the recruiter
        // In a real app we might email this, but requirement is "link that is sent (manually)"
        return back()->with('flash', [
            'type' => 'success',
            'message' => 'Interview link generated!',
            'data' => [
                'interview_url' => route('interview.book', $interview->token)
            ]
        ]);
    }

    /**
     * Show the booking page to the candidate.
     */
    public function show($token)
    {
        $interview = Interview::where('token', $token)->firstOrFail();

        if ($interview->status === 'cancelled') {
            abort(404, 'Interview cancelled');
        }

        if ($interview->status === 'scheduled') {
             return Inertia::render('Interview/Confirmed', [
                'interview' => $interview->load('application.jobPosition', 'application.applicant'),
            ]);
        }

        // Generate availability slots
        // Simple logic: Next 5 week days, 9am - 4pm, 1 hour slots
        // Excluding existing appointments
        
        $slots = $this->generateAvailabilitySlots();

        return Inertia::render('Interview/Book', [
            'interview' => $interview->load('application.jobPosition', 'application.applicant'),
            'slots' => $slots,
        ]);
    }

    /**
     * Book the interview slot.
     */
    public function update(Request $request, $token)
    {
        $interview = Interview::where('token', $token)->firstOrFail();

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        // Verify slot is still available (basic check)
        $exists = Interview::where('scheduled_at', $request->scheduled_at)
            ->where('id', '!=', $interview->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['scheduled_at' => 'This slot has just been taken. Please choose another.']);
        }

        $interview->update([
            'scheduled_at' => $request->scheduled_at,
            'status' => 'scheduled',
        ]);

        return redirect()->route('interview.book', $token)->with('success', 'Interview scheduled successfully!');
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
