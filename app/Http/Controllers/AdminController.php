<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CouponRequest;
use App\Repositories\AdminRepository;
use App\Http\Requests\EditCouponRequest;

class AdminController extends Controller
{
    protected $adminrepo;

    public function __construct(AdminRepository $adminrepo )
    {
        $this->adminrepo = $adminrepo;
    }

    public function make_meal_unavailable($id){
      return $this->adminrepo->make_meal_unavailable($id);
    }
    public function add_coupon(CouponRequest $request){
        return $this->adminrepo->addcoupon($request);
    }
       public function deletecoupon($id){
        return $this->adminrepo->deletecoupon($id);
    }
         public function edit_min_order(EditCouponRequest $request){
        return $this->adminrepo->edit_min_order($request);
    }
         public function edit_value(EditCouponRequest $request){
        return $this->adminrepo->edit_value($request);
    }
         public function edit_expires_at(EditCouponRequest $request){
        return $this->adminrepo->edit_expires_at($request);
    }

    public function show_coupons(){
        return $this->adminrepo->show_coupons();
    }


    }



