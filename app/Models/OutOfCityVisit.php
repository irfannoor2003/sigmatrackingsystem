<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutOfCityVisit extends Model
{
    protected $fillable = [
        'attendance_id',
        'salesman_id',
        'notes',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function items()
    {
        return $this->hasMany(OutOfCityVisitItem::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
