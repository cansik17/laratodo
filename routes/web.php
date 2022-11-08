<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/email-verification", [UserController::class, "activateAccount"]);
Route::get("/register", [UserController::class, "create"])->middleware("guest");
Route::post("/users", [UserController::class, "store"]);
Route::post('/logout', [UserController::class, 'logout'])->middleware("auth");
Route::get('/login', [UserController::class, 'login'])->name("login")->middleware("guest");
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

Route::get("/", [NoteController::class, "index"]);
Route::post("/", [NoteController::class, "store"]);
Route::delete("/{note}", [NoteController::class, "destroy"]);
Route::get("/render-list", [NoteController::class, "renderList"]);
Route::put("/toggle-status/{note}", [NoteController::class, "toggleStatus"]);


//Admin

// Route::middleware(['auth', 'user-access:admin'])->group(
//     function () {
//         Route::get("/admin/dashboard", [DashboardController::class, "index"]);
//         Route::get("/admin/users/{user}", [DashboardController::class, "show"]);
//         Route::delete("/admin/users/{user}", [DashboardController::class, "destroy"]);
//         Route::put("/admin/users/{user}", [DashboardController::class, "toggleStatus"]);
//     }
// );