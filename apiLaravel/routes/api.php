<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArtistController;

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


    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {   
    Route::post('/logout', [AuthController::class, 'logout']);
});

    Route::middleware('jwt.auth')->post('/logout', [AuthController::class, 'logout']);

Route::prefix('spotify')->group(function () {
    Route::post('/getToken', [ArtistController::class,'generateToken']);
    Route::get('/tracks/{artist_id}', [ArtistController::class,'getTracks']);
    Route::get('/album/{album_id}', [ArtistController::class,'getAlbum']);
});


Route::prefix('cats')->middleware(['jwt.auth'])->group(function () {
    Route::get('getBreeds',[CatController::class,'getBreeds']);
    Route::get('getImages',[CatController::class,'getImages']);
    Route::get('getImage/{id}',[CatController::class,'getImage']);
    Route::get('getSources',[CatController::class,'getSources']);
    Route::post('uploadImage',[CatController::class,'uploadImage']);
});
