<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
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
    Route::post('/make_meal_unavailable/{id}', [AdminController::class, 'make_meal_unavailable']);//type id
    Route::post('/deletecoupon/{id}', [AdminController::class, 'deletecoupon']);//coupon id
    Route::post('/add_coupon', [AdminController::class, 'add_coupon']);
    Route::post('/edit_expires_at', [AdminController::class, 'edit_expires_at']);
    Route::post('/edit_value', [AdminController::class, 'edit_value']);
    Route::post('/edit_min_order', [AdminController::class, 'edit_min_order']);
    Route::get('/show_coupons', [AdminController::class, 'show_coupons']);
    Route::get('/show_branches_admin', [AdminController::class, 'show_branches_admin']);
    Route::get('/show_main_categories_admin/{id}', [AdminController::class, 'show_main_categories_admin']);//branch id
    Route::get('/show_meals_admin/{id}', [AdminController::class, 'show_meals_admin']);//main category id
    Route::get('/show_types_admin/{id}', [AdminController::class, 'show_types_admin']);//meal id
    Route::get('/show_all_orders', [AdminController::class, 'show_all_orders']);
    Route::post('/accept_order', [AdminController::class, 'accept_order']);
    Route::get('/show_archive_orders', [AdminController::class, 'show_archive_orders']);
    Route::get('/show_last_accepted_orders', [AdminController::class, 'show_last_accepted_orders']);
    Route::post('/make_coupon_unactive/{id}', [AdminController::class, 'make_coupon_unactive']);//coupon id

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
    Route::get('/show_branches', [OwnerController::class, 'show_branches']);
    Route::get('/show_main_categories/{id}', [OwnerController::class, 'show_main_categories']);//branch id
    Route::get('/show_meals/{id}', [OwnerController::class, 'show_meals']);//main category id
    Route::get('/show_types/{id}', [OwnerController::class, 'show_types']);//meal id
    Route::post('/edit_branch_name', [OwnerController::class, 'edit_branch_name']);
    Route::get('/show_admins_withbranches', [OwnerController::class, 'show_admins_withbranches']);

});


Route::middleware(['auth:api', 'clientrole'])->group(function () {
    Route::get('/getbranches', [ClientController::class, 'getbranches']);
    Route::get('/getmaincategories/{id}', [ClientController::class, 'getmaincategories']);//branch_id
    Route::get('/getmealsofcategory/{id}', [ClientController::class, 'getmealsofcategory']);//category_id
    Route::get('/gettypesofmeal/{id}', [ClientController::class, 'gettypesofmeal']);//meal_id
    Route::post('/addtocart', [ClientController::class, 'addtocart']);
    Route::post('/addone_with_cart', [ClientController::class, 'addone_with_cart']);
    Route::post('/minusone_with_cart', [ClientController::class, 'minusone_with_cart']);
    Route::get('/minusone_without_cart', [ClientController::class, 'minusone_without_cart']);
    Route::get('/addone_without_cart', [ClientController::class, 'addone_without_cart']);
    Route::get('/show_coupons/{id}', [ClientController::class, 'show_coupons']);//branch id
    Route::post('/confirm_delivery_order', [ClientController::class, 'confirm_delivery_order']);
    Route::post('/confirm_table_order', [ClientController::class, 'confirm_table_order']);
    Route::post('/confirm_self_order', [ClientController::class, 'confirm_self_order']);
    Route::get('/show_cart/{id}', [ClientController::class, 'show_cart']);//branch_id
});
