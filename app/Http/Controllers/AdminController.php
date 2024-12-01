<?php

namespace App\Http\Controllers;

use App\Models\User; // Assuming you have a User model
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PatientFeedback;
use App\Models\UserLogin;
use App\Models\UserLogout;
use App\Models\Inquiry;
use App\Models\Feedback;
use App\Models\BusinessSetting;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Services\EmailVerificationNotificationService; // Add this import

class AdminController extends Controller
{

    public function index()
    {
        return view('layouts.admin');
    }
    // Controller method
    public function showDashboard()
    {
        $therapists = User::where('usertype', 'therapist')->get();
    
        return view('admin.dash', compact('therapists'));
    }

    public function dash()
    {
        return view('admin.dash');
    }
    public function usersTherapist()
    {
        $therapists = User::where('usertype', 'therapist')
            ->get()
            ->map(function($therapist) {
                // Format the date_of_birth if it exists
                if ($therapist->date_of_birth) {
                    $therapist->date_of_birth = date('Y-m-d', strtotime($therapist->date_of_birth));
                }
                return $therapist;
            });
    
        return view('admin.usersTherapist', compact('therapists'));
    }
    
    public function usersPatient()
    {
        $patients = User::where('usertype', 'user')->get();
        return view('admin.usersPatient', compact('patients'));
    }    
    public function report()
    {
        return view('admin.report');
    }

    public function appointmentr(Request $request)
    {
        $query = Appointment::query()
            ->with(['patient', 'therapist']);
    
        // Apply filters for both real-time search and filter submission
        if ($request->has('mode') && $request->mode != 'all') {
            $query->where('mode', $request->mode);
        }
    
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
    
        if ($request->start_date) {
            $query->whereDate('appointment_date', '>=', $request->start_date);
        }
    
        if ($request->end_date) {
            $query->whereDate('appointment_date', '<=', $request->end_date);
        }
    
        // Apply search if search term exists
        if ($request->has('search_name') && $request->search_name) {
            $query->where(function($q) use ($request) {
                $q->whereHas('patient', function($sq) use ($request) {
                    $sq->where('name', 'LIKE', '%' . $request->search_name . '%');
                })->orWhereHas('therapist', function($sq) use ($request) {
                    $sq->where('name', 'LIKE', '%' . $request->search_name . '%');
                });
            });
        }
    
        $appointments = $query->orderBy('appointment_date', 'desc')
                             ->orderBy('start_time', 'asc')
                             ->get();
    
        if ($request->ajax()) {
            return response()->json(['appointments' => $appointments]);
        }
    
        return view('admin.appointmentr', compact('appointments'));
    }
    
    
    public function inquiryr()
    {
        return view('admin.inquiryr');
    }
    public function otfr(Request $request)
    {
        $query = Feedback::query()
            ->with(['sender', 'recipient']);
    
        // Apply date filters if they exist
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }
    
        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }
    
        // Apply name or diagnosis search if provided
        if ($request->filled('search_name')) {
            $searchTerm = $request->search_name;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('sender', function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhereHas('recipient', function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%'); // Add diagnosis search
            });
        }
    
        $feedbacks = $query->orderBy('created_at', 'desc')->get();
    
        if ($request->ajax()) {
            return response()->json([
                'feedbacks' => $feedbacks
            ]);
        }
    
        return view('admin.otfr', compact('feedbacks'));
    }
    
    public function systemfeedbackr(Request $request)
    {
        $query = PatientFeedback::with('user');
    
        // Apply date filters only if the request is from the Apply Filter button
        if ($request->filled(['start_date', 'end_date'])) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }
    
        // Apply name search (this works in real-time)
        if ($request->filled('search_name')) {
            $searchTerm = $request->search_name;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }
    
        $feedbacks = $query->orderBy('created_at', 'desc')->get();
    
        if ($request->ajax()) {
            return response()->json([
                'data' => $feedbacks,
                'status' => 'success'
            ]);
        }
    
        return view('admin.systemfeedbackr', compact('feedbacks'));
    }
    
    public function activitylogs(Request $request)
    {
        // Create base query for logins
        $loginQuery = DB::table('user_logins as l')
            ->join('users as u', 'l.user_id', '=', 'u.id')
            ->select(
                'u.id', // Changed from l.id to u.id
                'l.user_id',
                'u.name',
                'u.usertype',
                'l.login_at as timestamp',
                DB::raw("'Login' as activity")
            );
    
        // Create base query for logouts
        $logoutQuery = DB::table('user_logouts as lo')
            ->join('users as u', 'lo.user_id', '=', 'u.id')
            ->select(
                'u.id', // Changed from lo.id to u.id
                'lo.user_id',
                'u.name',
                'u.usertype',
                'lo.logged_out_at as timestamp',
                DB::raw("'Logout' as activity")
            );
    
        // Apply filters
        if ($request->filled(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            
            $loginQuery->whereBetween('l.login_at', [$startDate, $endDate]);
            $logoutQuery->whereBetween('lo.logged_out_at', [$startDate, $endDate]);
        }
    
        if ($request->filled('usertype') && $request->usertype !== 'all') {
            $loginQuery->where('u.usertype', $request->usertype);
            $logoutQuery->where('u.usertype', $request->usertype);
        }
    
        if ($request->filled('activity') && $request->activity !== 'all') {
            if ($request->activity === 'login') {
                $logoutQuery->whereRaw('1 = 0'); // Exclude logouts
            } elseif ($request->activity === 'logout') {
                $loginQuery->whereRaw('1 = 0'); // Exclude logins
            }
        }
    
        // Apply search filter if present
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $loginQuery->where('u.name', 'LIKE', $searchTerm);
            $logoutQuery->where('u.name', 'LIKE', $searchTerm);
        }
    
        // Combine and sort results
        $activities = $loginQuery->union($logoutQuery)
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($activity) {
                // Format ID based on usertype
                $prefix = match ($activity->usertype) {
                    'user' => 'P-',
                    'admin' => 'A-',
                    'therapist' => 'T-',
                    default => ''
                };
                
                return [
                    'formatted_id' => $prefix . str_pad($activity->id, 4, '0', STR_PAD_LEFT),
                    'name' => $activity->name,
                    'usertype' => $activity->usertype,
                    'activity' => $activity->activity,
                    'date' => Carbon::parse($activity->timestamp)->format('Y-m-d'),
                    'time' => Carbon::parse($activity->timestamp)->format('h:i A')
                ];
            });
    
        if ($request->ajax()) {
            return response()->json(['data' => $activities]);
        }
    
        return view('admin.activitylogs', ['activities' => $activities]);
    }
    
    

    public function therapycenter()
    {
        $settings = BusinessSetting::first();
        return view('admin.therapycenter', compact('settings'));
    }

    public function editTCenter()
{
    $settings = BusinessSetting::first();
    return view('admin.editTCenter', compact('settings'));
}

public function updateCenter(Request $request)
{
    $businessHours = [];
    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    
    foreach ($days as $day) {
        $businessHours[$day] = [
            'start_time' => $request->input("hours.{$day}.start_time"),
            'end_time' => $request->input("hours.{$day}.end_time"),
            'is_closed' => $request->boolean("hours.{$day}.is_closed"),
            'is_teletherapy' => $request->boolean("hours.{$day}.is_teletherapy") // Add this line
        ];
    }

    BusinessSetting::updateOrCreate(
        ['id' => 1],
        [
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'business_hours' => $businessHours
        ]
    );

    return response()->json(['message' => 'Settings updated successfully']);
}



    public function chat()
    {
    $messengerColor = '#A6E999'; 
    return view('admin.chat', [
        'id' => Auth::id(),
        'messengerColor' => $messengerColor,
        'dark_mode' => false 
    ]);
    }


    public function deactivate($id)
{
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Update the account status
    $user->account_status = 'inactive';

    // Save the changes
    if ($user->save()) {
        return redirect()->back()->with('success', 'Account deactivated successfully.');
    } else {
        return redirect()->back()->with('error', 'Failed to deactivate account.');
    }
}

public function activate($id)
{
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Update the account status
    $user->account_status = 'active';

    // Save the changes
    if ($user->save()) {
        return redirect()->back()->with('success', 'Account activated successfully.');
    } else {
        return redirect()->back()->with('error', 'Failed to activate account.');
    }
}

public function updateUsertype(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'usertype' => 'required|in:therapist,user'
    ]);

    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Update the usertype
    $user->usertype = $request->input('usertype');

    // Save the changes
    if ($user->save()) {
        return redirect()->back()->with('success', 'User type updated successfully.');
    } else {
        return redirect()->back()->with('error', 'Failed to update user type.');
    }
}
public function updateUser(Request $request)
{
    // Validate the request
    $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'date_of_birth' => 'nullable|date',
        'gender' => 'required|in:male,female,other',
        'contact_number' => 'nullable|string|max:20',
        'specialization' => 'nullable|string|max:255',
        'home_address' => 'nullable|string|max:255',
        'availability' => 'nullable|array',
        'guardian_name' => 'nullable|string|max:255',
        'guardian_role' => 'nullable|string|max:255',
        'other_guardian_role' => 'nullable|string|max:255', // Add this line
    ]);

    // Use the hidden field to find the user by ID
    $user = User::find($request->input('user_id'));
    
    // Check if the user exists
    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Update the user information
    $user->first_name = $request->input('first_name');
    $user->middle_name = $request->input('middle_name');
    $user->last_name = $request->input('last_name');
    $user->date_of_birth = $request->input('date_of_birth');
    $user->gender = $request->input('gender');
    $user->contact_number = $request->input('contact_number');
    $user->specialization = $request->input('specialization');
    $user->home_address = $request->input('home_address');
    $user->availability = implode(',', $request->input('availability', []));
    
    // Handle guardian information
    $user->guardian_name = $request->input('guardian_name');
    
    // Handle guardian role - if "other" is selected, use the specified role
    if ($request->input('guardian_role') === 'other') {
        $user->guardian_role = $request->input('other_guardian_role');
    } else {
        $user->guardian_role = $request->input('guardian_role');
    }

    // Save the changes
    if ($user->save()) {
        return redirect()->back()->with('success', 'User information updated successfully.');
    } else {
        return redirect()->back()->with('error', 'Failed to update user information.');
    }
}


public function getDashboardCounts()
{
    // Existing counts
    $currentActiveUsers = User::where('account_status', 'active')->count();
    $lastMonthActiveUsers = User::where('account_status', 'active')
        ->where('created_at', '<=', Carbon::now()->subMonth())
        ->count();
    
    $userGrowth = 0;
    if ($lastMonthActiveUsers > 0) {
        $userGrowth = (($currentActiveUsers - $lastMonthActiveUsers) / $lastMonthActiveUsers) * 100;
    }

    // Get current month's successful visits
    $currentSuccessfulVisits = Appointment::where('mode', 'on-site')
        ->where('status', 'finished')
        ->whereMonth('created_at', now()->month)
        ->count();

    // Get last month's successful visits
    $lastMonthSuccessfulVisits = Appointment::where('mode', 'on-site')
        ->where('status', 'finished')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->count();

    // Calculate visits growth
    $visitsGrowth = 0;
    if ($lastMonthSuccessfulVisits > 0) {
        $visitsGrowth = (($currentSuccessfulVisits - $lastMonthSuccessfulVisits) / $lastMonthSuccessfulVisits) * 100;
    }

    return response()->json([
        'active_users' => $currentActiveUsers,
        'user_growth' => round($userGrowth, 2),
        'new_users' => User::where('created_at', '>=', Carbon::now()->subMonth())->count(),
        'new_user_growth' => 0,
        'supervised_accounts' => User::where('account_type', 'child')->count(),
        'successful_visits' => $currentSuccessfulVisits,
        'visits_growth' => round($visitsGrowth, 2)
    ]);
}




    public function getOnsiteAppointmentsData(Request $request)
    {
        $filter = $request->input('filter', 'monthly');
        $currentYear = now()->year;

        switch ($filter) {
            case 'monthly':
                $data = DB::table('appointments')
                    ->select(DB::raw('MONTH(created_at) as period'), DB::raw('COUNT(*) as count'))
                    ->where('mode', 'on-site')
                    ->whereYear('created_at', $currentYear)
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

                $chartData = collect(range(1, 12))->map(function ($month) use ($data) {
                    $count = $data->firstWhere('period', $month)?->count ?? 0;
                    return [
                        'label' => Carbon::create()->month($month)->format('M'),
                        'value' => $count
                    ];
                });
                break;

            case 'weekly':
                $data = DB::table('appointments')
                    ->select(DB::raw('WEEK(created_at) as period'), DB::raw('COUNT(*) as count'))
                    ->where('mode', 'on-site')
                    ->whereYear('created_at', $currentYear)
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

                $chartData = collect(range(1, 52))->map(function ($week) use ($data) {
                    $count = $data->firstWhere('period', $week)?->count ?? 0;
                    return [
                        'label' => "Week {$week}",
                        'value' => $count
                    ];
                });
                break;

            case 'yearly':
                $data = DB::table('appointments')
                    ->select(DB::raw('YEAR(created_at) as period'), DB::raw('COUNT(*) as count'))
                    ->where('mode', 'on-site')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

                $chartData = $data->map(function ($item) {
                    return [
                        'label' => $item->period,
                        'value' => $item->count
                    ];
                });
                break;

            default:
                return response()->json(['error' => 'Invalid filter'], 400);
        }

        return response()->json([
            'labels' => $chartData->pluck('label'),
            'values' => $chartData->pluck('value')
        ]);
    }

    public function getTeleAppointmentsData(Request $request)
    {
        $filter = $request->input('filter', 'monthly');
        $currentYear = now()->year;

        switch ($filter) {
            case 'monthly':
                $data = DB::table('appointments')
                    ->select(DB::raw('MONTH(created_at) as period'), DB::raw('COUNT(*) as count'))
                    ->where('mode', 'tele-therapy')
                    ->whereYear('created_at', $currentYear)
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

                $chartData = collect(range(1, 12))->map(function ($month) use ($data) {
                    $count = $data->firstWhere('period', $month)?->count ?? 0;
                    return [
                        'label' => Carbon::create()->month($month)->format('M'),
                        'value' => $count
                    ];
                });
                break;

            case 'weekly':
                $data = DB::table('appointments')
                    ->select(DB::raw('WEEK(created_at) as period'), DB::raw('COUNT(*) as count'))
                    ->where('mode', 'tele-therapy')
                    ->whereYear('created_at', $currentYear)
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

                $chartData = collect(range(1, 52))->map(function ($week) use ($data) {
                    $count = $data->firstWhere('period', $week)?->count ?? 0;
                    return [
                        'label' => "Week {$week}",
                        'value' => $count
                    ];
                });
                break;

            case 'yearly':
                $data = DB::table('appointments')
                    ->select(DB::raw('YEAR(created_at) as period'), DB::raw('COUNT(*) as count'))
                    ->where('mode', 'tele-therapy')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

                $chartData = $data->map(function ($item) {
                    return [
                        'label' => $item->period,
                        'value' => $item->count
                    ];
                });
                break;

            default:
                return response()->json(['error' => 'Invalid filter'], 400);
        }

        return response()->json([
            'labels' => $chartData->pluck('label'),
            'values' => $chartData->pluck('value')
        ]);
    }

    public function showReport(Request $request)
    {
        $feedbacks = PatientFeedback::with('user')->orderBy('created_at', 'desc')->get();
        $highlightFeedbackId = $request->query('feedback_id');
    
        $userLogins = UserLogin::with('user')->get()->map(function ($login) {
            return [
                'userId' => $login->user->id, // Add user ID
                'timestamp' => $login->login_at,
                'name' => $login->user->name,
                'action' => 'login',
                'userType' => $login->user->usertype,
            ];
        });
    
        $userLogouts = UserLogout::with('user')->get()->map(function ($logout) {
            return [
                'userId' => $logout->user->id, // Add user ID
                'timestamp' => $logout->logged_out_at,
                'name' => $logout->user->name,
                'action' => 'logout',
                'userType' => $logout->user->usertype,
            ];
        });
    
        // Combine logs
        $activityLogs = $userLogins->merge($userLogouts);
    
        // Apply date filtering
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
    
            $activityLogs = $activityLogs->filter(function ($log) use ($startDate, $endDate) {
                return $log['timestamp'] >= $startDate && $log['timestamp'] <= $endDate;
            });
        }
    
        // Apply user type filtering
        if ($request->has('userType') && $request->input('userType') !== 'all') {
            $userType = $request->input('userType');
    
            $activityLogs = $activityLogs->filter(function ($log) use ($userType) {
                return $log['userType'] === $userType;
            });
        }
    
        // Apply specific user filtering by name
        if ($request->has('specificName') && !empty($request->input('specificName'))) {
            $specificName = strtolower($request->input('specificName'));
            $activityLogs = $activityLogs->filter(function ($log) use ($specificName) {
                return strpos(strtolower($log['name']), $specificName) !== false; // Use strpos for partial matches
            });
        }

        // Apply specific user filtering by ID
        if ($request->has('specificID') && !empty($request->input('specificID'))) {
            $specificID = $request->input('specificID');
            $activityLogs = $activityLogs->filter(function ($log) use ($specificID) {
                return strpos($log['userId'], $specificID) !== false; // Check if specificID is part of the ID
            });
        }

        // Sort by timestamp in descending order
        $activityLogs = $activityLogs->sortByDesc('timestamp');
    
        return view('admin.report', compact('feedbacks', 'highlightFeedbackId', 'activityLogs'));
    }
    
    
    
    

    
    public function systemUsage()
    {
        // Daily visits
        $daily = UserLogin::selectRaw('DATE(login_at) as date, COUNT(DISTINCT user_id) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        // Weekly visits
        $weekly = UserLogin::selectRaw('YEARWEEK(login_at) as week, COUNT(DISTINCT user_id) as count')
            ->groupBy('week')
            ->orderBy('week')
            ->get();
    
        // Monthly visits
        $monthly = UserLogin::selectRaw('DATE_FORMAT(login_at, "%Y-%m") as month, COUNT(DISTINCT user_id) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        // Calculate growth percentages for daily, weekly, and monthly visits
        $dailyGrowth = $this->calculateGrowth($daily);
        $weeklyGrowth = $this->calculateGrowth($weekly);
        $monthlyGrowth = $this->calculateGrowth($monthly);
    
        return response()->json([
            'daily' => $daily,
            'daily_growth' => $dailyGrowth,
            'weekly' => $weekly,
            'weekly_growth' => $weeklyGrowth,
            'monthly' => $monthly,
            'monthly_growth' => $monthlyGrowth
        ]);
    }
    
    private function calculateGrowth($data)
    {
        if (count($data) < 2) {
            return 0; // Not enough data to calculate growth
        }
    
        $latestCount = $data->last()->count; // Most recent count
        $previousCount = $data->slice(-2, 1)->first()->count; // Count before the latest
    
        return $previousCount > 0 ? (($latestCount - $previousCount) / $previousCount) * 100 : 0;
    }
    
    public function showInquiries(Request $request)
    {
        $query = Inquiry::with('user')
            ->select('inquiries.*', 'users.name as patient_name')
            ->join('users', 'inquiries.user_id', '=', 'users.id');
    
        // Clear functionality
        if ($request->has('clear')) {
            return redirect()->route('admin.inquiryr');
        }
    
        // Search functionality
        if ($request->search_name) {
            $query->where(function($q) use ($request) {
                $q->where('users.name', 'LIKE', '%' . $request->search_name . '%')
                  ->orWhere('inquiries.concerns', 'LIKE', '%' . $request->search_name . '%');
            });
        }
    
        // Status filter
        if ($request->status && $request->status !== 'all') {
            if ($request->status === 'pending') {
                $query->whereNull('completed_at');
            } elseif ($request->status === 'completed') {
                $query->whereNotNull('completed_at');
            }
        }
    
        // Date range filter
        if ($request->start_date) {
            $query->whereDate('inquiries.created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('inquiries.created_at', '<=', $request->end_date);
        }
    
        $inquiries = $query->orderBy('inquiries.created_at', 'desc')->get();
    
        if ($request->ajax()) {
            return response()->json(['inquiries' => $inquiries]);
        }
    
        return view('admin.inquiryr', compact('inquiries'));
    }
    
    public function getConcernsData()
    {
        $concerns = DB::table('inquiries')
            ->select('concerns', DB::raw('count(*) as total'))
            ->groupBy('concerns')
            ->get();
    
        return response()->json([
            'labels' => $concerns->pluck('concerns'),
            'data' => $concerns->pluck('total')
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'specialization' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'home_address' => ['required', 'string', 'max:255'],
        ]);

        $defaultPassword = 'Welcome@123';

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword),
                'specialization' => $request->specialization,
                'contact_number' => $request->contact_number,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'home_address' => $request->home_address,
                'usertype' => 'therapist',
                'account_status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Use the new service
            $verificationService = new EmailVerificationNotificationService();
            $verificationService->markEmailAsVerified($user);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Therapist account created successfully.',
                'default_password' => $defaultPassword
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating therapist account: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    
    
}

