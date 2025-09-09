<?php

namespace App\Repositories;

use App\Models\Meal;
use App\Models\Type;
use App\Models\User;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\PhoneNumber;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Hash;

class OwnerRepository{

    public function addadmin($request){
        $user=User::create([
            'phonenumber'=>$request->phonenumber,
            'fullname'=>$request->fullname,
            'password'=>Hash::make($request->password),
            'role'=>'admin',
        ]);
        $admin=Admin::create([
            'user_id'=>$user->id,
            'branch_id'=>$request->branch_id,
        ]);

        return response()->json([
            'message'=>'Admin added successfully',
        ]);
    }


    public function addbranch($request){
        if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageExtension = $image->getClientOriginalExtension();
    $imageName = time() . '_' . uniqid() . '.' . $imageExtension;
    $imagePath = 'branches';
    $image->move(public_path($imagePath), $imageName);
    $imageRelativePath = $imagePath . '/' . $imageName;
     }
        $branch=Branch::create([
        'branch_name'=>$request->branch_name,
        'image'=>$imageRelativePath,
        'length'=>$request->length,
        'width'=>$request->width,
        'instagramtoken'=>$request->instatoken,
        'facebooktoken'=>$request->facetoken,
        ]);

        if (is_array($request->phones)) {
        foreach ($request->phones as $phone) {
            PhoneNumber::create([
                'phone'      => $phone,
                'branch_id'  => $branch->id,
            ]);
        }
    }
    return response()->json(['message' => 'Branch and phones added successfully']);
    }

    public function add_main_category($request){

     if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageExtension = $image->getClientOriginalExtension();
    $imageName = time() . '_' . uniqid() . '.' . $imageExtension;
    $imagePath = 'maincategory';
    $image->move(public_path($imagePath), $imageName);
    $imageRelativePath = $imagePath . '/' . $imageName;

}
      $category=MainCategory::create([
        'image' => $imageRelativePath,
        'name' =>  $request->name,
        'branch_id'=>$request->branch_id,
      ]);
      return response()->json(['message' => 'Main Category added successfully']);
    }

    public function addmealwithouttype($request)
    {
    if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageExtension = $image->getClientOriginalExtension();
    $imageName = time() . '_' . uniqid() . '.' . $imageExtension;
    $imagePath = 'Meal';
    $image->move(public_path($imagePath), $imageName);
    $imageRelativePath = $imagePath . '/' . $imageName;
}
        $meal=Meal::create([
            'name'=>$request->mealname,
            'description'=>$request->description,
            'image'=>$imageRelativePath,
            'maincategory_id'=>$request->maincategory_id,
        ]);

        $type=Type::create([
        'name'=>$request->mealname,
        'price'=>$request->price,
        'supportprice'=>$request->extraprice,
        'meal_id'=>$meal->id,
        ]);
  return response()->json(['message' => 'Meal added successfully']);
    }



    public function addmealwithtypes($request){

        if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageExtension = $image->getClientOriginalExtension();
    $imageName = time() . '_' . uniqid() . '.' . $imageExtension;
    $imagePath = 'Meal';
    $image->move(public_path($imagePath), $imageName);
    $imageRelativePath = $imagePath . '/' . $imageName;
}
        $meal=Meal::create([
            'name'=>$request->mealname,
            'description'=>$request->description,
            'image'=>$imageRelativePath,
            'maincategory_id'=>$request->maincategory_id,
        ]);


    $names = $request->input('tname');
    $prices = $request->input('tprice');
    $supportprice=$request->input('textraprice');


    $count = count($names);

       for ($i = 0; $i < $count; $i++) {
        Type::create([
            'name' => $names[$i],
            'meal_id' => $meal->id,
            'price' => $prices[$i],
            'supportprice' => $supportprice[$i],

        ]);
    }

  return response()->json(['message' => 'Meal added successfully']);

    }

    public function editprice($request) {
        $type=Type::where('id',$request->type_id)->first();
        if($request->price){
            $type->price=$request->price;
            $type->save();
            return response()->json(['message' => 'Meal updated successfully']);
        }
           if($request->extraprice){
            $type->supportprice=$request->extraprice;
            $type->save();
            return response()->json(['message' => 'Meal updated successfully']);
        }
     return response()->json(['message' => 'price required']);
    }


  public function deletemaincategory($maincategory_id)
  {
    $maincategory=MainCategory::where('id',$maincategory_id)->delete();
    return response()->json(['message' => 'category deleted successfully']);
  }

    public function deletemeal($meal_id)
  {
    $meal=Meal::where('id',$meal_id)->delete();
    return response()->json(['message' => 'meal deleted successfully']);
  }
    public function deletetype($type_id)
  {
    $type=Type::where('id',$type_id)->delete();
    return response()->json(['message' => 'type deleted successfully']);
  }

  public function edit_branch_name($request){
    $branch=Branch::where('id',$request->branch_id)->first();
    $branch->branch_name=$request->new_name;
    $branch->save();
    return response()->json(['message' => 'branch edited successfully']);
  }





}
