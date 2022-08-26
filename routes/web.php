<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::get('/', function () {
  return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/show/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::post('/update/{id}', [ProductController::class, 'update'])->name('products.update'); 
Route::post('/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy'); 
Route::get('/search', [ProductController::class, 'search'])->name('products.search'); 