<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'consult_id',
        'appointment_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consult()
    {
        return $this->belongsTo(Consult::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
