<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Auth\LoginLivewire;
use App\Http\Livewire\Auth\SignUpLivewire;
use App\Http\Livewire\User\MyAccountLivewire;
use App\Http\Livewire\Officer\OfficerLivewire;
use App\Http\Livewire\Scholar\ScholarLivewire;
use App\Http\Controllers\FilePreviewController;
use App\Http\Livewire\Response\ResponseLivewire;
use App\Http\Livewire\Auth\PasswordResetLivewire;
use App\Http\Livewire\Dashboard\DashboardLivewire;
use App\Http\Livewire\Invite\InviteOfficerLivewire;
use App\Http\Livewire\Invite\InviteScholarLivewire;
use App\Http\Livewire\Scholarship\ScholarshipLivewire;
use App\Http\Livewire\Requirement\RequirementPreviewLivewire;
use App\Http\Livewire\ScholarshipPage\ScholarshipPageLivewire;
use App\Http\Livewire\ScholarshipPost\ScholarshipPostOpenLivewire;
use App\Http\Livewire\ScholarshipOfficer\ScholarshipOfficerLivewire;
use App\Http\Livewire\ScholarshipScholar\ScholarshipScholarLivewire;
use App\Http\Livewire\ScholarshipCategory\ScholarshipCategoryLivewire;
use App\Http\Livewire\ScholarScholarship\ScholarScholarshipViewLivewire;
use App\Http\Livewire\ScholarshipRequirement\ScholarshipRequirementLivewire;
use App\Http\Livewire\ScholarshipScholar\ScholarshipScholarInviteImportExcel;
use App\Http\Livewire\ScholarshipOfficer\ScholarshipOfficerInviteLinkLivewire;
use App\Http\Livewire\ScholarshipRequirement\ScholarshipRequirementOpenLivewire;
use App\Http\Livewire\ScholarshipRequirementEdit\ScholarshipRequirementEditLivewire;
use App\Http\Livewire\ScholarshipRequirementResponse\ScholarshipRequirementResponseLivewire;

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
Route::get('/sign-up', SignUpLivewire::class)->name('sign-up.index')->middleware('auth.login');
Route::get('/password-reset/{token}/{email}', PasswordResetLivewire::class)->name('password.reset')->middleware('auth.login');
Route::get('/invite/officer/{invite_token}', ScholarshipOfficerInviteLinkLivewire::class)->name('invite');

Route::get('/dashboard', DashboardLivewire::class)->name('dashboard')->middleware('auth.main');
Route::get('/officer', OfficerLivewire::class)->name('officer')->middleware('auth.main');
Route::get('/scholar', ScholarLivewire::class)->name('scholar')->middleware('auth.main');
Route::get('/scholarship', ScholarshipLivewire::class)->name('scholarship')->middleware('auth.main');
Route::get('/scholarship/scholar', ScholarScholarshipViewLivewire::class)->name('scholar.scholarship')->middleware('auth.main');

Route::get('/scholarship/{scholarship_id}/home', ScholarshipPageLivewire::class)->name('scholarship.home')->middleware('auth.main');
Route::get('/scholarship/{scholarship_id}/officer', ScholarshipOfficerLivewire::class)->name('scholarship.officer')->middleware('auth.main');
Route::get('/scholarship/{scholarship_id}/scholar', ScholarshipScholarLivewire::class)->name('scholarship.scholar')->middleware('auth.main');
Route::get('/scholarship/{scholarship_id}/scholar/invite', ScholarshipScholarInviteImportExcel::class)->name('scholarship.scholar.invite')->middleware('auth.main');
Route::get('/scholarship/{scholarship_id}/requirement', ScholarshipRequirementLivewire::class)->name('scholarship.requirement')->middleware('auth.main');
Route::get('/scholarship/{scholarship_id}/category', ScholarshipCategoryLivewire::class)->name('scholarship.category')->middleware('auth.main');
Route::get('/scholarship/requirement/{requirement_id}', ScholarshipRequirementOpenLivewire::class)->name('scholarship.requirement.open')->middleware('auth.main');
Route::get('/scholarship/requirement/{requirement_id}/edit', ScholarshipRequirementEditLivewire::class)->name('requirement.edit')->middleware('auth.main');
Route::get('/scholarship/requirement/{requirement_id}/response', ScholarshipRequirementResponseLivewire::class)->name('requirement.response')->middleware('auth.main');
Route::get('/scholarship/post/{post_id}', ScholarshipPostOpenLivewire::class)->name('post.show')->middleware('auth.main');

Route::get('/invite/scholar', InviteScholarLivewire::class)->name('invite.scholar')->middleware('auth.main');
Route::get('/invite/officer', InviteOfficerLivewire::class)->name('invite.officer')->middleware('auth.main');

Route::get('/requirement/{requirement_id}', RequirementPreviewLivewire::class)->name('requirement.view')->middleware('auth.main');
Route::get('/requirement/{requirement_id}/response', ResponseLivewire::class)->name('reponse')->middleware('auth.main');

Route::get('/my-account', MyAccountLivewire::class)->name('my.account')->middleware('auth.main');

Route::get('/file/preview/{id}', [FilePreviewController::class, 'show'])->name('file.preview')->middleware('auth.main');

Route::get('/try', [HomeController::class, 'index'])->name('try');