<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
          protected $fillable = [
        'name',
        'price',
        'supportprice',
        'meal_id',
        'available',

    ];
    public function meal(){
        return $this->belongsTo(Meal::class);
    }

    public function cartitems(){
    return $this->hasMany(CartItem::class);
    }
        public function deliveryorderitem(){
    return $this->hasMany(DeliveryOrderItem::class);
    }

        public function tableorderitem(){
    return $this->hasMany(TableOrderItem::class);
    }

           public function selforderitem(){
    return $this->hasMany(SelfOrderItem::class);
    }
}
