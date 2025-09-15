<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderItem extends Model
{
    use HasFactory;
         protected $fillable = [
        'type_id',
        'delivery_order_id',
        'extra',
        'amount',
        'price',
    ];
    public function deliveryorder(){
        return $this->belongsTo(DeliveryOrder::class);
    }
        public function type(){
        return $this->belongsTo(Type::class);
    }

}
