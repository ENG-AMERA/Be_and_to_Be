<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/refresh', [UserController::class, 'refresh'])->name('refresh');
    Route::post('/me', [UserController::class, 'me'])->name('me');
});


Route::middleware(['auth:api', 'adminrole'])->group(function () {
  //  Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

});

Route::middleware(['auth:api', 'ownerrole'])->group(function () {
    Route::post('/AddAdmin', [OwnerController::class, 'AddAdmin']);
    Route::post('/addbranch', [OwnerController::class, 'addbranch']);
    Route::post('/AddMainCategories', [OwnerController::class, 'AddMainCategories']);
    Route::post('/AddMeals', [OwnerController::class, 'AddMeals']);
    Route::post('/editprice', [OwnerController::class, 'editprice']);
    Route::post('/deletemaincategory/{id}', [OwnerController::class, 'deletemaincategory']);//main category id
    Route::post('/deletemeal/{id}', [OwnerController::class, 'deletemeal']);//meal id
    Route::post('/deletetype/{id}', [OwnerController::class, 'deletetype']);//type id

});


Route::middleware(['auth:api', 'clientrole'])->group(function () {
     Route::get('/getbranches', [ClientController::class, 'getbranches']);
     Route::get('/getmaincategories/{id}', [ClientController::class, 'getmaincategories']);//branch_id
     Route::get('/getmealsofcategory/{id}', [ClientController::class, 'getmealsofcategory']);//category_id
     Route::get('/gettypesofmeal/{id}', [ClientController::class, 'gettypesofmeal']);//meal_id
     Route::post('/addtocart', [ClientController::class, 'addtocart']);

});
