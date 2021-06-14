<?php

use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy machine your API!
|
*/

Route::middleware('auth:api')->get('/user/info', function (Request $request) {
    return new UserResource($request->user());
});
Route::middleware(['auth:api'])->group(function () {

    // レポート
    Route::get('report', 'API\ReportController@index');
    Route::post('report', 'API\ReportController@store');
    Route::get('report/{report}', 'API\ReportController@show');
    Route::put('report/{report}', 'API\ReportController@update');
    Route::delete('report/{report}', 'API\ReportController@destroy');
    Route::put('report/sendback/{report}', 'API\ReportController@sendback');
    Route::post('report/download/', 'API\ReportController@download');
    Route::put('report/{report}/correct', 'API\ReportController@correct');

    Route::get('report/line/selector', 'API\ReportController@lineSelector');

    // 従業員
    Route::get('user', 'API\UserController@index');
    Route::post('user', 'API\UserController@store');
    Route::get('user/{user}', 'API\UserController@show');
    Route::put('user/{user}', 'API\UserController@update');
    Route::delete('user/{user}', 'API\UserController@destroy');
    // Route::get('employee/selector', 'API\EmployeeController@selector');
    // Route::get('employee/full_name', 'API\EmployeeController@fullname');

    // 商品
    Route::get('item', 'API\ItemController@index');
    Route::post('item', 'API\ItemController@store');
    Route::get('item/{item}', 'API\ItemController@show');
    Route::put('item/{item}', 'API\ItemController@update');
    Route::delete('item/{item}', 'API\ItemController@destroy');
    Route::get('item/selector', 'API\ItemController@selector');

    Route::get('lesson/selector', 'API\LessonController@index');
    ////
    //顧客
    Route::get('customer', 'API\CustomerController@index');
    Route::post('customer', 'API\CustomerController@store');
    Route::get('customer/{customer}', 'API\CustomerController@show');
    Route::put('customer/{customer}', 'API\CustomerController@update');
    Route::delete('customer/{customer}', 'API\CustomerController@destroy');
    Route::get('customer/selector', 'API\CustomerController@selector');

    Route::get('user', 'API\UserController@index');
    Route::post('user', 'API\UserController@store');
    Route::delete('user/{user}', 'API\UserController@destroy');


    Route::get('review', 'API\ReviewController@index');
    Route::post('review', 'API\ReviewController@store');
    Route::delete('review/{review}', 'API\ReviewController@destroy');
});
