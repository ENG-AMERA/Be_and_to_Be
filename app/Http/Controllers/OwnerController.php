<?php

namespace App\Http\Controllers;

use App\Services\OneService;
use Illuminate\Http\Request;
use App\Http\Requests\AddMealRequest;
use App\Repositories\OwnerRepository;
use App\Http\Requests\AddAdminRequest;
use App\Http\Requests\AddBranchRequest;
use App\Http\Requests\AddMainCategoryRequest;

class OwnerController extends Controller
{
    protected $ownerrepo;
    protected $ownerservice;


    public function __construct(OwnerRepository $ownerrepo , OneService $ownerservice  )
    {
        $this->ownerrepo = $ownerrepo;
        $this->ownerservice=$ownerservice;
    }
    public function AddAdmin(AddAdminRequest $request){
        return $this->ownerrepo->addadmin($request);
    }

    public function addbranch(AddBranchRequest $request){
        return $this->ownerrepo->addbranch($request);
    }

    public function AddMainCategories(AddMainCategoryRequest $request){
      return $this->ownerrepo->add_main_category($request);
    }

    public function AddMeals(AddMealRequest $request){
        return $this->ownerservice->addmeal($request);
    }

    public function editprice (Request $request){
        return $this->ownerrepo->editprice($request);
    }

    public function deletetype($type_id){
        return $this->ownerrepo->deletetype($type_id);
    }

     public function deletemeal($meal_id){
        return $this->ownerrepo->deletemeal($meal_id);
    }

     public function deletemaincategory($maincategory_id){
        return $this->ownerrepo->deletemaincategory($maincategory_id);
    }


}
