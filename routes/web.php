<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\LoginLivewire;
use App\Http\Livewire\MainLivewire;

use App\Http\Controllers\HomeController;

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
    return redirect()->route('login.index');
});

Route::get('/login', LoginLivewire::class)->name('login.index')->middleware('auth.login');
Route::get('/main/{page?}', MainLivewire::class)->name('main')->middleware('auth');

Route::get('/try', [HomeController::class, 'index']);