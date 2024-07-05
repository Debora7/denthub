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
    ];

    public function consults()
    {
        return $this->hasMany(Consult::class);
    }
}