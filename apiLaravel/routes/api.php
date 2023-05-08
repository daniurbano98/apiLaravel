<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\CatController;

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

Route::prefix('spotify')->group(function () {
    Route::post('/getToken', [ArtistController::class,'generateToken']);
    Route::get('/tracks/{artist_id}', [ArtistController::class,'getTracks']);
    Route::get('/album/{album_id}', [ArtistController::class,'getAlbum']);
});


Route::prefix('cats')->group(function () {
    Route::get('getBreed',[CatController::class,'getBreed']);
    Route::get('getImages',[CatController::class,'getImages']);

});