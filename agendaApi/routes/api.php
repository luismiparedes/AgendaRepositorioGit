<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use GuzzleHttp\Middleware;
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

Route::prefix('users')->group(function() {
    Route::post('/create',[UserController::class,"createUser"]);
    Route::post('/login',[UserController::class,"loginUser"]);
    Route::post('/sendEmail',[UserController::class,"sendEmail"]);

    Route::get('/seeList',[UserController::class,"seeListUsers"])->middleware('client');
    Route::post('/changePassword',[UserController::class,"changePassword"])->middleware('client');
    Route::post('/delete',[UserController::class,"deleteUser"])->middleware('client');

    Route::post('/logoutUser',[UserController::class,"logoutUser"])->middleware('client');

    //Request= name(string),number(string),photoContact(binary)
    Route::post('/addContact',[ContactController::class,"addContact"])->middleware('client');
    
    //Se obtiene el id del usuario mediante el token
    Route::get('/listContact',[ContactController::class,"listContact"])->middleware('client');

    //Request= name(string),number(string) se comparan, sale un contacto , y se elimina
    Route::post('/deleteContact',[ContactController::class,"deleteContact"])->middleware('client');

    //Request= name(string),number(string) se comparan, sale un contacto , y se modifica
    Route::post('/modifyByName',[ContactController::class,"modifyByName"])->middleware('client');

   //Request= name(string),number(string) se comparan, sale un contacto , y se modifica
    Route::post('/modifyByNumber',[ContactController::class,"modifyByNumber"])->middleware('client');

    



    // Route::post('/seeList',[UserController::class,"seeListUsers"])->middleware('rol');
});




