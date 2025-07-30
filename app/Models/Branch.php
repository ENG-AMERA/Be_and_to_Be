<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
        protected $fillable = [
        'branch_name',
        'image',
        'length',
        'width',
        'instagramtoken',
        'facebooktoken',
    ];

    public function phonenumbers(){
       return  $this->hasMany(PhoneNumber::class);
        }


    public function admin(){
       return  $this->hasMany(Admin::class);
        }

      public function maincategory(){
       return  $this->hasMany(MainCategory::class);
        }

}
