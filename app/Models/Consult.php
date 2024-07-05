<?php

namespace App\Models;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consult extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor',
        'service',
        'description',
        'price',
        'address',
        'city_id',
        'county_id',
        'user_id',
        'doctor_id',
        'consult_time',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
