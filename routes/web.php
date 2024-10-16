<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\clincStockController;
use App\Http\Controllers\StockTransactions;
use App\Http\Controllers\StockTransactionsController;
use App\Http\Controllers\distributeStock;
use App\Http\Controllers\distributeStockController;
use App\Http\Controllers\MailerController;
use App\Http\Controllers\mainStockController;
use App\Http\Controllers\printcontroller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\requestController;
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
//search main stock
Route::get('/mainstock/search',[mainStockController::class,  'searchmain'])->middleware('auth')->name('searchmainstock');
//show mainstock
Route::get('/mainstock',[mainStockController::class, 'showmain'])->middleware('auth')->name('mainstock');
//update to mainstock
Route::patch('/mainstock/{stock_item}',[mainStockController::class, 'updatemain'])->middleware('auth')->name('updateStock');
//Add new stock item
Route::post('/mainstock',[mainStockController::class, 'addnewitem'])->middleware('auth')->name('addnewitem');

//transaction journal search
Route::post('/StockTransactions/search',[StockTransactionsController::class, 'seachjournal'])->middleware('auth')->name('searchStock');
//printing report routes
Route::post('/StockTransactions/search/print',[printcontroller::class, 'printstransactionresults'])->middleware('auth')->name('printstransactionresults');
    
Route::get('/registered',[RegisteredUserController::class, 'create'])->middleware('auth')->name('registerationform');
Route::post('/registered',[RegisteredUserController::class, 'store'])->middleware('auth')->name('registered');
//recieve stock
Route::get('/clinicstock/pendingstock',[clincStockController::class, 'showpending'])->middleware('auth')->name('pendingstock');
Route::get('/clinicstock/receivedstock',[clincStockController::class, 'receivedstock'])->middleware('auth')->name('receivedstock');
Route::patch('/clinicstock/pendingstock/update',[clincStockController::class, 'changestatus'])->middleware('auth')->name('changestatus');
Route::get('/clinicstock/search',[clincStockController::class,  'searchpstock'])->middleware('auth')->name('searchpstock');
Route::post('/clinicstock/search/rstock',[clincStockController::class, 'searchrstock'])->middleware('auth')->name('searchrstock');

//making requesting stock
Route::get('/requeststock',[clincStockController:: class, 'requeststock'])->middleware('auth')->name('requeststock');
Route::post('/requeststock/save',[clincStockController::class, 'saverequest'])->middleware('auth')->name('saverequest');

//handling requests stocks
Route::get('/requests',[requestController::class, 'showrequests'])->middleware('auth')->name('showrequests');
Route::get('/requests/approved',[requestController::class, 'showarequests'])->middleware('auth')->name('showarequests');
Route::get('/requests/denied',[requestController::class, 'showdrequests'])->middleware('auth')->name('showdrequests');
Route::get('/requests/all',[requestController::class, 'showallrequests'])->middleware('auth')->name('showallrequests');
//search request
Route::post('/requests/search',[requestController::class, 'searchrequests'])->middleware('auth')->name('searchrequests');

Route::post('/requests/view',[requestController::class, 'viewrequest'])->middleware('auth')->name('viewrequest');
Route::get('/clinicstock',[clincStockController::class, 'getclinicstock'])->middleware('auth')->name('getclinicstock');
//distribute stock
Route::patch('/mainstock/dis/{stock_item}',[requestController::class, 'approverequest'])->middleware('auth')->name('approverequest');
Route::post('/sendemail', [MailerController::class, 'sendEmail'])->name('sendEmail');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
