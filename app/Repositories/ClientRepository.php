<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Meal;
use App\Models\Branch;
use App\Models\CartItem;
use App\Models\MainCategory;
use App\Models\Type;
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
    $cart=Cart::where('user_id',$user_id)->first();
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
        $cart=$this->createcart($request->amount,$request->price);
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

    public function createcart($amount,$price){
        $user_id=Auth::id();
        $cart=Cart::create([
            'user_id'=>$user_id,
            'item_number'=>$amount,
            'total_price'=>$price
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

    // public function addone_withoutcart($request){
    //  $type=Type::where('id',$request->type_id)->first();
    //  $price=0;
    //  if($request->extra==0){
    //     $price=$type->price;
    //  }
    // if($request->extra==1){
    //     $price=$type->supportprice;
    //  }
    //  $newprice=$request->lastprice+$price;
    //   return $newprice;
    // }

}
