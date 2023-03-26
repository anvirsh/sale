<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;
//use DataTables;
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

use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;


 

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
	 //Products
	  Route::get('/productsall', [ProductController::class, 'index'])->name('products');
	  Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
	  Route::post('/products', [ProductController::class, 'store'])->name('products.store');
	  Route::get('/products/{id}', [ProductController::class, 'edit'])->name('products.edit');
	  Route::post('/products/{id}', [ProductController::class, 'update'])->name('products.update');
	  Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
	  //Orders
	  Route::get('/ordersall', [OrderController::class, 'index'])->name('orders'); 
	  Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
	  Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
	  Route::get('/orders/{id}', [OrderController::class, 'edit'])->name('orders.edit');
	  Route::post('/orders/update/{id}', [OrderController::class, 'update'])->name('orders.update');
	  Route::post('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
	  // API
	  Route::get('/apis', [ApiController::class, 'index'])->name('apis');
	// Route::post('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
	// Route::resource('products', ProductController::class);
	// Route::get('/products', function () {
    //   return view('products.index');	   	    
		   // $model = App\Models\Product::query(); 
          //  return DataTables::of($model)->toJson();
	   
    // })->name('products');
	
	//Route::get('/orders', function () {
    //   return view('orders.index');
   // })->name('orders');
	
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
