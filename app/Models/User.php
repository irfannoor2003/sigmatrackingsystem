<?php

namespace App\Models;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_blocked',
        'left_at',
        'left_reason',
        'joined_at',
        'glass_mode',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->joined_at)) {
                $user->joined_at = now()->toDateString();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blocked' => 'boolean',
            'left_at' => 'datetime',
            'joined_at' => 'date',
            'glass_mode' => 'boolean',
        ];
    }

    // ===============================
    // SCOPES
    // ===============================

    public function scopeActive($query)
    {
        return $query->whereNull('left_at');
    }

    public function scopeLeft($query)
    {
        return $query->whereNotNull('left_at');
    }

    // ===============================
    // BLOCKING HELPERS
    // ===============================

    public function isBlocked(): bool
    {
        return (bool) ($this->is_blocked ?? false);
    }

    public function block(?string $reason = null): bool
    {
        $this->is_blocked = true;
        $saved = $this->save();

        try {
            // Send email and WhatsApp notification (best-effort)
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\UserBlocked($this));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending block email: '.$e->getMessage());
        }

        try {
            \App\Services\NotificationService::sendWhatsApp($this->phone, 'Your account has been blocked. Please contact admin.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending block WhatsApp: '.$e->getMessage());
        }

        return $saved;
    }

    public function unblock(): bool
    {
        $this->is_blocked = false;
        $saved = $this->save();

        try {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\UserUnblocked($this));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending unblock email: '.$e->getMessage());
        }

        try {
            \App\Services\NotificationService::sendWhatsApp($this->phone, 'Your account has been unblocked. You may now access the panel.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending unblock WhatsApp: '.$e->getMessage());
        }

        return $saved;
    }

    // ===============================
    // LEFT STAFF HELPERS
    // ===============================

    public function isLeft(): bool
    {
        return $this->left_at !== null;
    }

    public function markAsLeft(?string $reason = null, $leftAt = null): bool
    {
        if (!$this->isLeft()) {
            $this->left_at = $leftAt ? \Carbon\Carbon::parse($leftAt) : now();
            $this->left_reason = $reason;
        } else {
            $this->left_reason = $reason;
        }
        $saved = $this->save();

        try {
            // Terminate any active sessions so the user is logged out immediately
            \Illuminate\Support\Facades\DB::table('sessions')
                ->where('user_id', $this->id)
                ->delete();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed clearing sessions for left user: '.$e->getMessage());
        }

        try {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\UserLeft($this));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending left email: '.$e->getMessage());
        }

        try {
            \App\Services\NotificationService::sendWhatsApp($this->phone, 'You have left Sigma Engineering Services on '.$this->left_at->format('d M Y').'. You can no longer access the panel.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending left WhatsApp: '.$e->getMessage());
        }

        return $saved;
    }

    public function rejoin(): bool
    {
        $this->left_at = null;
        $this->left_reason = null;
        return $this->save();
    }

    // ===============================
    // RELATIONSHIPS
    // ===============================

    // Salesman related
    public function customers()
    {
        return $this->hasMany(Customer::class, 'salesman_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'salesman_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'salesman_id');
    }

    // ===============================
    // ROLE HELPERS
    // ===============================

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSalesman()
    {
        return $this->role === 'salesman';
    }

    public function isIT()
    {
        return $this->role === 'it';
    }

    public function isAccount()
    {
        return $this->role === 'account';
    }

    // ✅ NEW ROLES
    public function isStore()
    {
        return $this->role === 'store';
    }

    public function isOfficeBoy()
    {
        return $this->role === 'office_boy';
    }

    // ✅ Universal helper (VERY useful)
    public function hasRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }
    public function isHR()
{
    return $this->role === 'hr';
}

public function isSalesHead()
{
    return $this->role === 'saleshead';
}

}
