<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\Collection;
use Carbon\Carbon;

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
    if(Auth::user()){
        return redirect()->back();
    }else{
        return view('auth.login');
    }
});

Route::get('/dashboard', function () {
    $today = Carbon::today();
    $collection_today = Collection::where('created_at', '>=', $today)->pluck('amount')->sum();
    $collection_today_count = Collection::where('created_at', '>=', $today)->count();
    $collection_total = Collection::all()->pluck('amount')->sum();
    $collection_total_count = Collection::all()->count();
    return view('backend.pages.dashboard', compact('collection_total', 'collection_today', 'collection_today_count', 'collection_total_count'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/addCollection', [CollectionController::class, 'addCollection'])->name('addCollection');
    Route::get('/collections', [PageController::class, 'collections'])->name('collections');
    Route::get('/customer/collections/{id}', [PageController::class, 'customerCollections'])->name('customerCollections');
    Route::get('/customers', [PageController::class, 'customers'])->name('customers');
    Route::get('/dashboard/allCollections', [PageController::class, 'allCollections'])->name('allCollections');
    Route::get('/change-password', [PageController::class, 'changePassword'])->name('changePassword');
    Route::post('/dashboard/getCollections', [PageController::class, 'getCollections'])->name('getCollections');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/addUser', [UserController::class, 'addUser'])->name('addUser');
    Route::get('/users', [PageController::class, 'getUsers'])->name('getUsers');
});



Route::middleware(['auth', 'manager'])->group(function () {
    Route::get('/dashboard/generalReport', [PageController::class, 'generalReport'])->name('generalReport');
    Route::get('/dashboard/reportDate', [PageController::class, 'reportDate'])->name('reportDate');
    Route::post('/addCustomer', [CustomerController::class, 'addCustomer'])->name('addCustomer');
    Route::post('/dashboard/filterReport', [ReportController::class, 'filterReport'])->name('filterReport');
});

require __DIR__.'/auth.php';
