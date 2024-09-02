<?php

use App\Http\Controllers\PracticeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [PracticeController::class, 'index'])->name('index');
Route::post('store', [PracticeController::class, 'store'])->name('store');
Route::get('info', [PracticeController::class, 'info'])->name('info');
Route::get('delete/{id}', [PracticeController::class, 'delete'])->name('delete');
