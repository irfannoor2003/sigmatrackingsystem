<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'salesman_id',
        'purpose',
        'notes',
        'status',
        'started_at',
        'completed_at',
        'distance_km',
        'images',
        'start_lat',
        'start_lng',
        'reminder_5pm_sent',
        'reminder_530pm_sent',
        'blocked_at',
        'blocked_by',
        'unblocked_at',
        'unblocked_by',
    ];

    protected $casts = [
        'started_at'       => 'datetime',
        'completed_at'     => 'datetime',
        'blocked_at'       => 'datetime',
        'unblocked_at'     => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
        'images'           => 'array',
        'distance_km'      => 'decimal:2',
        'reminder_5pm_sent' => 'boolean',
        'reminder_530pm_sent' => 'boolean',
    ];

    /* ======================
        Relationships
    ====================== */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function pitstops()
    {
        return $this->hasMany(VisitPitstop::class)->orderBy('visited_at');
    }

    /* ======================
        STATUS HELPERS
    ====================== */

    public function isStarted(): bool
    {
        return $this->status === 'started';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeCompleted(): bool
    {
        return $this->isStarted();
    }

    public function canBeBlocked(): bool
    {
        return $this->isStarted() && !$this->isBlocked();
    }

    public function canBeUnblocked(): bool
    {
        return $this->isBlocked();
    }

    /* ======================
        ACCESSORS
    ====================== */

    public function getDisplayStatusAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Pending',
            'started'   => 'In Progress',
            'completed' => 'Completed',
            'blocked'   => 'Blocked',
            default     => 'Unknown',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'started'   => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'blocked'   => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }

    public function getDaysOverdueAttribute(): ?int
    {
        if (!$this->isBlocked() || !$this->blocked_at) {
            return null;
        }

        return now()->diffInDays($this->blocked_at);
    }
}
