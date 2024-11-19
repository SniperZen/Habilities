<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\AppointmentRequest;
use Illuminate\Support\Facades\Mail;
use App\Notifications\AppointmentRequested;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Mail\AppointmentAccepted;
use App\Notifications\AcceptedNotification;


class AppointmentController extends Controller
{
    public function create($therapistId)
    {
        $therapist = User::findOrFail($therapistId); // Assuming you have a User model for therapists
        return view('appointments.create', compact('therapist'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'mode' => 'required|string',
            'note' => 'nullable|string',
            'therapist_id' => 'required|exists:users,id',
        ]);
    
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Create the appointment and assign it to a variable
        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'therapist_id' => $request->therapist_id,
            'mode' => $request->mode,
            'note' => $request->note,
        ]);

        $therapist = User::find($appointment->therapist_id);
        $therapist->notify(new AppointmentRequested($appointment));
        // Now you can access the therapist's email
        $therapistEmail = $appointment->therapist->email; // Get the therapist's email
    
        // Send the appointment request notification to the therapist
        Mail::to($therapistEmail)->later(now()->addSeconds(5), new AppointmentRequest($appointment));
    
        // Redirect to the appointment confirmation page
        return response()->json([
            'success' => true,
            'message' => 'Appointment requested successfully!',
            'appointmentId' => $appointment->id
        ]);

    }
    
    public function show($id)
    {
        $appointments = DB::table('appointments')
            ->join('users', 'appointments.therapist_id', '=', 'users.id')
            ->select('appointments.*', 'users.first_name', 'users.last_name', 'users.profile_image')
            ->where(function($query) {
                $query->where('appointments.status', 'pending')
                      ->orWhere('appointments.status', 'accepted');
            })
            ->orderBy('appointments.appointment_date', 'asc')
            ->get();
    
        return view('patient.appntmnt', compact('appointments'));
    }

    public function index()
    {

        $appointments = Appointment::with('therapist')
        
            ->where('patient_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patient.appointments', compact('appointments'));
    }
    

    public function view($id)
    {
        $appointment = Appointment::with(['user', 'therapist'])->findOrFail($id);
        return response()->json($appointment);
    }
    
public function getAppointments()
{
    $patientId = Auth::id();
    $appointments = Appointment::where('patient_id', $patientId)
                               ->where('status', 'accepted')
                               ->with('therapist')
                               ->get();

    $events = $appointments->map(function ($appointment) {
        $appointmentDate = Carbon::parse($appointment->appointment_date)->format('Y-m-d');
        return [
            'id' => $appointment->id,
            'title' => $appointment->mode,
            'start' => $appointmentDate,
            'extendedProps' => [
                'description' => $appointment->note ?: null,
                'startTime' => $this->combineDateTime($appointmentDate, $appointment->start_time),
                'endTime' => $this->combineDateTime($appointmentDate, $appointment->end_time),
                'patientName' => $appointment->therapist ? 
                    $appointment->therapist->first_name . ' ' . $appointment->therapist->last_name : 
                    'No Therapist',
            ]
        ];
    });

    return response()->json($events);
}


private function combineDateTime($date, $time)
{
    return Carbon::parse($date . ' ' . $time)->format('Y-m-d\TH:i:s');
}

public function getTherapistAppointments()
{
    $therapistId = Auth::id();
    $appointments = Appointment::where('therapist_id', $therapistId)
                               ->where('status', 'accepted')
                               ->with('patient')  // Eager loading patient relationship
                               ->get();

    $events = $appointments->map(function ($appointment) {
        $appointmentDate = Carbon::parse($appointment->appointment_date)->format('Y-m-d');
        return [
            'id' => $appointment->id,
            'title' => $appointment->mode,
            'start' => $appointmentDate,
            'extendedProps' => [
                'description' => $appointment->note ?: null,
                'startTime' => $this->combineDateTime($appointmentDate, $appointment->start_time),
                'endTime' => $this->combineDateTime($appointmentDate, $appointment->end_time),
                'patientName' => $appointment->patient ? 
                    $appointment->patient->first_name . ' ' . $appointment->patient->last_name : 
                    'No Patient', // Assuming your patient model has first_name and last_name fields
            ]
        ];
    });

    return response()->json($events);
}

public function addAppointment(Request $request)  // Remove $appointmentId parameter
{
    // Validate the incoming request data
    $request->validate([
        'date' => [
            'required',
            'date',
            function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->startOfDay() < now()->startOfDay()) {
                    $fail('The appointment date cannot be in the past.');
                }
            },
        ],
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'patient_id' => 'required|exists:users,id',
        'mode' => 'required|in:on-site,teletherapy',
    ]);

    // Check for conflicting appointments
    $conflict = Appointment::where('appointment_date', $request->date)
        ->where(function ($query) use ($request) {
            $query->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
        })
        ->exists();

    if ($conflict) {
        return back()->withErrors(['msg' => 'The selected time slot is already booked.']);
    }

    // Create new appointment
    $appointment = new Appointment();
    $appointment->appointment_date = $request->date;
    $appointment->start_time = $request->start_time;
    $appointment->end_time = $request->end_time;
    $appointment->status = 'accepted';
    $appointment->patient_id = $request->patient_id;
    $appointment->therapist_id = Auth::id(); // Add the current therapist's ID
    $appointment->mode = $request->mode;
    $appointment->save();

    // Optional: Send notifications
    if ($appointment->patient) {
        $appointment->patient->notify(new AcceptedNotification($appointment));
        
        Mail::to($appointment->patient->email)
            ->later(now()->addSeconds(5), new AppointmentAccepted($appointment));
    }

    return redirect()->route('therapist.AppSched')->with('success', 'Appointment added successfully!');
}


public function searchPatients(Request $request)
{
    $patients = DB::table('users')
        ->where('role', 'patient')
        ->select('id', 'first_name', 'middle_name', 'last_name')
        ->get();

    return response()->json($patients);
}



}
