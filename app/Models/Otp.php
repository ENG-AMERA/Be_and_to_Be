<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
           protected $fillable = [
        'expires_at',
        'used',
        'otp',
        'phone',
        'created_at'
    ];
}
