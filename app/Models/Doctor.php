<?php

namespace App\Models;

use App\Models\Consult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'working_days',
        'user_id'
    ];

    public function consults()
    {
        return $this->hasMany(Consult::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
