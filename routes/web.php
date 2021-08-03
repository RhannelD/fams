<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\LoginLivewire;
use App\Http\Livewire\DashboardLivewire;
use App\Http\Livewire\ScholarLivewire;
use App\Http\Livewire\OfficerLivewire;
use App\Http\Livewire\ScholarshipLivewire;
use App\Http\Livewire\ScholarshipProgramLivewire;
use App\Http\Livewire\ScholarshipRequirementEditLivewire;
use App\Http\Livewire\ScholarshipPostOpenLivewire;
use App\Http\Livewire\ResponseLivewire;

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
})->name('index');

Route::get('/login', LoginLivewire::class)->name('login.index')->middleware('auth.login');
Route::get('/dashboard', DashboardLivewire::class)->name('dashboard')->middleware('auth.main');
Route::get('/officer', OfficerLivewire::class)->name('officer')->middleware('auth.main');
Route::get('/scholar', ScholarLivewire::class)->name('scholar')->middleware('auth.main');
Route::get('/scholarship', ScholarshipLivewire::class)->name('scholarship')->middleware('auth.main');
Route::get('/scholarship/{id}/{tab}/{requirement_id?}', ScholarshipProgramLivewire::class)->name('scholarship.program')->middleware('auth.main');
Route::get('/requirement/{id}/edit', ScholarshipRequirementEditLivewire::class)->name('requirement.edit')->middleware('auth.main');
Route::get('/post/{id}', ScholarshipPostOpenLivewire::class)->name('post.show')->middleware('auth.main');
Route::get('/response/{id}', ResponseLivewire::class)->name('reponse')->middleware('auth.main');

Route::get('/try', [HomeController::class, 'index'])->name('try');