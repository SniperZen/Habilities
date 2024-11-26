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
use Illuminate\Http\JsonResponse;


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
        try {
            $patientId = Auth::id();
            $appointments = Appointment::where('patient_id', $patientId)
                                       ->where('status', 'accepted')
                                       ->with('therapist')
                                       ->get();
    
            $events = $appointments->map(function ($appointment) {
                $appointmentDateTime = Carbon::parse($appointment->appointment_date);
                
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->mode,
                    'start' => $appointmentDateTime->format('Y-m-d'),
                    'extendedProps' => [
                        'description' => $appointment->note ?: null,
                        'startTime' => $appointmentDateTime->format('Y-m-d H:i:s'),
                        'endTime' => $appointmentDateTime->addMinutes($appointment->end_time ?? 60)->format('Y-m-d H:i:s'),
                        'patientName' => $appointment->therapist ? 
                            $appointment->therapist->first_name . ' ' . $appointment->therapist->last_name : 
                            'No Therapist',
                    ]
                ];
            });
    
            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch appointments'], 500);
        }
    }
    


private function combineDateTime($date, $time)
{
    return Carbon::parse($date . ' ' . $time)->format('Y-m-d\TH:i:s');
}

public function getTherapistAppointments()
{
    try {
        $therapistId = Auth::id();
        $appointments = Appointment::where('therapist_id', $therapistId)
                                   ->where('status', 'accepted')
                                   ->with('patient')
                                   ->get();

        $events = $appointments->map(function ($appointment) {
            $appointmentDateTime = Carbon::parse($appointment->appointment_date);
            
            return [
                'id' => $appointment->id,
                'title' => $appointment->mode,
                'start' => $appointmentDateTime->format('Y-m-d'),
                'extendedProps' => [
                    'description' => $appointment->note ?: null,
                    'startTime' => $appointmentDateTime->format('Y-m-d H:i:s'),
                    'endTime' => $appointmentDateTime->addMinutes($appointment->end_time ?? 60)->format('Y-m-d H:i:s'),
                    'patientName' => $appointment->patient ? 
                        $appointment->patient->first_name . ' ' . $appointment->patient->last_name : 
                        'No Patient',
                    // Additional useful information for therapists
                    'mode' => $appointment->mode,
                    'status' => $appointment->status,
                    'patientId' => $appointment->patient_id
                ]
            ];
        });

        return response()->json($events);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch appointments'], 500);
    }
}


public function addAppointment(Request $request)
{
    try {
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
            'mode' => 'required|in:on-site,tele-therapy',
        ]);

        $therapistId = Auth::id();
        $patientId = $request->patient_id;
        $appointmentDate = $request->date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        // Check for conflicting appointments for both therapist and patient
        $conflict = Appointment::where('appointment_date', $appointmentDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->where(function ($query) use ($therapistId, $patientId) {
                $query->where('therapist_id', $therapistId)
                      ->orWhere('patient_id', $patientId);
            })
            ->where('status', 'accepted')
            ->exists();

        if ($conflict) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected time slot conflicts with an existing appointment.'
            ], 422);
        }

        // Calculate duration in hours
        $duration = Carbon::parse($startTime)->diffInHours(Carbon::parse($endTime));
        if ($duration < 1 || $duration > 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment duration must be between 1 and 2 hours.'
            ], 422);
        }

        // Create new appointment
        $appointment = new Appointment();
        $appointment->appointment_date = $appointmentDate;
        $appointment->start_time = $startTime;
        $appointment->end_time = $endTime;
        $appointment->status = 'accepted';
        $appointment->patient_id = $patientId;
        $appointment->therapist_id = $therapistId;
        $appointment->mode = $request->mode;
        $appointment->save();

        // Get patient and therapist details
        $patient = User::findOrFail($patientId);
        $therapist = User::findOrFail($therapistId);

        // Send notification to patient
        try {
            $patient->notify(new AppointmentAccepted($appointment));
            
            // Send email with delay to prevent blocking
            Mail::to($patient->email)
                ->later(now()->addSeconds(5), new AppointmentAccepted($appointment));
        } catch (\Exception $e) {
            // Log notification error but don't stop the process
        }

        // Return success response
        return redirect()->route('therapist.AppSched')->with('success', 'Appointment accepted successfully!');


    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while adding the appointment.'
        ], 500);
    }
}

public function searchPatients(Request $request)
{
    $patients = DB::table('users')
        ->where('role', 'patient')
        ->select('id', 'first_name', 'middle_name', 'last_name')
        ->get();

    return response()->json($patients);
}
public function getAcceptedAppointments(): JsonResponse
{
    try {
        // Get request parameters
        $currentTherapistId = Auth::id();
        $patientId = request('patient_id');
        $selectedDate = request('date');

        // Validate required parameters
        if (!$patientId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient ID is required'
            ], 400);
        }

        // Build the query
        $appointments = Appointment::where('status', 'accepted')
            ->where(function($query) use ($currentTherapistId, $patientId) {
                $query->where('therapist_id', $currentTherapistId)
                      ->orWhere('patient_id', $patientId);
            });

        // Add date filter if provided
        if ($selectedDate) {
            $appointments->whereDate('appointment_date', Carbon::parse($selectedDate));
        }

        // Execute query
        $appointments = $appointments->get();

        // Format appointments
        $formattedAppointments = $appointments->map(function ($appointment) {
            return [
                'appointment_details' => [
                    'id' => $appointment->id,
                    'schedule' => [
                        'date' => Carbon::parse($appointment->appointment_date)->format('F d, Y'),
                        'start' => Carbon::parse($appointment->start_time)->format('h:i A'),
                        'end' => Carbon::parse($appointment->end_time)->format('h:i A'),
                    ],
                    'consultation_type' => $appointment->mode,
                    'status' => ucfirst($appointment->status),
                ],
                'participants' => [
                    'patient' => [
                        'id' => $appointment->patient_id,
                        'name' => $appointment->patient->name ?? 'Unknown Patient', // Add patient name if needed
                    ],
                    'therapist' => [
                        'id' => $appointment->therapist_id,
                        'name' => $appointment->therapist->name ?? 'Unknown Therapist', // Add therapist name if needed
                    ]
                ],
                'notes' => $appointment->note ?? 'No notes available',
                'metadata' => [
                    'created' => [
                        'date' => Carbon::parse($appointment->created_at)->format('F d, Y'),
                        'time' => Carbon::parse($appointment->created_at)->format('h:i A'),
                    ],
                    'last_updated' => [
                        'date' => Carbon::parse($appointment->updated_at)->format('F d, Y'),
                        'time' => Carbon::parse($appointment->updated_at)->format('h:i A'),
                    ]
                ]
            ];
        });

        // Return success response
        return response()->json([
            'status' => 'success',
            'timestamp' => now(),
            'total_accepted_appointments' => $appointments->count(),
            'appointments' => $formattedAppointments,
            'therapist_id' => $currentTherapistId,
            'filters' => [
                'date' => $selectedDate ? Carbon::parse($selectedDate)->format('F d, Y') : null,
                'patient_id' => $patientId
            ]
        ]);

    } catch (\Exception $e) {
        // Log the error for debugging
        
        // Return error response
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching appointments',
            'debug' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
public function getAcceptedAppointments2(): JsonResponse
{
    try {
        $currentTherapistId = Auth::id();
        $patientId = request('patient_id');
        $selectedDate = request('date');

        if (!$patientId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient ID is required'
            ], 400);
        }

        // Modified query to properly filter appointments
        $appointments = Appointment::where('status', 'accepted')
            ->whereDate('appointment_date', Carbon::parse($selectedDate))
            ->where(function($query) use ($patientId) {
                // Get appointments where this patient is involved
                // OR appointments that might conflict with the time slot
                $query->where('patient_id', $patientId)
                      ->orWhere(function($q) use ($patientId) {
                          $q->where('patient_id', '!=', $patientId)
                            ->whereNotNull('patient_id');
                      });
            })
            ->get();

        // Rest of your code remains the same...
        $formattedAppointments = $appointments->map(function ($appointment) {
            return [
                'appointment_details' => [
                    'id' => $appointment->id,
                    'schedule' => [
                        'date' => Carbon::parse($appointment->appointment_date)->format('F d, Y'),
                        'start' => Carbon::parse($appointment->start_time)->format('h:i A'),
                        'end' => Carbon::parse($appointment->end_time)->format('h:i A'),
                    ],
                    'consultation_type' => $appointment->mode,
                    'status' => ucfirst($appointment->status),
                ],
                'participants' => [
                    'patient' => [
                        'id' => $appointment->patient_id,
                        'name' => $appointment->patient->name ?? 'Unknown Patient',
                    ],
                    'therapist' => [
                        'id' => $appointment->therapist_id,
                        'name' => $appointment->therapist->name ?? 'Unknown Therapist',
                    ]
                ],
                'notes' => $appointment->note ?? 'No notes available',
                'metadata' => [
                    'created' => [
                        'date' => Carbon::parse($appointment->created_at)->format('F d, Y'),
                        'time' => Carbon::parse($appointment->created_at)->format('h:i A'),
                    ],
                    'last_updated' => [
                        'date' => Carbon::parse($appointment->updated_at)->format('F d, Y'),
                        'time' => Carbon::parse($appointment->updated_at)->format('h:i A'),
                    ]
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'timestamp' => now(),
            'total_accepted_appointments' => $appointments->count(),
            'appointments' => $formattedAppointments,
            'therapist_id' => $currentTherapistId,
            'filters' => [
                'date' => $selectedDate ? Carbon::parse($selectedDate)->format('F d, Y') : null,
                'patient_id' => $patientId
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching appointments',
            'debug' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}



}
