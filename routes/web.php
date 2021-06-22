<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\Login;
use App\Http\Livewire\Main;

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

Route::get('/login', Login::class)->name('login.index');

Route::get('/main/{page?}', Main::class)->name('main');
