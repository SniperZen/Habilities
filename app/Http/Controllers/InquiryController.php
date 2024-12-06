<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\NewInquiryNotification;
use Carbon\Carbon;
class InquiryController extends Controller

{
    /**
     * Store the first step of the inquiry process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeStep1(Request $request)
    {
        // Validate the input
        $request->validate([
            'concerns' => 'required|string',
            'elaboration' => 'required|string|max:500',
        ]);

        // Store the data in the session
        session([
            'inquiry.concerns' => $request->input('concerns'),
            'inquiry.elaboration' => $request->input('elaboration'),
        ]);

        // Redirect to the next step
        return redirect()->route('patient.inquiry2');
    }
    /**
     * Store the second step of the inquiry process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeInquiryStep2(Request $request)
    {
        // Validate the input
        $request->validate([
            'identification_card' => 'nullable|file|max:10240|mimes:jpg,png,pdf,docx',
            'birth_certificate' => 'required|file|max:10240|mimes:jpg,png,pdf,docx',
            'diagnosis_reports' => 'required|file|max:10240|mimes:jpg,png,pdf,docx',
        ]);

        // Handle the file uploads
        $idCardPath = $request->hasFile('identification_card') 
            ? $request->file('identification_card')->store('uploads/identification_card', 'public') 
            : null;

        $birthCertificatePath = $request->file('birth_certificate')->store('uploads/birth_certificates', 'public');
        $diagnosisReportsPath = $request->file('diagnosis_reports')->store('uploads/diagnosis_reports', 'public');

        // Store file paths in the session
        session([
            'inquiry.identification_card' => $idCardPath,
            'inquiry.birth_certificate' => $birthCertificatePath,
            'inquiry.diagnosis_reports' => $diagnosisReportsPath,
        ]);

        // Redirect to the next step
        return redirect()->route('patient.inquiry3');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'concerns' => 'required|string|max:255',
            'elaboration' => 'required|string',
            'identification_card' => 'nullable|string',
            'birth_certificate' => 'nullable|string',
            'diagnosis_reports' => 'nullable|string',
        ]);
    
        $inquiry = new Inquiry();
        $inquiry->user_id = Auth::id();
        $inquiry->concerns = $validatedData['concerns'];
        $inquiry->elaboration = $validatedData['elaboration'];
        $inquiry->identification_card = $validatedData['identification_card'] ?? null;
        $inquiry->birth_certificate = $validatedData['birth_certificate'] ?? null;
        $inquiry->diagnosis_reports = $validatedData['diagnosis_reports'] ?? null;
        $inquiry->save();
    
        // Notify all therapists about the new inquiry
        $therapists = User::where('usertype', 'therapist')->get();
        foreach ($therapists as $therapist) {
            $therapist->notify(new NewInquiryNotification($inquiry));
        }
    
        // Using flash session data
        return redirect()->route('patient.inquiry01')->with('success', 'Inquiry confirmed successfully!');
    }
    
    public function showInquiries(Request $request)
    {
        $historyFilter = $request->input('history_filter', 'all');
        $pendingFilter = $request->input('pending_filter', 'all');
    
        // Query for pending inquiries
        $pendingInquiries = Inquiry::whereNull('completed_at')
            ->when($pendingFilter !== 'all', function ($query) use ($pendingFilter) {
                return $this->applyDateFilter($query, $pendingFilter, 'created_at');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Query for completed inquiries
        $completedInquiries = Inquiry::whereNotNull('completed_at')
            ->when($historyFilter !== 'all', function ($query) use ($historyFilter) {
                return $this->applyDateFilter($query, $historyFilter, 'completed_at');
            })
            ->orderBy('completed_at', 'desc')
            ->get();
    
        return view('therapist.inquiry', compact('pendingInquiries', 'completedInquiries'));
    }
    
    private function applyDateFilter($query, $filter, $dateColumn)
    {
        switch ($filter) {
            case 'today':
                return $query->whereDate($dateColumn, Carbon::today());
            case 'yesterday':
                return $query->whereDate($dateColumn, Carbon::yesterday());
            case 'last_7_days':
                return $query->where($dateColumn, '>=', Carbon::now()->subDays(7));
            case 'last_14_days':
                return $query->where($dateColumn, '>=', Carbon::now()->subDays(14));
            case 'last_21_days':
                return $query->where($dateColumn, '>=', Carbon::now()->subDays(21));
            case 'last_28_days':
                return $query->where($dateColumn, '>=', Carbon::now()->subDays(28));
            default:
                return $query;
        }
    }
    public function showMessage($id)
    {
        $inquiry = Inquiry::with('user')->findOrFail($id);
        return view('therapist.inquirymess', compact('inquiry'));
    }
    public function complete($id)
    {
        $inquiry = Inquiry::find($id);
        if ($inquiry) {
            $inquiry->completed_at = now();
            $inquiry->save();
    
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inquiry marked as completed'
                ]);
            }
            
            return redirect()->back()->with('success', 'Inquiry marked as Responded.');
        }
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Inquiry not found'
            ], 404);
        }
    
        return redirect()->back()->with('error', 'Inquiry not found');
    }
    
    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->route('therapist.inquiry')->with('success', 'Inquiry deleted successfully.');
    }

}
