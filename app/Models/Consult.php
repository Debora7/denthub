<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
