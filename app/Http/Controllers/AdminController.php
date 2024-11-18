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

use App\Models\BusinessSetting;

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
        $therapists = User::where('usertype', 'therapist')->get();
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

    public function appointmentr()
    {
        return view('admin.appointmentr');
    }
    public function inquiryr()
    {
        return view('admin.inquiryr');
    }
    public function otfr()
    {
        return view('admin.otfr');
    }
    public function systemfeedbackr()
    {
        return view('admin.systemfeedbackr');
    }
    public function activitylogs()
    {
        return view('admin.activitylogs');
    }

    public function therapycenter()
    {
        $settings = BusinessSetting::first();
        return view('admin.therapycenter', compact('settings'));
    }

    public function editTCenter()  // Changed from ediTCenter to editTCenter
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
            'is_closed' => $request->boolean("hours.{$day}.is_closed")
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

    // Save the changes
    if ($user->save()) {
        return redirect()->back()->with('success', 'User information updated successfully.');
    } else {
        return redirect()->back()->with('error', 'Failed to update user information.');
    }
}
public function getDashboardCounts()
{
    // Active Users count and growth
    $currentActiveUsers = User::where('account_status', 'active')->count();
    $lastMonthActiveUsers = User::where('account_status', 'active')
        ->where('created_at', '<=', Carbon::now()->subMonth())
        ->count();
    
    $userGrowth = 0;
    if ($lastMonthActiveUsers > 0) {
        $userGrowth = (($currentActiveUsers - $lastMonthActiveUsers) / $lastMonthActiveUsers) * 100;
    }

    // New Users count (registered within the last month)
    $newUsersCount = User::where('account_status', 'active')
        ->where('created_at', '>=', Carbon::now()->subMonth())
        ->count();

    // New Users growth calculation
    $lastMonthNewUsers = User::where('account_status', 'active')
        ->where('created_at', '>=', Carbon::now()->subMonth())
        ->where('created_at', '<', Carbon::now()->subMonth()->addMonth())
        ->count();

    $newUserGrowth = 0;
    if ($lastMonthNewUsers > 0) {
        $newUserGrowth = (($newUsersCount - $lastMonthNewUsers) / $lastMonthNewUsers) * 100;
    }

    // Account counts
    $therapistAccounts = User::where('usertype', 'therapist')->count();
    $patientAccounts = User::where('usertype', 'user')->count();
    $supervisedAccounts = User::where('account_type', 'child')->count();

    // Gender distribution
    $genderDistribution = User::select('gender', DB::raw('count(*) as count'))
        ->whereNotNull('gender')
        ->groupBy('gender')
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->gender => $item->count];
        });

    return response()->json([
        'active_users' => $currentActiveUsers,
        'user_growth' => round($userGrowth, 2),
        'new_users' => $newUsersCount,
        'new_user_growth' => round($newUserGrowth, 2), // Include new user growth
        'therapist_accounts' => $therapistAccounts,
        'patient_accounts' => $patientAccounts,
        'supervised_accounts' => $supervisedAccounts,
        'gender_distribution' => $genderDistribution
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
        $query = Inquiry::with('user') // Change from Report to Inquiry
            ->select('inquiries.*', 'users.name as patient_name') // Change from reports to inquiries
            ->join('users', 'inquiries.user_id', '=', 'users.id'); // Change from reports to inquiries

        // Filter by status
        if ($request->status === 'pending') {
            $query->whereNull('completed_at');
        } elseif ($request->status === 'completed') {
            $query->whereNotNull('completed_at');
        }

        // Filter by date range
        if ($request->start_date) {
            $query->whereDate('inquiries.created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('inquiries.created_at', '<=', $request->end_date);
        }

        $inquiries = $query->orderBy('inquiries.created_at', 'desc')->get();

        return view('admin.inquiryr', compact('inquiries')); // Change reports to inquiries
    }
    
}

