<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Accept;
use App\Services\FcmService;
use App\Http\Requests\CouponRequest;
use App\Repositories\AdminRepository;
use App\Repositories\ClientRepository;
use App\Http\Requests\EditCouponRequest;

class AdminController extends Controller
{
    protected $adminrepo;
    protected $clientrepo;
    protected $fcmService;

    public function __construct(AdminRepository $adminrepo , ClientRepository $clientrepo , FcmService $fcmService)
    {
        $this->adminrepo = $adminrepo;
        $this->clientrepo = $clientrepo;
        $this->fcmService =$fcmService;
    }

    public function make_meal_unavailable($id){
      return $this->adminrepo->make_meal_unavailable($id);
    }

       public function make_meal_available($id){
      return $this->adminrepo->make_meal_available($id);
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
    public function show_branches_admin(){
         return $this->clientrepo->getbranches();
    }
    public function show_main_categories_admin($id){
        return $this->clientrepo->getmaincategories($id);
    }
    public function show_meals_admin($id){
        return $this->clientrepo->getmealsofcategory($id);
    }
    public function show_types_admin($id){
        return $this->clientrepo->gettypesofmeal($id);
    }

    public function show_all_orders(){
      return $this->adminrepo->show_all_orders();
    }
      public function accept_order(Accept $request){
      return $this->adminrepo->accept_order($request,$this->fcmService);
    }

    public function show_archive_orders(){
        return $this->adminrepo->show_archive_orders();
    }
    public function show_last_accepted_orders(){
         return $this->adminrepo->show_last_accepted_orders();
    }

    public function make_coupon_unactive($id){
          return $this->adminrepo->make_coupon_unactive($id);
    }


    }



