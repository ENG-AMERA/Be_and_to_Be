<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableOrderItem extends Model
{
    use HasFactory;
             protected $fillable = [
        'type_id',
        'table_order_id',
        'extra',
        'amount',
        'price',
    ];
    public function tableorder(){
        return $this->belongsTo(TableOrder::class);
    }

        public function type(){
        return $this->belongsTo(Type::class);
    }
}
