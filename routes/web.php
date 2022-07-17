<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('screen.screen1');
})->name('screen1');

Route::post('/screen2', [TaskController::class, 'screen2'])->name('screen2');
Route::post('/screen3', [TaskController::class, 'screen3'])->name('screen3');
