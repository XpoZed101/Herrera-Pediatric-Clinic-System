<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Repositories\AppointmentRepository;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentRepository $repository)
    {
    }

    public function index(): RedirectResponse
    {
        // Redirect index to the appointment creation page per request
        return redirect()->route('client.appointments.create');
    }

    /**
     * Return available times for a given date (9:00–15:00, every 30 minutes),
     * excluding times already scheduled.
     */
    public function availableTimes(\Illuminate\Http\Request $request)
    {
        $date = $request->query('date');
        if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json(['error' => 'Invalid date'], 422);
        }

        $allowedTimes = ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00'];

        $existingTimes = Appointment::where('status', 'scheduled')
            ->whereDate('scheduled_at', $date)
            ->pluck('scheduled_at')
            ->map(fn ($dt) => Carbon::parse($dt)->format('H:i'))
            ->all();

        $available = array_values(array_diff($allowedTimes, $existingTimes));

        return response()->json([
            'date' => $date,
            'allowed' => $allowedTimes,
            'booked' => $existingTimes,
            'available' => $available,
        ]);
    }

    public function create(): View
    {
        $currentAppointment = Appointment::where('user_id', Auth::id())
            ->latest('created_at')
            ->first();

        return view('client.appointments.create', [
            'currentAppointment' => $currentAppointment,
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        // Compose scheduled_at from separate date and time
        $date = $payload['scheduled_date'] ?? null;
        $time = $payload['scheduled_time'] ?? null;
        if ($date && $time) {
            $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
            if ($scheduledAt->isPast()) {
                return back()->withErrors([
                    'scheduled_date' => __('Please choose a future date and time.'),
                ])->withInput();
            }
            $payload['scheduled_at'] = $scheduledAt;
        }
        unset($payload['scheduled_date'], $payload['scheduled_time']);
        // Use Auth facade to satisfy static analysis for union type of auth()
        $payload['user_id'] = Auth::id();

        // Block creating a new appointment if any existing is not completed
        if ($this->repository->hasNotCompletedForUser($payload['user_id'])) {
            return back()->withErrors([
                'appointment' => __('You already have an active appointment that is not completed. Please complete or cancel it before creating a new one.'),
            ]);
        }

        // If selected time already has a scheduled appointment, suggest alternatives
        if (!empty($payload['scheduled_at'])) {
            $selected = $payload['scheduled_at'];
            $hasConflict = Appointment::where('status', 'scheduled')
                ->where('scheduled_at', $selected)
                ->exists();

            if ($hasConflict) {
                $allowedTimes = ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00'];
                $existingTimes = Appointment::where('status', 'scheduled')
                    ->whereDate('scheduled_at', $selected->toDateString())
                    ->pluck('scheduled_at')
                    ->map(fn ($dt) => Carbon::parse($dt)->format('H:i'))
                    ->all();

                $availableToday = array_values(array_diff($allowedTimes, $existingTimes));

                $suggested = [];
                foreach ($availableToday as $t) {
                    $suggested[] = [
                        'date' => $selected->toDateString(),
                        'time' => $t,
                    ];
                    if (count($suggested) >= 5) break; // suggest up to 5 options
                }

                // If none available today, suggest next day same allowed times
                if (empty($suggested)) {
                    $nextDay = $selected->copy()->addDay()->toDateString();
                    foreach ($allowedTimes as $t) {
                        $suggested[] = [
                            'date' => $nextDay,
                            'time' => $t,
                        ];
                        if (count($suggested) >= 5) break;
                    }
                }

                return back()
                    ->withErrors(['appointment' => __('Selected time is not available. Please choose a suggested time.')])
                    ->with('suggested_times', $suggested)
                    ->withInput();
            }
        }

        $this->repository->create($payload);

        // Redirect directly to create page to show busy success message on the same layout
        return redirect()
            ->route('client.appointments.create')
            ->with('status', __('Appointment requested successfully. We’ll notify you soon.'));
    }
}