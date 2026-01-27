<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Get current user's IP address
     */
    public static function getIpAddress(): ?string
    {
        return Request::ip();
    }

    /**
     * Get current user agent
     */
    public static function getUserAgent(): ?string
    {
        return Request::header('User-Agent');
    }

    /**
     * Get current user role
     */
    public static function getUserRole(): string
    {
        if (!Auth::check()) {
            return 'guest';
        }

        $user = Auth::user();
        
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return 'admin';
        }

        return 'guest';
    }

    /**
     * Log booking creation
     */
    public static function logBookingCreated($booking, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'booking_created',
            'resource_type' => 'Booking',
            'resource_id' => $booking->id,
            'changes' => [
                'before' => [],
                'after' => $booking->toArray()
            ],
            'metadata' => [
                'property_id' => $booking->property_id,
                'guest_id' => $booking->guest_id,
                'check_in' => $booking->check_in,
                'check_out' => $booking->check_out,
                'total_amount' => $booking->total_amount,
                'status' => $booking->status
            ],
            'description' => "Booking created for " . ($booking->guest->name ?? 'Guest')
        ]);
    }

    /**
     * Log booking update
     */
    public static function logBookingUpdated($booking, $changes, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'booking_updated',
            'resource_type' => 'Booking',
            'resource_id' => $booking->id,
            'changes' => $changes,
            'metadata' => [
                'changed_fields' => array_keys($changes['after'] ?? [])
            ],
            'description' => "Booking updated - Changed: " . implode(', ', array_keys($changes['after'] ?? []))
        ]);
    }

    /**
     * Log booking cancellation
     */
    public static function logBookingCancelled($booking, $reason = null, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'booking_cancelled',
            'resource_type' => 'Booking',
            'resource_id' => $booking->id,
            'metadata' => [
                'guest_id' => $booking->guest_id,
                'booking_ref' => $booking->booking_ref,
                'reason' => $reason
            ],
            'description' => "Booking cancelled" . ($reason ? " - Reason: {$reason}" : "")
        ]);
    }

    /**
     * Log payment initiation
     */
    public static function logPaymentInitiated($paymentIntent, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'payment_initiated',
            'resource_type' => 'Payment',
            'resource_id' => $paymentIntent->id,
            'changes' => [
                'before' => [],
                'after' => $paymentIntent->toArray()
            ],
            'metadata' => [
                'booking_id' => $paymentIntent->booking_id,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'payment_method' => $paymentIntent->payment_method,
                'status' => $paymentIntent->status
            ],
            'description' => "Payment initiated via {$paymentIntent->payment_method} - KES {$paymentIntent->amount}"
        ]);
    }

    /**
     * Log successful payment
     */
    public static function logPaymentSucceeded($paymentIntent, $transactionRef = null, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'payment_succeeded',
            'resource_type' => 'Payment',
            'resource_id' => $paymentIntent->id,
            'status' => 'success',
            'metadata' => [
                'booking_id' => $paymentIntent->booking_id,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'payment_method' => $paymentIntent->payment_method,
                'transaction_ref' => $transactionRef,
                'mpesa_reference' => $paymentIntent->mpesa_reference
            ],
            'description' => "Payment succeeded - KES {$paymentIntent->amount} - Ref: {$transactionRef}"
        ]);
    }

    /**
     * Log payment failure
     */
    public static function logPaymentFailed($paymentIntent, $error = null, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'payment_failed',
            'resource_type' => 'Payment',
            'resource_id' => $paymentIntent->id,
            'status' => 'failed',
            'error_message' => $error,
            'metadata' => [
                'booking_id' => $paymentIntent->booking_id,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'payment_method' => $paymentIntent->payment_method
            ],
            'description' => "Payment failed - KES {$paymentIntent->amount} - Error: {$error}"
        ]);
    }

    /**
     * Log manual payment verification
     */
    public static function logManualPaymentVerified($paymentIntent, $mpesaRef, $verifiedBy = null)
    {
        return self::createLog([
            'user_id' => $verifiedBy ?? Auth::id(),
            'action' => 'manual_payment_verified',
            'resource_type' => 'Payment',
            'resource_id' => $paymentIntent->id,
            'status' => 'success',
            'metadata' => [
                'booking_id' => $paymentIntent->booking_id,
                'mpesa_reference' => $mpesaRef,
                'amount' => $paymentIntent->amount,
                'verified_by_admin' => Auth::user()?->name ?? 'System'
            ],
            'description' => "Manual payment verified - MPESA Ref: {$mpesaRef} - KES {$paymentIntent->amount}"
        ]);
    }

    /**
     * Log receipt generation
     */
    public static function logReceiptGenerated($receipt, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'receipt_generated',
            'resource_type' => 'Receipt',
            'resource_id' => $receipt->id,
            'status' => 'success',
            'metadata' => [
                'receipt_no' => $receipt->receipt_no,
                'booking_id' => $receipt->booking_id,
                'amount' => $receipt->amount,
                'currency' => $receipt->currency,
                'payment_method' => $receipt->getMetadataValue('payment_method')
            ],
            'description' => "Receipt generated - {$receipt->receipt_no} - KES {$receipt->amount}"
        ]);
    }

    /**
     * Log receipt download
     */
    public static function logReceiptDownloaded($receipt, $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'receipt_downloaded',
            'resource_type' => 'Receipt',
            'resource_id' => $receipt->id,
            'metadata' => [
                'receipt_no' => $receipt->receipt_no,
                'booking_id' => $receipt->booking_id
            ],
            'description' => "Receipt downloaded - {$receipt->receipt_no}"
        ]);
    }

    /**
     * Log refund processing
     */
    public static function logRefundProcessed($booking, $amount, $reason = null, $processedBy = null)
    {
        return self::createLog([
            'user_id' => $processedBy ?? Auth::id(),
            'action' => 'refund_processed',
            'resource_type' => 'Refund',
            'resource_id' => $booking->id,
            'status' => 'success',
            'metadata' => [
                'booking_id' => $booking->id,
                'amount' => $amount,
                'reason' => $reason,
                'processed_by' => Auth::user()?->name ?? 'System'
            ],
            'description' => "Refund processed - KES {$amount}" . ($reason ? " - Reason: {$reason}" : "")
        ]);
    }

    /**
     * Log admin payment modification
     */
    public static function logAdminModifiedPayment($paymentIntent, $changes, $reason = null, $modifiedBy = null)
    {
        return self::createLog([
            'user_id' => $modifiedBy ?? Auth::id(),
            'action' => 'admin_modified_payment',
            'resource_type' => 'Payment',
            'resource_id' => $paymentIntent->id,
            'changes' => $changes,
            'metadata' => [
                'booking_id' => $paymentIntent->booking_id,
                'modified_by' => Auth::user()?->name ?? 'System',
                'reason' => $reason,
                'changed_fields' => array_keys($changes['after'] ?? [])
            ],
            'description' => "Admin modified payment - Changed: " . implode(', ', array_keys($changes['after'] ?? []))
        ]);
    }

    /**
     * Log admin action (generic)
     */
    public static function logAdminAction($action, $resourceType, $resourceId, $description = null, $metadata = [], $userId = null)
    {
        return self::createLog([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'status' => 'success',
            'metadata' => array_merge($metadata, [
                'performed_by' => Auth::user()?->name ?? 'System'
            ]),
            'description' => $description ?? "Admin action: {$action}"
        ]);
    }

    /**
     * Log successful login
     */
    public static function logLoginSuccess($user = null)
    {
        $user = $user ?? Auth::user();

        return self::createLog([
            'user_id' => $user?->id,
            'action' => 'login_success',
            'resource_type' => 'Auth',
            'resource_id' => $user?->id,
            'status' => 'success',
            'metadata' => [
                'user_email' => $user?->email,
                'user_name' => $user?->name,
            ],
            'description' => $user?->email
                ? "User {$user->email} logged in successfully"
                : 'Login succeeded'
        ]);
    }

    /**
     * Log failed login attempt
     */
    public static function logLoginFailed(string $email = null)
    {
        return self::createLog([
            'user_id' => null,
            'action' => 'login_failed',
            'resource_type' => 'Auth',
            'resource_id' => null,
            'status' => 'failed',
            'metadata' => [
                'user_email' => $email,
            ],
            'description' => $email
                ? "Login failed for {$email}"
                : 'Login failed'
        ]);
    }

    /**
     * Log logout
     */
    public static function logLogout($user = null)
    {
        $user = $user ?? Auth::user();

        return self::createLog([
            'user_id' => $user?->id,
            'action' => 'logout',
            'resource_type' => 'Auth',
            'resource_id' => $user?->id,
            'status' => 'success',
            'metadata' => [
                'user_email' => $user?->email,
                'user_name' => $user?->name,
            ],
            'description' => $user?->email
                ? "User {$user->email} logged out"
                : 'User logged out'
        ]);
    }

    /**
     * Create an audit log entry
     */
    public static function createLog($data = [])
    {
        $defaults = [
            'user_id' => Auth::id(),
            'ip_address' => self::getIpAddress(),
            'user_agent' => self::getUserAgent(),
            'user_role' => self::getUserRole(),
            'status' => 'success',
            'changes' => null,
            'metadata' => []
        ];

        $logData = array_merge($defaults, $data);

        return AuditLog::create($logData);
    }

    /**
     * Get audit log for resource
     */
    public static function getResourceAudit($resourceType, $resourceId, $limit = 50)
    {
        return AuditLog::forResource($resourceType, $resourceId)
            ->recentFirst()
            ->limit($limit)
            ->get();
    }

    /**
     * Get user audit trail
     */
    public static function getUserAudit($userId, $limit = 100)
    {
        return AuditLog::byUser($userId)
            ->recentFirst()
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs by action
     */
    public static function getActionAudit($action, $limit = 50)
    {
        return AuditLog::byAction($action)
            ->recentFirst()
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs by IP address
     */
    public static function getIpAudit($ipAddress, $limit = 100)
    {
        return AuditLog::byIpAddress($ipAddress)
            ->recentFirst()
            ->limit($limit)
            ->get();
    }

    /**
     * Get suspicious activity (multiple failures from same IP)
     */
    public static function getSuspiciousActivity($minutes = 60, $failureThreshold = 5)
    {
        $recentLogs = AuditLog::within($minutes)
            ->failed()
            ->recentFirst()
            ->get();

        $grouped = $recentLogs->groupBy('ip_address');

        return $grouped->filter(function ($logs) use ($failureThreshold) {
            return $logs->count() >= $failureThreshold;
        });
    }

    /**
     * Get statistics
     */
    public static function getStatistics($days = 30)
    {
        $startDate = now()->subDays($days);

        return [
            'total_logs' => AuditLog::where('created_at', '>=', $startDate)->count(),
            'successful_actions' => AuditLog::where('created_at', '>=', $startDate)->successful()->count(),
            'failed_actions' => AuditLog::where('created_at', '>=', $startDate)->failed()->count(),
            'by_action' => AuditLog::where('created_at', '>=', $startDate)
                ->groupBy('action')
                ->selectRaw('action, count(*) as count')
                ->get(),
            'by_user' => AuditLog::where('created_at', '>=', $startDate)
                ->where('user_id', '!=', null)
                ->groupBy('user_id')
                ->selectRaw('user_id, count(*) as count')
                ->with('user')
                ->get(),
            'by_resource_type' => AuditLog::where('created_at', '>=', $startDate)
                ->groupBy('resource_type')
                ->selectRaw('resource_type, count(*) as count')
                ->get(),
            'unique_ips' => AuditLog::where('created_at', '>=', $startDate)
                ->distinct('ip_address')
                ->count('ip_address')
        ];
    }
}
