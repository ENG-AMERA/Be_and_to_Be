<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
        protected $fillable = [
        'code','value','min_order', 'expires_at', 'is_active','branch_id'
    ];

    // public function isValid($orderTotal)
    // {
    //     return $this->is_active
    //         && (!$this->expires_at || $this->expires_at > now())
    //         && (!$this->min_order || $orderTotal >= $this->min_order);
    // }
        public function isValid($orderTotal)
    {
       if (!$this->is_active) {
        return ['valid' => false, 'reason' => 'أعد الطلب مجدداً , لا يمكنك استخدام الكوبون لأن الكوبون غير مفعل'];
    }

    if ($this->expires_at && $this->expires_at <= now()) {
        return ['valid' => false, 'reason' => 'أعد الطلب مجدداً , لا يمكنك استخدام الكوبون لأن الكوبون منتهي الصلاحية'];
    }

    if ($this->min_order && $orderTotal < $this->min_order) {
        return ['valid' => false, 'reason' => 'أعد الطلب مجدداً , لا يمكنك استخدام الكوبون لأن قيمة الطلب أقل من الحد الأدنى المسموح'];
    }

    return ['valid' => true, 'reason' => null];
    }

        public function applyDiscount($orderTotal)
    {

        $discountAmount = ($orderTotal * $this->value) / 100;

        return $orderTotal - $discountAmount;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function deliveryorder(){
        return $this->hasMany(DeliveryOrder::class);
    }

              public function tableorder(){
        return $this->hasMany(TableOrder::class);
    }
              public function selforder(){
        return $this->hasMany(SelfOrder::class);
    }

}
