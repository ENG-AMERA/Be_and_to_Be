<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Meal;
use App\Models\Type;
use App\Models\Branch;
use App\Models\Coupon;
use App\Models\CartItem;
use App\Models\SelfOrder;
use App\Models\TableOrder;
use App\Models\MainCategory;
use App\Models\DeliveryOrder;
use App\Models\SelfOrderItem;
use App\Models\TableOrderItem;
use App\Models\DeliveryOrderItem;
use Illuminate\Support\Facades\Auth;

class ClientRepository{


    public function getbranches(){
        $Branch=Branch::with('phonenumbers')->get();
        return response()->json([
            'branches'=>$Branch,
        ]);
    }

    public function getmaincategories($branch_id)
    {
        $Categories=MainCategory::where('branch_id',$branch_id)->get();
           return response()->json([
            'All Categories'=>$Categories,
        ]);
    }

    public function getmealsofcategory($category_id){
        $meal=Meal::where('maincategory_id',$category_id)->get();
              return response()->json([
            'All Meals'=> $meal,
        ]);
    }

       public function gettypesofmeal($meal_id){
        $meal=Meal::where('id',$meal_id)->with('type')->get();
              return response()->json([
            'meals with types'=> $meal,
        ]);
    }

    public function addtocart($request){
    $user_id=Auth::id();
    $cart=Cart::where('user_id',$user_id)->where('branch_id',$request->branch_id)->first();
    $product=Type::where('id',$request->type_id)->first();
if($product->available==1)
{
    if($cart)
    {
        $cart->total_price  += $request->price;
        $cart->item_number += $request->amount;
        $cart->save();

         if($request->extra==0)
    {
        $item=CartItem::where('type_id',$request->type_id)->
                        where('cart_id',$cart->id)->where('extra',0)->first();
        if($item){
     $this->addexistingmeal($request->amount,$request->price,0,$product->id,$cart->id);
        }
        if(!$item){
    $item=CartItem::create([
            'cart_id'=>$cart->id,
            'type_id'=>$request->type_id,
            'amount'=>$request->amount,
            'price'=>$request->price,
        ]);
        }
    }
        if($request->extra==1)
            {
      $item=CartItem::where('type_id',$request->type_id)->
                        where('cart_id',$cart->id)->where('extra',1)->first();
        if($item){
     $this->addexistingmeal($request->amount,$request->price,1,$product->id,$cart->id);
        }

        if(!$item){
       $item=CartItem::create([
            'cart_id'=>$cart->id,
            'type_id'=>$request->type_id,
            'amount'=>$request->amount,
            'extra'=>1,
            'price'=>$request->price,
        ]);
        }
        }
           }

    if(!$cart){
        $cart=$this->createcart($request->amount,$request->price,$request->branch_id);
     if($request->extra==0)
    {
        $item=CartItem::create([
            'cart_id'=>$cart->id,
            'type_id'=>$request->type_id,
            'amount'=>$request->amount,
            'price'=>$request->price,
        ]);

    }
        if($request->extra==1){
        $item=CartItem::create([
            'cart_id'=>$cart->id,
            'type_id'=>$request->type_id,
            'amount'=>$request->amount,
            'extra'=>1,
            'price'=>$request->price,
        ]);
        }
    }
     return response()->json([
        'meals added successfully'
        ]);

}
if($product->available==0)
    {
     return response()->json([
        'this meal is not available'
        ]);
    }

}

    public function createcart($amount,$price,$branch_id){
        $user_id=Auth::id();
        $cart=Cart::create([
            'user_id'=>$user_id,
            'item_number'=>$amount,
            'total_price'=>$price,
            'branch_id'=>$branch_id,
        ]);
        return $cart;
    }

    public function addexistingmeal($amount,$price,$extra,$type_id,$cart_id){
        $item=CartItem::where('type_id',$type_id)->
                        where('cart_id',$cart_id)->
                        where('extra',$extra)->first();
        $item->amount +=$amount;
        $item->price += $price;
        $item->save();

    }

    public function addone_without_cart($request){
     $type=Type::where('id',$request->type_id)->first();
     $price=0;
     if($request->extra==0){
        $price=$type->price;
     }
    if($request->extra==1){
        $price=$type->supportprice;
     }
     $newprice=$request->lastprice+$price;
      return $newprice;
    }

     public function minusone_without_cart($request){
     $type=Type::where('id',$request->type_id)->first();
     $price=0;
     if($request->extra==0){
        $price=$type->price;
     }
    if($request->extra==1){
        $price=$type->supportprice;
     }
     $newprice=$request->lastprice-$price;
     if($newprice > 0)
        {
       return $newprice;
     }
     else{
        return 0;
     }

    }

    public function addone_with_cart($request){

        $cart_item=CartItem::where('id',$request->item_id)->first();
        $cart=$cart_item->cart;
        $type=$cart_item->type;
        if($type->available==1)
            {
        $cart_item->amount += 1;
        $additional_price=0;
          if($cart_item->extra==0){
        $additional_price=$type->price;
        }
        if($cart_item->extra==1){
        $additional_price=$type->supportprice;
        }
        $cart_item->price += $additional_price;
        $cart->total_price += $additional_price;
        $cart->item_number += 1;

         $cart->save();
         $cart_item->save();
        return response()->json([
            'item added successfully'
        ]);
        }
        else{
            return response()->json([
            'this meal is not available'
        ]);
        }

    }

    public function minusone_with_cart($request)
    {
        $cart_item = CartItem::where('id', $request->item_id)->firstOrFail();
    $cart = $cart_item->cart;
    $type = $cart_item->type;
    $price = 0;

    // إذا كان المنتج واحد، نحذفه مباشرة
    if ($cart_item->amount == 1) {
        $price = $cart_item->price;
        $cart->total_price -= $price;
        $cart->item_number -= 1;
        $cart_item->delete();
    }
    // إذا كان أكثر من واحد، نقلل الكمية
    else {
        $cart_item->amount -= 1;

        if ($cart_item->extra == 0) {
            $price = $type->price;
        } elseif ($cart_item->extra == 1) {
            $price = $type->supportprice;
        }

        $cart_item->price -= $price;
        $cart->total_price -= $price;
        $cart->item_number -= 1;
        $cart_item->save();
    }

    $cart->save();

    return response()->json([
        'message' => 'Item updated successfully'
    ]);

    }

    public function show_coupons($id){
        $coupons=Coupon::where('branch_id',$id)->get();
            return response()->json([
        'all coupons' => $coupons
    ]);

    }

    public function confirm_delivery_order($request){
        $cart=Cart::where('id',$request->cart_id)->first();
        $cart_item=$cart->cartitems;
        $finalPrice=0;
        $coupon = null;
        if($request->coupon_id){
            $coupon=Coupon::where('id',$request->coupon_id)->first();
          if ($coupon)
                {
            $check = $coupon->isValid($cart->total_price);

            if (!$check['valid']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $check['reason'],
                ], 400);
            }

            // لو الكوبون صالح
            $finalPrice = $coupon->applyDiscount($cart->total_price);
        }

        }
        else{
            $finalPrice=$cart->total_price;
        }
        $delivery_order=DeliveryOrder::create([
            'note'=>$request->note,
            'address'=>$request->address,
            'branch_id'=>$cart->branch_id,
            'coupon_id'=>$request->coupon_id,
            'user_id'=>$cart->user_id,
            'item_number'=>$cart->item_number,
            'total_price'=>$finalPrice,
        ]);
        foreach($cart_item as $item){
            $item=DeliveryOrderItem::create([
                'type_id'=>$item->type_id,
                'price'=>$item->price,
                'amount'=>$item->amount,
                'extra'=>$item->extra,
                'delivery_order_id'=>$delivery_order->id,
            ]);
        }
        $original_price=$cart->total_price;
        $cart->delete();

            return response()->json([
             'original_price' => $original_price,
             'discount'       => $coupon ? $coupon->value . '%' : '0%',
             'final_price'    => $finalPrice,
             'order info'=>$delivery_order,
             'order details'=>$delivery_order->deliveryorderitem,
         ]);

    }


        public function confirm_table_order($request){
        $cart=Cart::where('id',$request->cart_id)->first();
        $cart_item=$cart->cartitems;
        $finalPrice=0;
        $coupon = null;
        if($request->coupon_id){
            $coupon=Coupon::where('id',$request->coupon_id)->first();
         if ($coupon)
                {
            $check = $coupon->isValid($cart->total_price);

            if (!$check['valid']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $check['reason'],
                ], 400);
            }

            // لو الكوبون صالح
            $finalPrice = $coupon->applyDiscount($cart->total_price);
        }
        }
        else{
            $finalPrice=$cart->total_price;
        }
        $table_order=TableOrder::create([
            'note'=>$request->note,
            'table_number'=>$request->table_number,
            'branch_id'=>$cart->branch_id,
            'coupon_id'=>$request->coupon_id,
            'user_id'=>$cart->user_id,
            'item_number'=>$cart->item_number,
            'total_price'=>$finalPrice,
        ]);
        foreach($cart_item as $item){
            $item=TableOrderItem::create([
                'type_id'=>$item->type_id,
                'price'=>$item->price,
                'amount'=>$item->amount,
                'extra'=>$item->extra,
                'table_order_id'=>$table_order->id,
            ]);
        }
        $original_price=$cart->total_price;
        $cart->delete();

            return response()->json([
             'original_price' => $original_price,
             'discount'       => $coupon ? $coupon->value . '%' : '0%',
             'final_price'    => $finalPrice,
             'order info'=>$table_order,
             //'order details'=>$table_order->tableorderitem,
         ]);

    }


     public function confirm_self_order($request){
        $cart=Cart::where('id',$request->cart_id)->first();
        $cart_item=$cart->cartitems;
        $finalPrice=0;
        $coupon = null;
        if($request->coupon_id){
            $coupon=Coupon::where('id',$request->coupon_id)->first();
            if ($coupon)
                {
            $check = $coupon->isValid($cart->total_price);

            if (!$check['valid']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $check['reason'],
                ], 400);
            }

            // لو الكوبون صالح
            $finalPrice = $coupon->applyDiscount($cart->total_price);
        }

    }
        else{
            $finalPrice=$cart->total_price;
        }
        $self_order=SelfOrder::create([
            'note'=>$request->note,
            'branch_id'=>$cart->branch_id,
            'coupon_id'=>$request->coupon_id,
            'user_id'=>$cart->user_id,
            'item_number'=>$cart->item_number,
            'total_price'=>$finalPrice,
        ]);
        foreach($cart_item as $item){
            $item=SelfOrderItem::create([
                'type_id'=>$item->type_id,
                'price'=>$item->price,
                'amount'=>$item->amount,
                'extra'=>$item->extra,
                'self_order_id'=>$self_order->id,
            ]);
        }
        $original_price=$cart->total_price;
        $cart->delete();

            return response()->json([
             'original_price' => $original_price,
             'discount'       => $coupon ? $coupon->value . '%' : '0%',
             'final_price'    => $finalPrice,
             'order info'=>$self_order,
             'order details'=>$self_order->selforderitem,
         ]);

    }





}
