<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\AddOneWithCartRequest;
use App\Http\Requests\ConfirmSelfOrderRequest;
use App\Http\Requests\MinusOneWithCartRequest;
use App\Http\Requests\AddOneWithoutCartRequest;
use App\Http\Requests\ConfirmTableOrderRequest;
use App\Http\Requests\MinusOneWithoutCartRequest;
use App\Http\Requests\ConfirmDeliveryOrderRequest;

class ClientController extends Controller
{
    protected $clientrepo;

    public function __construct(ClientRepository $clientrepo )
    {
        $this->clientrepo = $clientrepo;
    }

    public function getbranches(){
   return $this->clientrepo->getbranches();
    }

       public function getmaincategories($branch_id){
   return $this->clientrepo->getmaincategories($branch_id);
    }

    public function getmealsofcategory($category_id){
   return $this->clientrepo->getmealsofcategory($category_id);
    }

     public function gettypesofmeal($meal_id){
   return $this->clientrepo->gettypesofmeal($meal_id);
    }

    public function addtocart(AddToCartRequest $request){
        return $this->clientrepo->addtocart($request);
    }


    public function minusone_with_cart(MinusOneWithCartRequest $request){
        return $this->clientrepo->minusone_with_cart($request);
    }

        public function addone_with_cart(AddOneWithCartRequest $request){
        return $this->clientrepo->addone_with_cart($request);
    }

            public function minusone_without_cart(MinusOneWithoutCartRequest $request){
        return $this->clientrepo->minusone_without_cart($request);
    }
            public function addone_without_cart(AddOneWithoutCartRequest $request){
        return $this->clientrepo->addone_without_cart($request);
    }
              public function show_coupons($id){
        return $this->clientrepo->show_coupons($id);
    }

        public function confirm_delivery_order(ConfirmDeliveryOrderRequest $request)
        {
        return $this->clientrepo->confirm_delivery_order($request);
    }

        public function confirm_table_order(ConfirmTableOrderRequest $request)
        {
        return $this->clientrepo->confirm_table_order($request);
    }
        public function confirm_self_order(ConfirmSelfOrderRequest $request)
        {
        return $this->clientrepo->confirm_self_order($request);
    }

    public function show_cart($branch_id){
     return $this->clientrepo->show_cart($branch_id);
    }








}
