<?php

use App\Http\Controllers\DashbaordController;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('pages.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashbaordController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['role:admin,manager,staff'])->group(function () {
    Route::get('/transactions/adjustment-stock', [TransactionController::class, 'adjustmentStock'])->name('transactions.adjustment-stock');
    Route::post('/transactions/adjustment-stock', [TransactionController::class, 'storeAdjustmentStock'])->name('transactions.store-adjustment-stock');
    Route::resource('transactions', TransactionController::class);
    Route::get('/item-master/{id}/detail', [ItemMasterController::class, 'detail'])->name('item-master.detail');
    Route::resource('item-master', ItemMasterController::class);
});

Route::middleware('role:admin')->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
