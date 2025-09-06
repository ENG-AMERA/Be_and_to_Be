<?php

namespace App\Repositories;

use App\Models\Type;
use App\Models\Admin;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class AdminRepository{

    public function make_meal_unavailable($id)
    {
        $type=Type::where('id',$id)->first();
        $type->available=0;
        $type->save();
           return response()->json([
            'status updated successfully'
        ]);
    }

    public function addcoupon($request){
        $user_id=Auth::id();
        $admin=Admin::where('user_id',$user_id)->first();
        $branch_id=$admin->branch_id;
        $coupon=Coupon::create([
            'branch_id'=>$branch_id,
            'code'=>$request->code,
            'min_order'=>$request->min_order,
            'value'=>$request->percent_value,
            'expires_at'=>$request->expires_date,
        ]);
                       return response()->json([
            'coupon added successfully'
        ]);
    }

    public function deletecoupon($id){
        $coupon=Coupon::where('id',$id)->delete();
                       return response()->json([
            'coupon deleted successfully'
        ]);
    }

    public function edit_min_order($request){
        $coupon=Coupon::where('id',$request->coupon_id)->first();
        $coupon->min_order=$request->min_order;
        $coupon->save();
                return response()->json([
            'coupon edited successfully'
        ]);
    }
        public function edit_value($request){
        $coupon=Coupon::where('id',$request->coupon_id)->first();
        $coupon->value=$request->value;
        $coupon->save();
                       return response()->json([
            'coupon edited successfully'
        ]);
    }
          public function edit_expires_at($request){
        $coupon=Coupon::where('id',$request->coupon_id)->first();
        $coupon->expires_at=$request->expires_at;
        $coupon->save();
                  return response()->json([
            'coupon edited successfully'
        ]);
    }
    public function show_coupons(){
        $coupons=Coupon::with('branch')->get();
        return response()->json([
            'All coupons'=> $coupons,
        ]);
    }

}
