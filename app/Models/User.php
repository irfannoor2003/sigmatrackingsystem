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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
