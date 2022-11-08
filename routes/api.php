<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiNoteController;

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

Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);
Route::post('logout', [ApiAuthController::class, 'logout'])->middleware("auth:api");

Route::get("/", [ApiNoteController::class, "index"])->middleware("auth:api");
Route::post("/", [ApiNoteController::class, "store"])->middleware("auth:api");
Route::delete("/{note}", [ApiNoteController::class, "destroy"])->middleware("auth:api");
Route::put("/toggle-status/{note}", [ApiNoteController::class, "toggleStatus"])->middleware("auth:api");
