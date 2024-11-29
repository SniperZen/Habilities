<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment; // Ensure you import the Appointment model
use Illuminate\Support\Facades\Auth; // For authentication
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Carbon\Carbon;
use App\Models\PatientFeedback;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessSetting;
use App\Notifications\NewPatientFeedbackNotification;
use App\Notifications\AppointmentCanceledNotificationPatient;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AppointmentCancelPatient;
use App\Mail\AppointmentCanceled;


class PatientController extends Controller
{
    public function profile()
    {
        $feedback = Feedback::with('sender')
            ->where('recipient_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Fetch the next upcoming appointment for the patient
        $upcomingSession = Appointment::with('therapist')  // Just 'therapist' since it's directly related to User
            ->where('patient_id', Auth::id())
            ->where('appointment_date', '>=', now())
            ->where('status', 'confirmed')
            ->orderBy('appointment_date', 'asc')
            ->first();
    
        return view('patient.profile', compact('feedback', 'upcomingSession'));
    }
    

    public function edit()
    {
        return view('profile.edit');
    }

    public function editprof()
    {
        return view('patient.edit-profile');
    }

    public function inquiry01()
    {
        return view('patient.inquiry01');
    }

    public function inquiry()
    {
        return view('patient.inquiry');
    }

    public function  inquiry2(){
        return view('patient.inquiry2');
     }

     public function  inquiry3(){
        return view('patient.inquiry3');
     }

    public function AppReq()
    {
        $therapists = User::where('usertype', 'therapist')->get();
                // Update missed appointments
                $this->updateAppointmentStatuses();
        return view('patient.ReqApp', compact('therapists'));
    }

    public function  appntmnt(){
        return view('patient.appntmnt');
     }

     public function  feedback(){
        return view('patient.p-feedback');
     }

     public function  help(){
        return view('patient.p-help');
     }

     public function  about(){
        $settings = BusinessSetting::first();
        return view('patient.p-about', compact('settings'));
     }

     public function   changespass(){
        return view('patient.changespass');
     }
     public function myHistory(Request $request)
     {
         $filter = $request->input('history_filter', 'all');
         
         // Start with base query for logged-in patient's appointments
         $query = Appointment::where('patient_id', Auth::id())
             ->whereIn('status', [
                 'finished',
                 'therapist_declined',
                 'patient_declined',
                 'missed',
                 'therapist_canceled',
                 'patient_canceled'
             ])
             ->with('therapist');  // Eager load therapist relationship
     
         // Apply date filters
         switch ($filter) {
             case 'today':
                 $query->whereDate('appointment_date', Carbon::today());
                 break;
             case 'yesterday':
                 $query->whereDate('appointment_date', Carbon::yesterday());
                 break;
             case 'last_7_days':
                 $query->whereBetween('appointment_date', [
                     Carbon::now()->subDays(7)->startOfDay(),
                     Carbon::now()->endOfDay()
                 ]);
                 break;
             case 'last_14_days':
                 $query->whereBetween('appointment_date', [
                     Carbon::now()->subDays(14)->startOfDay(),
                     Carbon::now()->endOfDay()
                 ]);
                 break;
             case 'last_21_days':
                 $query->whereBetween('appointment_date', [
                     Carbon::now()->subDays(21)->startOfDay(),
                     Carbon::now()->endOfDay()
                 ]);
                 break;
             case 'last_28_days':
                 $query->whereBetween('appointment_date', [
                     Carbon::now()->subDays(28)->startOfDay(),
                     Carbon::now()->endOfDay()
                 ]);
                 break;
             case 'all':
             default:
                 // No additional filtering needed
                 break;
         }
     
         // Update missed appointments
         $this->updateAppointmentStatuses();
     
         // Order by appointment date and time in descending order
         $pastAppointments = $query->orderBy('appointment_date', 'desc')
                                  ->orderBy('start_time', 'desc')
                                  ->get();
     
         return view('patient.myHistory', compact('pastAppointments', 'filter'));
     }
     
    public function CompApp($id)
    {
        $therapist = User::find($id);
        
        if (!$therapist) {
            return redirect()->route('patient.AppReq')->with('error', 'Therapist not found.');
        }

        return view('patient.CompApp', compact('therapist'));
    }

    public function waitConf($id)
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return redirect()->route('patient.AppReq')->with('error', 'Appointment not found.');
        }
    
        return view('patient.waitConf', compact('appointment'));
    }
    
    
    

 public function chat()
    {
        $messengerColor = '#8AAEE0'; 
    return view('patient.chat', [
        'id' => Auth::id(),
        'messengerColor' => $messengerColor,
        'dark_mode' => false 
    ]);
    }

 public function dash()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        return view('patient.dash', compact('notifications'));
    }
    
    public function showAppointments(Request $request)
{
    $patientId = Auth::id();
    
    // Base query for accepted appointments with proper time casting
    $acceptedQuery = Appointment::with('therapist')
        ->where('patient_id', $patientId)
        ->where('status', 'accepted')
        ->where(function($query) {
            $query->where('appointment_date', '>', now()->toDateString())
                ->orWhere(function($q) {
                    $q->where('appointment_date', '=', now()->toDateString())
                        ->where('end_time', '>=', now()->format('H:i:s'));
                });
        });

    // Base query for pending appointments
    $pendingQuery = Appointment::with('therapist')
        ->where('patient_id', $patientId)
        ->where('status', 'pending');

    // Add proper time casting
    $acceptedQuery->orderByRaw('CAST(appointment_date AS DATE), CAST(start_time AS TIME)');

    // Rest of your switch statement remains the same, but update the ordering in each case
    switch ($request->input('accepted_filter', 'all_upcoming')) {
        case 'pending_only':
            $acceptedAppointments = collect([]);
            $pendingAppointments = $pendingQuery->get();
            break;
            
        case 'all_upcoming':
            $acceptedAppointments = $acceptedQuery
                ->orderByRaw('CAST(appointment_date AS DATE), CAST(start_time AS TIME)')
                ->get();
            $pendingAppointments = $pendingQuery->get();
            break;
            
        case 'today':
            $acceptedAppointments = $acceptedQuery
                ->whereDate('appointment_date', Carbon::today())
                ->orderByRaw('CAST(start_time AS TIME)')
                ->get();
            $pendingAppointments = collect([]);
            break;
            
            case 'tomorrow':
                $acceptedAppointments = $acceptedQuery
                    ->whereDate('appointment_date', Carbon::tomorrow())
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();
                $pendingAppointments = collect([]);
                break;
                
            case 'this_week':
                $acceptedAppointments = $acceptedQuery
                    ->whereBetween('appointment_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ])
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();
                $pendingAppointments = collect([]);
                break;
                
            case 'next_week':
                $acceptedAppointments = $acceptedQuery
                    ->whereBetween('appointment_date', [
                        Carbon::now()->addWeek()->startOfWeek(),
                        Carbon::now()->addWeek()->endOfWeek()
                    ])
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();
                $pendingAppointments = collect([]);
                break;
                
            case 'this_month':
                $acceptedAppointments = $acceptedQuery
                    ->whereYear('appointment_date', Carbon::now()->year)
                    ->whereMonth('appointment_date', Carbon::now()->month)
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();
                $pendingAppointments = collect([]);
                break;
                
            case 'next_month':
                $nextMonth = Carbon::now()->addMonth();
                $acceptedAppointments = $acceptedQuery
                    ->whereYear('appointment_date', $nextMonth->year)
                    ->whereMonth('appointment_date', $nextMonth->month)
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();
                $pendingAppointments = collect([]);
                break;
                
            default:
                $acceptedAppointments = $acceptedQuery
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();
                $pendingAppointments = collect([]);

    }

    // Before combining collections, ensure time formats are consistent
    $acceptedAppointments = $acceptedAppointments->map(function ($appointment) {
        if ($appointment->start_time) {
            $appointment->start_time = Carbon::parse($appointment->start_time)->format('H:i:s');
        }
        if ($appointment->end_time) {
            $appointment->end_time = Carbon::parse($appointment->end_time)->format('H:i:s');
        }
        return $appointment;
    });

    // Combine collections
    $upcomingAppointments = $acceptedAppointments->concat($pendingAppointments);
        // Update missed appointments
        $this->updateAppointmentStatuses();
    return view('patient.appntmnt', [
        'upcomingAppointments' => $upcomingAppointments,
        'acceptedFilter' => $request->input('accepted_filter', 'all_upcoming'),
        'historyFilter' => $request->input('history_filter', 'all')
    ]);
}

    
public function showAppointmentsDash()
{
    // First update the statuses
    $this->updateAppointmentStatuses();

    // Then get the appointments
    $appointments = Appointment::where('patient_id', Auth::id())
        ->whereIn('status', [
            'finished',
            'therapist_declined',
            'therapist_canceled',
            'patient_declined',
            'missed',
        ])
        ->orderBy('appointment_date', 'desc')
        ->take(5)
        ->get();

    return view('patient.dash', compact('appointments'));
}


    
    private function updateAppointmentStatuses()
    {
        $today = Carbon::today();
        
        $appointments = Appointment::where('status', 'accepted')
            ->whereDate('appointment_date', $today)
            ->get();
        
        foreach ($appointments as $appointment) {
            try {
                $appointmentDate = $appointment->appointment_date;
                $endTime = Carbon::parse($appointment->end_time); // Assuming end_time is stored as a valid datetime string
                
                logger()->info("Processing appointment ID: {$appointment->id}");
                logger()->info("Appointment date: {$appointmentDate}");
                logger()->info("End time: {$endTime}");
    
                // Create a new instance for comparison
                $twoHoursPastEndTime = $endTime->copy()->addHours(2);
                
                // Check if the current time is more than 2 hours past the end_time
                if (now()->isAfter($twoHoursPastEndTime)) {
                    // Update the status to 'missed'
                    $appointment->status = 'missed';
                    $appointment->save();
                    logger()->info("Updated appointment ID: {$appointment->id} to 'missed'");
                } else {
                    logger()->info("Appointment ID: {$appointment->id} is not missed yet.");
                }
            } catch (\Exception $e) {
                logger()->error("Failed to update appointment status for appointment ID: {$appointment->id}. Error: " . $e->getMessage());
            }
        }
    }
        public function notification(){
            return view('patient.notification');
        }

        
    public function store(Request $request)
        {
            $request->validate([
                'feedback' => 'required|string|max:600',
            ]);
        
            $feedback = PatientFeedback::create([
                'user_id' => Auth::id(),
                'feedback' => $request->feedback,
            ]);
        
            // Notify all admin users
            $admins = User::where('usertype', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewPatientFeedbackNotification($feedback));
            }
        
            return redirect()->back()->with('success', 'Thank you for your feedback!');
        }

    public function cancelAppointment(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            
            // Validate the request
            $request->validate([
                'cancellationReason' => 'required|string',
                'cancellationNote' => 'nullable|string',
            ]);

            // Update appointment with cancellation details
            $appointment->status = 'patient_declined';
            $appointment->patient_reason = $request->cancellationReason;
            $appointment->patient_note = $request->cancellationNote;
            $appointment->completion_date = now(); // Add this line to set the completion timestamp
            $appointment->save();

            // Get the therapist
            $therapist = $appointment->therapist;
            $therapistEmail = $therapist->email; 

            // You can uncomment these lines when you have set up your mail and notification system
            Mail::to($therapistEmail)->later(now()->addSeconds(5), new AppointmentCancelPatient($appointment));
            $therapist->notify(new AppointmentCanceledNotificationPatient($appointment));

            return response()->json(['success' => true, 'message' => 'Appointment cancelled successfully']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => 'An error occurred while canceling the appointment'
            ], 500);
        }
    }


}

