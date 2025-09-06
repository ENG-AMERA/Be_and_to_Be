<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfOrderItem extends Model
{
    use HasFactory;
                 protected $fillable = [
        'type_id',
        'self_order_id',
        'extra',
        'amount',
        'price',
    ];
    public function selforder(){
        return $this->belongsTo(SelfOrder::class);
    }
}
