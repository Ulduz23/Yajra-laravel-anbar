<?php

use App\Exports\brandsexport;
use App\Http\Controllers\adminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\brandcontroller;
use App\Http\Controllers\clientController;
use App\Http\Controllers\clientscontroller;
use App\Http\Controllers\ordersController;
use App\Http\Controllers\productController;
use App\Http\Controllers\userController;
use App\Http\Controllers\profileController;



Route::group(['middleware'=>'islogin'],function(){

Route::get('brand', [brandcontroller::class, 'index'])->name('brand');
Route::post('bstore', [brandcontroller::class, 'store']);
Route::post('bedit', [brandcontroller::class, 'edit']);
Route::post('bdelete', [brandcontroller::class, 'destroy']);


Route::get('clients', [clientscontroller::class, 'index']);
Route::post('cstore', [clientscontroller::class, 'store']);
Route::post('cedit', [clientscontroller::class, 'edit']);
Route::post('cdelete', [clientscontroller::class, 'destroy']);
 

Route::get('products', [productController::class, 'index']);
Route::post('pstore', [productController::class, 'store']);
Route::post('pedit', [productController::class, 'edit']);
Route::post('pdelete', [productController::class, 'destroy']);


Route::get('/', [ordersController::class, 'index'])->name('orders');
Route::post('orstore', [ordersController::class, 'store']);
Route::post('oredit', [ordersController::class, 'edit']);
Route::post('ordelete', [ordersController::class, 'destroy']);
Route::post('ortesdiq', [ordersController::class, 'tesdiq']);
Route::post('orlegv', [ordersController::class, 'legv']);


//----------------------LOGOOUT-----------------------------------------//

Route::get('/logout',  [userController::class, 'logout'])->name('logout');
    

 
//-----------------------------PROFILE-------------------------------//

Route::get('/myprofile',[profileController::class,'index'])->name('myprofile');

Route::post('/profile',[profileController::class,'profile'])->name('profile');



//-----------------------------ADMIN-------------------------------//

Route::get('admin', [adminController::class, 'index'])->name('admin');
Route::post('adminstore', [adminController::class, 'store']);
Route::post('adminedit', [adminController::class, 'edit']);
Route::post('admindelete', [adminController::class, 'destroy']);


//-----------------------------EXPORTS-------------------------------//

Route::get('/bexport',  [brandcontroller::class, 'export']);
Route::get('/cexport',  [clientscontroller::class, 'export']);
Route::get('/pexport',  [productController::class, 'export']);
Route::get('/oexport',  [ordersController::class, 'export']);

});




Route::group(['middleware'=>'notlogin'],function(){

Route::post('/login',[userController::class,'login'])->name('login');

Route::get('/login', function(){
    return view('login');
})->name('daxilol');


//----------------------------REGISTER--------------------------------------------//

Route::post('/qeydiyyat',[userController::class,'register'])->name('register');

Route::get('/qeydiyyat', function(){
    return view('register');
})->name('qeydiyyat');

//----------------------------FORGOT--------------------------------------------//

Route::get('/forgot',[userController::class,'forgot'])->name('forgot');
Route::post('/forgot',[userController::class,'sendlink'])->name('sendlink');

Route::get('/email',[userController::class,'email_forgot'])->name('email_forgot');

//----------------------------Reset--------------------------------------------//

Route::get('/reset',[userController::class,'reset'])->name('resetform');
Route::post('/reset',[userController::class,'resetparol'])->name('resetparol');

});
