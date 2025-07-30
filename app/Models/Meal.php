<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
       protected $fillable = [
        'name',
        'image',
        'description',
        'maincategory_id'

    ];

    public function maincategory(){
        return $this->belongsTo(MainCategory::class);
    }
    public function type(){
        return $this->hasMany(Type::class);
    }

}
