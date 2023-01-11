<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\librarieController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReserveController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::group(['middleware' => ['auth:sanctum','verified']], function() {
    Route::get('/librarie', [librarieController::class, 'list'] )->name('librarie.list');
    Route::resource('/books', BookController::class)->names('book');
    Route::post('/books/{category}', [CategoryController::class, 'Books'])->name('category.books');
    Route::post('/Category/all', [CategoryController::class, 'All'])->name('category.all');
    Route::post('/Reserve/all', [ReserveController::class, 'All'])->name('reserve.all');
    Route::post('/Reserve/delete/{reserve}', [ReserveController::class, 'Delete'])->name('reserve.delete');
    Route::post('/Reserve/{book}/{dias}', [ReserveController::class, 'reserve'])->name('reserve.book');
});
