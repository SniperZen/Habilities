<?php

namespace App\Http\Controllers;
use App\Models\Appointment; // Ensure you import the Appointment model
use Illuminate\Support\Facades\Auth; // For authentication
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Feedback; // Ensure you import the Appointment model
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentCanceled;
use App\Mail\AppointmentAccepted;
use App\Mail\AppointmentUpdated;
use App\Http\Controllers\Controller;
use App\Notifications\AcceptedNotification;
use App\Notifications\TherapistFeedbackNotification;
use App\Notifications\AppointmentCanceledNotification;
use App\Notifications\AppointmentUpdatedNotification;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\BusinessSetting;
use App\Models\PatientFeedback;
use App\Notifications\NewPatientFeedbackNotification;


class TherapistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications; // Fetch all notifications
        
        // Fetch accepted appointments, limit to 5
        $acceptedAppointments = DB::table('appointments')
            ->join('users', 'appointments.patient_id', '=', 'users.id')
            ->select('appointments.*', 'users.first_name', 'users.middle_name', 'users.last_name')
            ->where('appointments.therapist_id', Auth::id())
            ->where('appointments.status', 'accepted')
            ->limit(5)
            ->get();
    
        return view('therapist.dash', compact('notifications', 'acceptedAppointments'));
    }
    
 
    public function getAppointmentFilters()
{
    $appointments = DB::table('appointments')
        ->join('users', 'appointments.patient_id', '=', 'users.id')
        ->select('appointments.*', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.profile_image')
        ->where('appointments.therapist_id', Auth::id())
        ->where('appointments.status', 'pending')
        ->get();

    return response()->json($appointments);
}


    public function AppReq2($id)
    {
        $appointment = DB::table('appointments')
            ->join('users', 'appointments.patient_id', '=', 'users.id')
            ->select('appointments.*', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.profile_image')
            ->where('appointments.id', $id)
            ->first();
    
        return view('therapist.AppReq2', compact('appointment'));
    }
    
    public function AppSched(Request $request)
    {
        $acceptedFilter = $request->input('accepted_filter', 'all_upcoming');
        $historyFilter = $request->input('history_filter', 'all');
    
        // Query for accepted appointments
        $acceptedQuery = DB::table('appointments')
            ->join('users', 'appointments.patient_id', '=', 'users.id')
            ->join('users as therapists', 'appointments.therapist_id', '=', 'therapists.id') 
            ->select('appointments.*', 'users.first_name', 'users.middle_name', 'users.last_name', 'therapists.teletherapist_link')
            ->where('appointments.therapist_id', Auth::id())
            ->where('appointments.status', 'accepted');
    
        // Apply filter for accepted appointments
        $acceptedQuery = $this->applyAcceptedFilter($acceptedQuery, $acceptedFilter);
    
        // Query for history appointments
        $historyQuery = DB::table('appointments')
            ->join('users', 'appointments.patient_id', '=', 'users.id')
            ->select('appointments.*', 'users.first_name', 'users.middle_name', 'users.last_name')
            ->where('appointments.therapist_id', Auth::id())
            ->whereIn('appointments.status', ['finished', 'declined']);
    
        // Apply filter for history appointments
        $historyQuery = $this->applyHistoryFilter($historyQuery, $historyFilter);
    
        $acceptedAppointments = $acceptedQuery->orderBy('appointments.appointment_date', 'asc')
                                              ->orderBy('appointments.start_time', 'asc')
                                              ->get();
    
        $historyAppointments = $historyQuery->orderBy('appointments.appointment_date', 'desc')
                                            ->orderBy('appointments.start_time', 'desc')
                                            ->get();
    
        return view('therapist.AppSched', compact('acceptedAppointments', 'historyAppointments', 'acceptedFilter', 'historyFilter'));
    }
    
    private function applyAcceptedFilter($query, $filter)
    {
        switch ($filter) {
            case 'today':
                return $query->whereDate('appointments.appointment_date', Carbon::today());
            case 'tomorrow':
                return $query->whereDate('appointments.appointment_date', Carbon::tomorrow());
            case 'this_week':
                return $query->whereBetween('appointments.appointment_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            case 'next_week':
                return $query->whereBetween('appointments.appointment_date', [Carbon::now()->addWeek()->startOfWeek(), Carbon::now()->addWeek()->endOfWeek()]);
            case 'this_month':
                return $query->whereYear('appointments.appointment_date', Carbon::now()->year)
                             ->whereMonth('appointments.appointment_date', Carbon::now()->month);
            case 'next_month':
                return $query->whereYear('appointments.appointment_date', Carbon::now()->addMonth()->year)
                             ->whereMonth('appointments.appointment_date', Carbon::now()->addMonth()->month);
            case 'all_upcoming':
            default:
                return $query->where('appointments.appointment_date', '>=', Carbon::today());
        }
    }
    
    private function applyHistoryFilter($query, $filter)
    {
        switch ($filter) {
            case 'today':
                return $query->whereDate('appointments.appointment_date', Carbon::today());
            case 'yesterday':
                return $query->whereDate('appointments.appointment_date', Carbon::yesterday());
            case 'last_7_days':
                return $query->where('appointments.appointment_date', '>=', Carbon::now()->subDays(7));
            case 'last_14_days':
                return $query->where('appointments.appointment_date', '>=', Carbon::now()->subDays(14));
            case 'last_21_days':
                return $query->whereYear('appointments.appointment_date', Carbon::now()->subDays(21));
            case 'last_28_days':
                return $query->whereYear('appointments.appointment_date', Carbon::now()->subDays(28));
            case 'all':
            default:
                return $query;
        }
    }
    


    public function addAppointment(Request $request, $appointmentId)
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
        ]);
    
        // Find the existing appointment with eager loaded relationships
        $appointment = Appointment::with(['patient', 'therapist'])->findOrFail($appointmentId);
    
        // Check for conflicting appointments
        $conflict = Appointment::where('appointment_date', $request->date)
            ->where('id', '!=', $appointmentId)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();
    
        if ($conflict) {
            return back()->withErrors(['msg' => 'The selected time slot is already booked.']);
        }
    
        // Update the appointment details
        $appointment->appointment_date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->status = 'accepted';
        $appointment->updated_at = now();
    
        // Save the changes to the database
        $appointment->save();
    
        // Refresh the model to ensure we have the latest data with relationships
        $appointment->refresh();
    
        // Send notification to the patient
        $appointment->patient->notify(new AcceptedNotification($appointment));
    
        $patient = $appointment->user; // Get the patient
        $patientEmail = $patient->email; // Get the patient's email
    
        // Send email notification
        Mail::to($appointment->patient->email)
            ->later(now()->addSeconds(5), new AppointmentAccepted($appointment));
            
    
        // Redirect with success message
        return redirect()->route('therapist.AppSched')->with('success', 'Appointment updated successfully.');
    }
    
    
    
    public function declineRequest($id)
{
    $appointment = Appointment::findOrFail($id);
    
    // Check if the appointment status is already declined or finished
    if ($appointment->status === 'declined' || $appointment->status === 'finished') {
        return redirect()->back()->with('error', 'Cannot decline this appointment.');
    }

    $appointment->status = 'declined';
    $appointment->save();

    $patient = $appointment->user; // Get the patient
    $patientEmail = $patient->email; // Get the patient's email

    // Send email notification
    Mail::to($patientEmail)->later(now()->addSeconds(5), new AppointmentCanceled($appointment));

    // Send in-app notification
    $patient->notify(new AppointmentCanceledNotification($appointment));

    return redirect()->route('therapist.AppReq')->with('success', 'Appointment request declined successfully.');
}
public function cancelAppointment(Request $request, $id)
{
    try {
        $appointment = Appointment::findOrFail($id);
        
        // Update appointment with cancellation details
        $appointment->status = 'declined';
        $appointment->cancellation_reason = $request->cancellationReason;
        $appointment->cancellation_note = $request->cancellationNote;
        $appointment->save();

        $patient = $appointment->user; // Get the patient
        $patientEmail = $patient->email; // Get the patient's email

        Mail::to($patientEmail)->later(now()->addSeconds(5), new AppointmentCanceled($appointment));
    
        // Send in-app notification
        $patient->notify(new AppointmentCanceledNotification($appointment));

        return response()->json(['success' => true, 'message' => 'Appointment canceled successfully.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => 'An error occurred while canceling the appointment.']);
    }
}




public function finishAppointment(Request $request, $id)
{
    $appointment = Appointment::findOrFail($id);
    
    // Update the status of the appointment
    $appointment->status = 'finished';
    $appointment->save();

    // Return a JSON response
    return response()->json(['success' => true, 'message' => 'Appointment status updated to finished.']);
}





public function profile()
{
    $user = Auth::user(); // Get the authenticated therapist's data
    $patients = User::where('usertype', 'user')
                    ->orderBy('last_name')
                    ->get();
    return view('therapist.profile', compact('user', 'patients'));
}

public function edit(){
    return view('therapist.edit');
}

public function chat($id = null){
    $messengerColor = '#7c71a5'; // Using your custom color
    return view('therapist.chat', [
        'id' => $id ?? Auth::id(),
        'messengerColor' => $messengerColor,
        'dark_mode' => false 
    ]);
}

public function inquiry(){
    return view('therapist.inquiry');
}

public function inquirymess(){
    return view('therapist.inquirymess');
}
public function dash()
{
    $user = Auth::user();
    $notifications = $user->notifications;
    
    // Fetch accepted appointments
    $acceptedAppointments = DB::table('appointments')
        ->join('users', 'appointments.patient_id', '=', 'users.id')
        ->select('appointments.*', 'users.first_name', 'users.middle_name', 'users.last_name')
        ->where('appointments.therapist_id', Auth::id())
        ->where('appointments.status', 'accepted')
        ->get();

    // Get count of accepted appointments
    $appointmentCount = DB::table('appointments')
        ->where('therapist_id', Auth::id())
        ->where('status', 'accepted')
        ->count();

    // Get count of pending inquiries
    $inquiriesCount = DB::table('inquiries')
        ->whereNull('completed_at')
        ->count();

    // Get count of patients (users with usertype 'user')
    $patientCount = DB::table('users')
        ->where('usertype', 'user')
        ->where('account_status', 'active')
        ->count();

    return view('therapist.dash', compact(
        'notifications', 
        'acceptedAppointments', 
        'appointmentCount',
        'inquiriesCount',
        'patientCount'
    ));
}



public function feedback(){
    
    return view('therapist.feedback');
}

public function feedback2(){
    return view('therapist.feedback2');
}

public function myHistory(Request $request)
    {
        $therapist = Auth::user();
        $historyFilter = $request->input('history_filter', 'all');

        $query = DB::table('appointments')
            ->join('users', 'appointments.patient_id', '=', 'users.id')
            ->select(
                'appointments.*',
                'users.first_name',
                'users.middle_name',
                'users.last_name'
            )
            ->where('appointments.therapist_id', $therapist->id)
            ->whereIn('appointments.status', ['finished', 'declined'])
            ->orderBy('appointments.appointment_date', 'desc')
            ->orderBy('appointments.start_time', 'desc');

        // Apply date filters
        switch ($historyFilter) {
            case 'today':
                $query->whereDate('appointments.appointment_date', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('appointments.appointment_date', Carbon::yesterday());
                break;
            case 'last_7_days':
                $query->where('appointments.appointment_date', '>=', Carbon::now()->subDays(7));
                break;
            case 'last_14_days':
                $query->where('appointments.appointment_date', '>=', Carbon::now()->subDays(14));
                break;
            case 'last_21_days':
                $query->where('appointments.appointment_date', '>=', Carbon::now()->subDays(21));
                break;
            case 'last_28_days':
                $query->where('appointments.appointment_date', '>=', Carbon::now()->subDays(28));
                break;
        }

        $historyAppointments = $query->get();

        return view('therapist.myHistory', compact('historyAppointments', 'historyFilter'));
    }
public function feedback3(){
    return view('therapist.feedback3');
}
public function showFeedback($id)
{
    $feedback = Feedback::with('recipient')->findOrFail($id);

    return view('therapist.feedback3', compact('feedback'));
}
public function destroy($id)
{
    $feedback = Feedback::findOrFail($id);
    $feedback->delete();

    return redirect()->route('therapist.feedback')->with('success', 'Feedback deleted successfully.');
}

public function notification(){
    return view('therapist.notification');
}

public function   changespass(){
    return view('therapist.changespass');
 }

 public function  feedbackt(){
    return view('therapist.t-feedback');
 }

 public function  help(){
    return view('therapist.t-help');
 }

 public function  about(){
    
    $settings = BusinessSetting::first();
    return view('therapist.t-about', compact('settings'));
 }



 public function searchUsers(Request $request)
 {
     $query = $request->get('query');
 
     // Fetch users with usertype 'user' that match the search query
     $users = User::where('usertype', 'user') // Filter by usertype
         ->where(function($q) use ($query) {
             $q->where('name', 'LIKE', "%{$query}%")
               ->orWhere('email', 'LIKE', "%{$query}%"); // Include other fields if needed
         })
         ->get(['id', 'name']); // Select only the fields needed
 
     return response()->json($users);
 }
 

public function store(Request $request)
{
    $validatedData = $request->validate([
        'recipient_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $feedback = new Feedback();
    $feedback->sender_id = Auth::id();
    $feedback->recipient_id = $validatedData['recipient_id'];
    $feedback->title = $validatedData['title'];
    $feedback->content = $validatedData['content'];
    $feedback->save();

    // Get the therapist (sender) information
    $therapist = Auth::user();

    // Send notification to the recipient
    $recipient = User::find($validatedData['recipient_id']);
    $recipient->notify(new TherapistFeedbackNotification($therapist, $feedback));

    return redirect()->route('therapist.feedback')->with('success', 'Feedback sent successfully and notification delivered.');

}


public function fbview(Request $request)
{
    $query = Feedback::where('sender_id', Auth::id());
    
    // Get the filter value from the request
    $pendingFilter = $request->input('pending_filter', 'all');
    
    // Apply date filters
    switch ($pendingFilter) {
        case 'today':
            $query->whereDate('created_at', Carbon::today());
            break;
        case 'yesterday':
            $query->whereDate('created_at', Carbon::yesterday());
            break;
        case 'last_7_days':
            $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
            break;
        case 'last_14_days':
            $query->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()]);
            break;
        case 'last_21_days':
            $query->whereBetween('created_at', [Carbon::now()->subDays(21), Carbon::now()]);
            break;
        case 'last_28_days':
            $query->whereBetween('created_at', [Carbon::now()->subDays(28), Carbon::now()]);
            break;
        default:
            // 'all' - no filter needed
            break;
    }

    $feedbacks = $query->with('recipient')->orderBy('created_at', 'desc')->get();
    
    return view('therapist.feedback', compact('feedbacks'));
}


public function updateAppointment(Request $request, $appointmentId)
{
    // Validate the incoming request data
    $request->validate([
        'date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    // Find the existing appointment
    $appointment = Appointment::findOrFail($appointmentId);

    // Update the appointment details
    $appointment->appointment_date = $request->date;
    $appointment->start_time = $request->start_time;
    $appointment->end_time = $request->end_time;
    $appointment->status = 'accepted';
    $appointment->updated_at = now();

    // Save the changes to the database
    $appointment->save();

    $patient = $appointment->user; // Get the patient
    $patientEmail = $patient->email; // Get the patient's email

    // Send email notification
    Mail::to($patientEmail)->later(now()->addSeconds(5), new AppointmentUpdated($appointment));

    // Send in-app notification
    $patient->notify(new AppointmentUpdatedNotification($appointment));
    
    // Return JSON response
    return response()->json(['success' => true, 'message' => 'Appointment updated successfully.']);
}



public function AppReq(Request $request)
{
    $filter = $request->input('filter', 'all');
    
    $query = Appointment::query()
        ->join('users', 'appointments.patient_id', '=', 'users.id')
        ->select('appointments.*', 'users.first_name', 'users.middle_name', 'users.last_name')
        ->where('appointments.status', 'pending'); // Only show pending appointments

    switch ($filter) {
        case 'today':
            $query->whereDate('appointments.created_at', Carbon::today());
            break;
        case 'yesterday':
            $query->whereDate('appointments.created_at', Carbon::yesterday());
            break;
        case 'last_7_days':
            $query->where('appointments.created_at', '>=', Carbon::now()->subDays(7));
            break;
        case 'last_14_days':
            $query->where('appointments.created_at', '>=', Carbon::now()->subDays(14));
            break;
        case 'last_21_days':
            $query->where('appointments.created_at', '>=', Carbon::now()->subDays(21));
            break;
        case 'last_28_days':
            $query->where('appointments.created_at', '>=', Carbon::now()->subDays(28));
            break;
        case 'all':
        default:
            // 'all' or any other value will show all pending appointments
            break;
    }

    $appointments = $query->orderBy('appointments.created_at', 'desc')->get();

    return view('therapist.AppReq', compact('appointments', 'filter'));
}

public function stores(Request $request)
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

}
