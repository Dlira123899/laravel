<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Simulator for Factories
Route::get('/simulator', function () {

    $result = User::factory()->count(2)->create();

    dd($result);
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/admin', AdminDashboardController::class);
});

// Guest
Route::middleware(['auth', 'role:guest'])->group(function () {
    Route::resource('/guest', GuestDashboardController::class);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Access token
Route::get('/token/create', function (Request $request) {
    $token = $request->user()->createToken("admin_token");
    return ['token' => $token->plainTextToken];
})->middleware(['auth', 'role:admin']);

require __DIR__ . '/auth.php';
