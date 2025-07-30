<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;
        protected $fillable = [
        'phone',
        'branch_id',
    ];

        public function branch(){
       return  $this->belongsTo(Branch::class);
        }
}

