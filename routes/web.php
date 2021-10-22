<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Auth\LoginLivewire;
use App\Http\Livewire\Auth\SignUpLivewire;
use App\Http\Livewire\Send\SendSmsLivewire;
use App\Http\Livewire\Send\SendEmailLivewire;
use App\Http\Livewire\User\MyAccountLivewire;
use App\Http\Livewire\Officer\OfficerLivewire;
use App\Http\Livewire\Scholar\ScholarLivewire;
use App\Http\Controllers\FilePreviewController;
use App\Http\Livewire\Response\ResponseLivewire;
use App\Http\Livewire\UserChat\UserChatLivewire;
use App\Http\Livewire\Auth\PasswordResetLivewire;
use App\Http\Livewire\Dashboard\DashboardLivewire;
use App\Http\Livewire\Invite\InviteOfficerLivewire;
use App\Http\Livewire\Invite\InviteScholarLivewire;
use App\Http\Livewire\Scholarship\ScholarshipLivewire;
use App\Http\Livewire\Requirement\RequirementPreviewLivewire;
use App\Http\Livewire\ScholarshipPage\ScholarshipPageLivewire;
use App\Http\Livewire\Requirement\RequirementResponsesLivewire;
use App\Http\Livewire\ScholarshipPost\ScholarshipPostOpenLivewire;
use App\Http\Livewire\ScholarshipOfficer\ScholarshipOfficerLivewire;
use App\Http\Livewire\ScholarshipScholar\ScholarshipScholarLivewire;
use App\Http\Livewire\ScholarshipCategory\ScholarshipCategoryLivewire;
use App\Http\Livewire\ScholarScholarship\ScholarScholarshipViewLivewire;
use App\Http\Livewire\ScholarshipDashboard\ScholarshipDashboardLivewire;
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

Route::get('/', function () { return redirect()->route('login.index'); })->name('index');

// Needs to be signed out to access
Route::group(['middleware' => ['user.login']], function(){
    Route::get('/login', LoginLivewire::class)->name('login.index');

    Route::get('/sign-up', SignUpLivewire::class)->name('sign-up.index');

    Route::get('/sign-up/officer/{invite_token}', ScholarshipOfficerInviteLinkLivewire::class)->name('invite');

    Route::get('/password-reset/{token}/{email}', PasswordResetLivewire::class)->name('password.reset');
});

// Needs to be signed in to access
Route::group(['middleware' => ['user.auth']], function(){
    Route::get('/message', UserChatLivewire::class)->name('user.chat');

    Route::get('/dashboard', DashboardLivewire::class)->name('dashboard');

    Route::get('/officer', OfficerLivewire::class)->name('officer');

    Route::get('/scholar', ScholarLivewire::class)->name('scholar');

    Route::get('/scholarship', ScholarshipLivewire::class)->name('scholarship');

    Route::get('/scholarship/scholar', ScholarScholarshipViewLivewire::class)->name('scholar.scholarship');

    Route::get('/file/preview/{id}', [FilePreviewController::class, 'show'])->name('file.preview');

    Route::prefix('/scholarship')->group(function () {
        Route::get('/{scholarship_id}/dashboard', ScholarshipDashboardLivewire::class)->name('scholarship.dashboard');

        Route::get('/{scholarship_id}/home', ScholarshipPageLivewire::class)->name('scholarship.home');

        Route::get('/post/{post_id}', ScholarshipPostOpenLivewire::class)->name('scholarship.post.show');

        Route::get('/{scholarship_id}/officer', ScholarshipOfficerLivewire::class)->name('scholarship.officer');

        Route::get('/{scholarship_id}/scholar', ScholarshipScholarLivewire::class)->name('scholarship.scholar');

        Route::get('/{scholarship_id}/scholar/invite', ScholarshipScholarInviteImportExcel::class)->name('scholarship.scholar.invite');

        Route::get('/{scholarship_id}/category', ScholarshipCategoryLivewire::class)->name('scholarship.category');
        
        Route::get('/{scholarship_id}/requirement', ScholarshipRequirementLivewire::class)->name('scholarship.requirement');
        
        Route::get('/requirement/{requirement_id}', ScholarshipRequirementOpenLivewire::class)->name('scholarship.requirement.open');
        
        Route::get('/requirement/{requirement_id}/edit', ScholarshipRequirementEditLivewire::class)->name('scholarship.requirement.edit');
        
        Route::get('/requirement/{requirement_id}/responses', ScholarshipRequirementResponseLivewire::class)->name('scholarship.requirement.responses');

        Route::get('/{scholarship_id}/send/email', SendEmailLivewire::class)->name('scholarship.send.email');

        Route::get('/{scholarship_id}/send/sms', SendSmsLivewire::class)->name('scholarship.send.sms');
    });

    Route::prefix('/my-account')->group(function () {
        Route::get('/invite/scholar', InviteScholarLivewire::class)->name('invite.scholar');

        Route::get('/invite/officer', InviteOfficerLivewire::class)->name('invite.officer');

        Route::get('/info', MyAccountLivewire::class)->name('my.account');
    });

    Route::prefix('/requirement')->group(function () {
        Route::get('/reponses', RequirementResponsesLivewire::class)->name('requirement.reponses.list');

        Route::get('/{requirement_id}', RequirementPreviewLivewire::class)->name('requirement.view');

        Route::get('/{requirement_id}/response', ResponseLivewire::class)->name('requirement.response');
    });
});

//----------------------- To be Deleted --------------------------
Route::get('/try', [HomeController::class, 'index'])->name('try');  
