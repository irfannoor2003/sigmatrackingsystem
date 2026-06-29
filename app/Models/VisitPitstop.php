<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPitstop extends Model
{
    protected $fillable = [
        'visit_id',
        'customer_id',
        'purpose',
        'notes',
        'distance_km',
        'images',
        'visited_at',
        'lat',
        'lng',
    ];

    protected $casts = [
        'images' => 'array',
        'visited_at' => 'datetime',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
