<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\InterviewBooking;
use App\Models\InterviewSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InterviewBookingController extends Controller
{
    public function show(Request $request, $tenant, $application)
    {
        if (! $request->hasValidSignature()) {
            return view('invitation-expired');
        }

        $applicationModel = Application::findOrFail($application);

        $slots = InterviewSlot::where('job_posting_id', $applicationModel->job_posting_id)
            ->where('is_booked', false)
            ->orderBy('starts_at')
            ->get();

        return view('interview-booking.show', [
            'application' => $applicationModel,
            'slots' => $slots,
        ]);
    }

    public function book(Request $request, $tenant, $slot)
    {
        if (! $request->hasValidSignature()) {
            return view('invitation-expired');
        }

        $slotModel = InterviewSlot::findOrFail($slot);

        if ($slotModel->is_booked) {
            return response()->view('This slot was just booked by someone else. Please pick another.',[],200);
        }

        $applicationModel = Application::findOrFail($request->query('application'));

        InterviewBooking::create([
            'slot_id' => $slotModel->id,
            'application_id' => $applicationModel->id,
            'confirmation_token' => Str::random(32),
            'booked_at' => now(),
        ]);

        $slotModel->update(['is_booked' => true]);

        return view('interview-booking.confirmed', ['slot' => $slotModel]);
    }
}
