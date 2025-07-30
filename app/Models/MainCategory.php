<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'image',
        'branch_id',
    ];

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function meal(){
        return $this->hasMany(Meal::class);
    }






}
