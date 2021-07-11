<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\LoginLivewire;
use App\Http\Livewire\DashboardLivewire;
use App\Http\Livewire\ScholarLivewire;
use App\Http\Livewire\OfficerLivewire;
use App\Http\Livewire\ScholarshipLivewire;
use App\Http\Livewire\ScholarshipProgramLivewire;

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
Route::get('/dashboard', DashboardLivewire::class)->name('dashboard')->middleware('auth.main');
Route::get('/officer', OfficerLivewire::class)->name('officer')->middleware('auth.main');
Route::get('/scholar', ScholarLivewire::class)->name('scholar')->middleware('auth.main');
Route::get('/scholarhip', ScholarshipLivewire::class)->name('scholarhip')->middleware('auth.main');
Route::get('/scholarship/{id}/{tab}/{requirement_id?}', ScholarshipProgramLivewire::class)->name('scholarship.program')->middleware('auth.main');

Route::get('/try', [HomeController::class, 'index'])->name('try');