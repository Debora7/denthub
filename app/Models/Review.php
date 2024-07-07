<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['consult_id', 'rating', 'experience'];

    public function consult()
    {
        return $this->belongsTo(Consult::class);
    }
}
