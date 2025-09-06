<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfOrder extends Model
{
    use HasFactory;
            protected $fillable = [
        'accepted','total_price','item_number', 'user_id', 'coupon_id','branch_id','note',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function coupon(){
      return $this->belongsTo(Coupon:: class);
    }

    public function selforderitem(){
        return $this->hasMany(SelfOrderItem::class);
    }
}
