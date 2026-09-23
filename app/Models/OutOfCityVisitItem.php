<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutOfCityVisitItem extends Model
{
    protected $fillable = [
        'out_of_city_visit_id',
        'customer_id',
        'note',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function outOfCityVisit()
    {
        return $this->belongsTo(OutOfCityVisit::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
