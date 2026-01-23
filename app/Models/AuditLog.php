<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AuditLog extends Model
{
    public $timestamps = false; // Only created_at
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    
    protected $fillable = [
        'user_id',
        'action',
        'resource_type',
        'resource_id',
        'changes',
        'metadata',
        'ip_address',
        'user_agent',
        'user_role',
        'status',
        'description',
        'error_message'
    ];

    protected $casts = [
        'changes' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime'
    ];

    // ========== Relationships ==========

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    // ========== Scopes ==========

    public function scopeByUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction(Builder $query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByResourceType(Builder $query, $resourceType)
    {
        return $query->where('resource_type', $resourceType);
    }

    public function scopeForResource(Builder $query, $resourceType, $resourceId)
    {
        return $query->where('resource_type', $resourceType)
            ->where('resource_id', $resourceId);
    }

    public function scopeByIpAddress(Builder $query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    public function scopeByRole(Builder $query, $role)
    {
        return $query->where('user_role', $role);
    }

    public function scopeSuccessful(Builder $query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed(Builder $query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeWithin(Builder $query, $minutes)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeToday(Builder $query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek(Builder $query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth(Builder $query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeRecentFirst(Builder $query)
    {
        return $query->orderByDesc('created_at');
    }

    // ========== Methods ==========

    public function getChangesArray()
    {
        return $this->changes ?? [];
    }

    public function getChangesBefore()
    {
        return $this->changes['before'] ?? [];
    }

    public function getChangesAfter()
    {
        return $this->changes['after'] ?? [];
    }

    public function getChangedFields()
    {
        $before = $this->getChangesBefore();
        $after = $this->getChangesAfter();
        
        return array_keys(array_diff_key($after, $before));
    }

    public function getMetadataValue($key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    public function isBookingAction()
    {
        return $this->resource_type === 'Booking';
    }

    public function isPaymentAction()
    {
        return $this->resource_type === 'Payment';
    }

    public function isReceiptAction()
    {
        return $this->resource_type === 'Receipt';
    }

    public function isAdminAction()
    {
        return $this->user_role === 'admin';
    }

    public function isGuestAction()
    {
        return $this->user_role === 'guest';
    }

    public function isSystemAction()
    {
        return $this->user_role === 'system';
    }

    public function wasSuccessful()
    {
        return $this->status === 'success';
    }

    public function hasFailed()
    {
        return $this->status === 'failed';
    }

    // ========== Formatting ==========

    public function getActionLabel()
    {
        return match($this->action) {
            'booking_created' => 'Booking Created',
            'booking_updated' => 'Booking Updated',
            'booking_cancelled' => 'Booking Cancelled',
            'payment_initiated' => 'Payment Initiated',
            'payment_succeeded' => 'Payment Succeeded',
            'payment_failed' => 'Payment Failed',
            'manual_payment_verified' => 'Manual Payment Verified',
            'receipt_generated' => 'Receipt Generated',
            'receipt_downloaded' => 'Receipt Downloaded',
            'refund_processed' => 'Refund Processed',
            'admin_modified_payment' => 'Admin Modified Payment',
            'admin_issued_refund' => 'Admin Issued Refund',
            default => ucwords(str_replace('_', ' ', $this->action))
        };
    }

    public function getResourceLabel()
    {
        return match($this->resource_type) {
            'Booking' => "Booking #{$this->resource_id}",
            'Payment' => "Payment #{$this->resource_id}",
            'Receipt' => "Receipt #{$this->resource_id}",
            'Refund' => "Refund #{$this->resource_id}",
            default => "{$this->resource_type} #{$this->resource_id}"
        };
    }

    public function getStatusBadge()
    {
        return match($this->status) {
            'success' => '<span class="badge bg-success">Success</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }
}
