<?php

use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//EMPLOYEE
Route::get('/employee',[EmployeeController::class,'getAll']);
Route::post('/employee/insert',[EmployeeController::class,'insert']);
Route::put('/employee/update',[EmployeeController::class,'update']);
Route::delete('/employee/delete',[EmployeeController::class,'delete']);
Route::post('/employee/detail',[EmployeeController::class,'detail']);

//CUSTOMER
Route::post('/dangki',[CustomerController::class,'dangki']);
Route::post('/doimatkhau',[CustomerController::class,'doimatkhau']);
Route::get('/client',[CustomerController::class,'getAll']);
Route::prefix('customer')->group(function () {
    Route::post('/insert',[CustomerController::class,'dangki']);
    Route::put('/update',[CustomerController::class,'update']);
    Route::delete('/delete',[CustomerController::class,'delete']);
    Route::post('/detail',[CustomerController::class,'detail']);
});

//PRODUCT
Route::get('/getall',[ProductController::class,'getAll']);
Route::prefix('product')->group(function () {
    Route::get('/{id}',[ProductController::class,'getdetail']);
    Route::post('/insert',[ProductController::class,'insert']);
    Route::put('/update',[ProductController::class,'update']);
    Route::post('/insert_detail',[ProductController::class,'insert_detail']);
    Route::put('/update_detail',[ProductController::class,'update_detail']);
    Route::delete('/delete',[ProductController::class,'delete']);
});

//ORDER
Route::post('/order',[OrderController::class,'order']);
Route::get('/get_order',[OrderController::class,'getAll']);
Route::post('/getorderbyphone',[OrderController::class,'getorderbysdt']);
Route::post('/getorderbyname',[OrderController::class,'getorderbyname']);

