<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   
});

require __DIR__ . '/auth.php';


// route for admin
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard',[AdminController::class,'adminDashboard'])->name('admin.dashboard');
});

// route for manager
Route::middleware(['auth','role:manager'])->group(function () {
    Route::get('/manager/dashboard',[ManagerController::class,'managerDashboard'])->name('manager.dashboard');


});

// route for driver
Route::middleware(['auth','role:driver'])->group(function () {
  
    Route::get('/driver/dashboard',[DriverController::class,'driverDashboard'])->name('driver.dashboard');
});





