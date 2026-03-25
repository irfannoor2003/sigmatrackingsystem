<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{
    /* =========================
        MASS ASSIGNMENT
    ==========================*/
    protected $fillable = [
        'salesman_id',
        'date',
        'status',

        'clock_in',
        'clock_out',
        'total_minutes',

        'lat',
        'lng',
        'distance_meters',

        'office_verified',
        'qr_verified',
        'checkin_method',

        'short_leave',
        'auto_clock_out',
        'manual_visit',

        'device_hash',
        'checkin_ip',

        'reminder_sent',
        'note',
        'marked_by',
    ];

    /* =========================
        TYPE CASTING
    ==========================*/
    protected $casts = [
        'date'            => 'date',
        'clock_in'        => 'datetime',
        'clock_out'       => 'datetime',

        'office_verified' => 'boolean',
        'qr_verified'     => 'boolean',
        'short_leave'     => 'boolean',
        'auto_clock_out'  => 'boolean',
        'manual_visit'    => 'boolean',
        'reminder_sent'   => 'boolean',
    ];

    /* =========================
        RELATIONSHIPS
    ==========================*/
    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    // Alias (optional but kept for compatibility)
    public function user()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function office()
{
    return $this->belongsTo(Office::class);
}

    /* =========================
        ACCESSORS (UI HELPERS)
    ==========================*/

    /**
     * Human-readable attendance status
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'leave') {
            return 'On Leave';
        }

        if ($this->manual_visit) {
            return 'Manual Visit';
        }

        if ($this->short_leave) {
            return 'Short Leave';
        }

        if ($this->clock_in && !$this->clock_out) {
            return 'Working';
        }

        if ($this->clock_in && $this->clock_out) {
            return 'Completed';
        }

        return 'Not Clocked';
    }

    /**
     * Live working minutes
     */
    public function getWorkingMinutesAttribute(): int
    {
        if ($this->status === 'leave' || !$this->clock_in) {
            return 0;
        }

        $clockOut = $this->clock_out ?? now();

        return $this->clock_in->diffInMinutes($clockOut);
    }

    /**
     * Format working time as HH:MM
     */
    public function getWorkingDurationAttribute(): string
    {
        $minutes = $this->total_minutes ?? $this->working_minutes;

        if ($minutes <= 0) {
            return '--';
        }

        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }

    /**
     * Late clock-in (>= 12 PM)
     */
    public function getIsLateAttribute(): bool
    {
        if ($this->manual_visit || $this->status === 'leave') {
            return false;
        }

        return $this->clock_in && $this->clock_in->format('H:i') >= '12:00';
    }

    /**
     * Early clock-out (before 5 PM)
     */
    public function getIsEarlyOutAttribute(): bool
    {
        if ($this->manual_visit || $this->status === 'leave') {
            return false;
        }

        return $this->clock_out && $this->clock_out->format('H:i') < '17:00';
    }

    /**
     * Auto clock-out indicator
     */
    public function getIsAutoClockedAttribute(): bool
    {
        return (bool) $this->auto_clock_out;
    }

    /**
     * UI Badge for check-in method
     */
    public function getCheckinBadgeAttribute(): string
    {
        return match ($this->checkin_method) {
            'qr'     => 'QR + GPS',
            'gps'    => 'GPS',
            'manual' => 'Manual',
            default  => 'Unknown'
        };
    }
    public function marker()
{
    return $this->belongsTo(User::class, 'marked_by');
}
public function markedBy()
{
    return $this->belongsTo(User::class, 'marked_by');
}
}
