<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Http\Requests\AddToCartRequest;

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

}
