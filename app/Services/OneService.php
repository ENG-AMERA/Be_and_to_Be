<?php

namespace App\Services;

use App\Repositories\OwnerRepository;

class OneService{
    protected $ownerrepo;
 public function __construct(OwnerRepository $ownerrepo   )
    {
        $this->ownerrepo = $ownerrepo;
 }
public function addmeal($request){
 if($request->hastypes==0){
      return $this->ownerrepo->addmealwithouttype($request);
 }
 if($request->hastypes==1){
    return  $this->ownerrepo->addmealwithtypes($request);
 }
}

}

