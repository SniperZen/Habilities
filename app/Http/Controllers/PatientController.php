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
             ->whereIn('status', ['finished', 'declined'])
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
                 $query->whereDate('appointment_date', '>=', Carbon::now()->subDays(7));
                 break;
             case 'last_14_days':
                 $query->whereDate('appointment_date', '>=', Carbon::now()->subDays(14));
                 break;
             case 'last_21_days':
                 $query->whereDate('appointment_date', '>=', Carbon::now()->subDays(21));
                 break;
             case 'last_28_days':
                 $query->whereDate('appointment_date', '>=', Carbon::now()->subDays(28));
                 break;

             case 'all':
             default:
                 // No additional filtering needed
                 break;
         }
 
         // Order by status (pending first), then by appointment date and time
         $pastAppointments = $query->orderBy('appointment_date', 'desc')
                                    ->orderBy('start_time', 'desc')
                                    ->get();
                                   
 
         return view('patient.myHistory', compact('pastAppointments'));
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
        
        // Base query for accepted appointments
        $acceptedQuery = Appointment::with('therapist')
            ->where('patient_id', $patientId)
            ->where('status', 'accepted')
            ->where('appointment_date', '>=', now());
    
        // Base query for pending appointments
        $pendingQuery = Appointment::with('therapist')
            ->where('patient_id', $patientId)
            ->where('status', 'pending');
    
        // Apply date filters
        switch ($request->input('accepted_filter', 'all_upcoming')) {
            case 'pending_only':
                // Only show pending appointments
                $acceptedAppointments = collect([]);
                $pendingAppointments = $pendingQuery->get();
                break;
                
            case 'all_upcoming':
                // Show both accepted and pending
                $acceptedAppointments = $acceptedQuery
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->get();
                $pendingAppointments = $pendingQuery->get();
                break;
                
            case 'today':
                $acceptedAppointments = $acceptedQuery
                    ->whereDate('appointment_date', Carbon::today())
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
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
    
        // Combine collections, ensuring pending appointments are always at the bottom
        $upcomingAppointments = $acceptedAppointments->concat($pendingAppointments);
    
        return view('patient.appntmnt', [
            'upcomingAppointments' => $upcomingAppointments,
            'acceptedFilter' => $request->input('accepted_filter', 'all_upcoming'),
            'historyFilter' => $request->input('history_filter', 'all')
        ]);
    }
    
    
    public function showAppointmentsDash()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->whereIn('status', ['finished', 'declined'])
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();
    
        return view('patient.dash', compact('appointments'));
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
            $appointment->status = 'declined';
            $appointment->patient_reason = $request->cancellationReason;
            $appointment->patient_note = $request->cancellationNote;
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

