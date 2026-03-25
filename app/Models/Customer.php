<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
    'salesman_id',
    'name',
    'contact_person',
    'phone1',
    'phone2',
    'email',
    'address',
    'city_id',
    'industry_id',
    'category_id',
    'image',
    'landmark',
     'last_newsletter_sent_at',
     'welcome_email_sent_at',
];


    // Relationships
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }
    protected static function booted()
{
    static::created(function ($customer) {
        if (!empty($customer->email) && !$customer->welcome_email_sent_at) {
            \Illuminate\Support\Facades\Mail::to($customer->email)
                ->send(new \App\Mail\CustomerWelcome($customer));

            $customer->update(['welcome_email_sent_at' => now()]);
        }
    });
}

}
