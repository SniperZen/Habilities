<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        
        if ($notification->notifiable_id === Auth::id()) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    public function getMoreNotifications($offset)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $notifications = DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->skip($offset)
                ->take(4)
                ->get()
                ->map(function ($notification) {
                    try {
                        return $this->processNotificationData($notification);
                    } catch (\Exception $e) {
                        Log::error('Error processing individual notification: ' . $e->getMessage(), [
                            'notification_id' => $notification->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        
                        // Return a default notification if processing fails
                        return [
                            'id' => $notification->id,
                            'type' => $notification->type,
                            'title' => 'Notification',
                            'description' => 'Unable to process notification',
                            'profile_image' => asset('images/others/default-prof.png'),
                            'read_at' => $notification->read_at,
                            'created_at' => \Carbon\Carbon::parse($notification->created_at)->diffForHumans()
                        ];
                    }
                });

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'count' => $notifications->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getMoreNotifications: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function processNotificationData($notification)
    {
        $data = json_decode($notification->data);
        
        // Base notification structure
        $notificationData = [
            'id' => $notification->id,
            'type' => $notification->type,
            'read_at' => $notification->read_at,
            'created_at' => \Carbon\Carbon::parse($notification->created_at)->diffForHumans()
        ];

        if ($notification->type === 'App\Notifications\NewInquiryNotification') {
            $user = User::find($data->user_id);
            
            $notificationData = array_merge($notificationData, [
                'title' => 'New Inquiry',
                'description' => $data->message,
                'user_id' => $data->user_id,
                'user_name' => $data->user_name,
                'inquiry_id' => $data->inquiry_id,
                'profile_image' => $user && $user->profile_image 
                    ? Storage::url($user->profile_image)
                    : asset('images/others/default-prof.png')
            ]);
        }
        else if ($notification->type === 'App\Notifications\AppointmentRequested') {
            $patient = User::find($data->patient_id);
            
            $notificationData = array_merge($notificationData, [
                'title' => 'New Appointment Request',
                'description' => $data->message,
                'patient_id' => $data->patient_id,
                'therapist_id' => $data->therapist_id,
                'patient_name' => $data->patient_name,
                'therapist_name' => $data->therapist_name,
                'profile_image' => $patient && $patient->profile_image 
                    ? Storage::url($patient->profile_image)
                    : asset('images/others/default-prof.png')
            ]);
        } else {
            // Default case for unknown notification types
            $notificationData = array_merge($notificationData, [
                'title' => $data->title ?? 'Notification',
                'description' => $data->message ?? 'New notification',
                'profile_image' => asset('images/others/default-prof.png')
            ]);
        }

        return $notificationData;
    }
}
