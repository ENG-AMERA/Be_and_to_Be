<?php

namespace App\Repositories;

use App\Models\Type;
use App\Models\Admin;
use App\Models\Coupon;
use App\Models\DeliveryOrder;
use App\Models\SelfOrder;
use App\Models\TableOrder;
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

    public function show_all_orders(){
        $user_id=Auth::id();
        $admin=Admin::where('user_id',$user_id)->first();
        $branch_id=$admin->branch_id;

        $dateLimit =  now()->subHours(48);

        $DeliveryOrder=DeliveryOrder::where('branch_id',$branch_id)
        ->where('created_at', '>=', $dateLimit)->where('accepted',0)
        ->with('user')
        ->with('coupon')->with('deliveryorderitem.type.meal')->get();

        $TableOrder=TableOrder::where('branch_id',$branch_id)->where('created_at', '>=', $dateLimit)
        ->where('accepted',0)
        ->with('user')
        ->with('coupon')->with('tableorderitem.type.meal')->get();

        $SelfOrder=SelfOrder::where('branch_id',$branch_id)->where('created_at', '>=', $dateLimit)
        ->where('accepted',0)
        ->with('user')
        ->with('coupon')->with('selforderitem.type.meal')->get();


            return response()->json([
            'delivery_orders'=> $DeliveryOrder,
            'table_orders'=>$TableOrder,
            'self_orders'=>$SelfOrder,
        ]);
    }

    public function accept_order($request){
        if($request->type=='delivery_order'){
            $DeliveryOrder=DeliveryOrder::where('id',$request->order_id)->first();
            $DeliveryOrder->accepted=1;
            $DeliveryOrder->save();
        }

           if($request->type=='table_order'){
            $tableOrder=TableOrder::where('id',$request->order_id)->first();
            $tableOrder->accepted=1;
            $tableOrder->save();
        }
            if($request->type=='self_order'){
            $selfOrder=SelfOrder::where('id',$request->order_id)->first();
            $selfOrder->accepted=1;
            $selfOrder->save();
        }
     return response()->json([
            'accepted successfully'
        ]);
    }

    public function show_archive_orders()
    {
         $user_id=Auth::id();
        $admin=Admin::where('user_id',$user_id)->first();
        $branch_id=$admin->branch_id;

        $DeliveryOrder=DeliveryOrder::where('branch_id',$branch_id)->where('accepted',1)
        ->with('user')
        ->with('coupon')->with('deliveryorderitem.type.meal')->get();

        $TableOrder=TableOrder::where('branch_id',$branch_id)->where('accepted',1)
        ->with('user')
        ->with('coupon')->with('tableorderitem.type.meal')->get();

        $SelfOrder=SelfOrder::where('branch_id',$branch_id)->where('accepted',1)
        ->with('user')
        ->with('coupon')->with('selforderitem.type.meal')->get();


            return response()->json([
            'delivery_orders'=> $DeliveryOrder,
            'table_orders'=>$TableOrder,
            'self_orders'=>$SelfOrder,
        ]);

    }

    public function show_last_accepted_orders(){
          $user_id=Auth::id();
        $admin=Admin::where('user_id',$user_id)->first();
        $branch_id=$admin->branch_id;

        $dateLimit =  now()->subHours(2);

        $DeliveryOrder=DeliveryOrder::where('branch_id',$branch_id)
        ->where('updated_at', '>=', $dateLimit)->where('accepted',1)
        ->with('user')
        ->with('coupon')->with('deliveryorderitem.type.meal')->get();

        $TableOrder=TableOrder::where('branch_id',$branch_id)->where('updated_at', '>=', $dateLimit)
        ->where('accepted',1)
        ->with('user')
        ->with('coupon')->with('tableorderitem.type.meal')->get();

        $SelfOrder=SelfOrder::where('branch_id',$branch_id)->where('updated_at', '>=', $dateLimit)
        ->where('accepted',1)
        ->with('user')
        ->with('coupon')->with('selforderitem.type.meal')->get();


            return response()->json([
            'delivery_orders'=> $DeliveryOrder,
            'table_orders'=>$TableOrder,
            'self_orders'=>$SelfOrder,
        ]);
    }

    public function make_coupon_unactive($id){
        $coupon=Coupon::where('id',$id)->first();
        $coupon->is_active=0;
        $coupon->save();

        return response()->json(['message' => 'coupon edited successfully']);
    }


}
