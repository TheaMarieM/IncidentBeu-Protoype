<?php

namespace App\Services;

use App\Models\ParentNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send pending notifications via SMS or Email
     */
    public function processPendingNotifications()
    {
        $pendingNotifications = ParentNotification::where('status', 'pending')
            ->with(['parent', 'student', 'incident'])
            ->get();

        foreach ($pendingNotifications as $notification) {
            try {
                if ($notification->notification_type === 'email') {
                    $this->sendEmail($notification);
                } else {
                    $this->sendSMS($notification);
                }

                $notification->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                $notification->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                Log::error('Notification failed', [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmail(ParentNotification $notification)
    {
        // TODO: Implement email sending using Laravel Mail
        // This is a placeholder for the actual email sending logic
        
        /*
        Mail::to($notification->parent->email)->send(
            new ParentIncidentNotification($notification)
        );
        */

        // For now, just log it
        Log::info('Email notification sent', [
            'to' => $notification->parent->email,
            'parent' => $notification->parent->full_name,
            'student' => $notification->student->full_name,
        ]);
    }

    /**
     * Send SMS notification
     */
    protected function sendSMS(ParentNotification $notification)
    {
        // TODO: Implement SMS sending using services like Twilio, Semaphore, or Vonage
        // This is a placeholder for the actual SMS sending logic
        
        /*
        // Example using a generic SMS service
        $smsService = app(SMSService::class);
        $smsService->send([
            'to' => $notification->parent->phone,
            'message' => $notification->message,
        ]);
        */

        // For now, just log it
        Log::info('SMS notification sent', [
            'to' => $notification->parent->phone,
            'parent' => $notification->parent->full_name,
            'student' => $notification->student->full_name,
        ]);
    }

    /**
     * Generate notification message
     */
    public static function generateParentNotificationMessage($parentName, $studentName)
    {
        return "Dear {$parentName}, \n\n" .
            "This is to inform you that {$studentName} has been involved in a behavioral incident at St. Paul University Philippines - BEU. " .
            "Please visit the school to discuss this matter with the Discipline Office at your earliest convenience.\n\n" .
            "Thank you for your cooperation.\n" .
            "BEU Discipline Office";
    }
}
