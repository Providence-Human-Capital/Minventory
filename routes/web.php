<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\clincStockController;
use App\Http\Controllers\StockTransactions;
use App\Http\Controllers\StockTransactionsController;
use App\Http\Controllers\distributeStock;
use App\Http\Controllers\distributeStockController;
use App\Http\Controllers\mainStockController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\RouteRegistrar;
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
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//show add form
Route::get('/StockTransactions',[StockTransactionsController::class, 'show'])->middleware('auth')->name('StockTransactions');
//store item
Route::post('/StockTransactions',[StockTransactionsController::class, 'store'])->middleware('auth')->name('storeStock');
//show add form
Route::get('/distributeStock',[distributeStockController::class, 'show'])->middleware('auth')->name('procurer.distributeStock');
//search main stock
Route::get('/mainstock/search',[mainStockController::class,  'searchmain'])->middleware('auth')->name('searchmainstock');
//show mainstock
Route::get('/mainstock',[mainStockController::class, 'showmain'])->middleware('auth')->name('mainstock');
//update to mainstock
Route::patch('/mainstock/{stock_item}',[mainStockController::class, 'updatemain'])->middleware('auth')->name('updateStock');
//Add new stock item
Route::post('/mainstock',[mainStockController::class, 'addnewitem'])->middleware('auth')->name('addnewitem');

//update frommainstock
Route::patch('/mainstock/dis/{stock_item}',[mainStockController::class, 'distributemain'])->middleware('auth')->name('distributeStock');
//transaction journal search
Route::post('/StockTransactions/search',[StockTransactionsController::class, 'seachjournal'])->middleware('auth')->name('searchStock');
    
Route::get('/registered',[RegisteredUserController::class, 'create'])->middleware('auth');
Route::post('/registered',[RegisteredUserController::class, 'store'])->middleware('auth')->name('registered');
//recieve stock
Route::get('/clinicstock/pendingstock',[clincStockController::class, 'showpending'])->middleware('auth')->name('pendingstock');
Route::patch('/clinicstock/pendingstock/update',[clincStockController::class, 'changestatus'])->middleware('auth')->name('changestatus');
//requesting stock
Route::get('/requeststock',[clincStockController:: class, 'requeststock'])->middleware('auth')->name('requeststock');
Route::post('/requeststock',[clincStockController::class, 'saverequest'])->middleware('auth')->name('saverequest');

Route::get('/clinicstock',[clincStockController::class, 'avenue81'])->middleware('auth')->name('avenue81');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
