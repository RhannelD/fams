<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Policies\BackupRestorePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Scholarship::class => ScholarshipPolicy::class,
        ScholarshipPost::class => ScholarshipPostPolicy::class,
        ScholarshipPostComment::class => ScholarshipPostCommentPolicy::class,
        ScholarshipCategory::class => ScholarshipCategoryPolicy::class,
        ScholarshipRequirement::class => ScholarshipRequirementPolicy::class,
        ScholarshipRequirementItem::class => ScholarshipRequirementItemPolicy::class,
        ScholarshipRequirementItemOption::class => ScholarshipRequirementItemOptionPolicy::class,
        ScholarshipRequirementAgreement::class => ScholarshipRequirementAgreementPolicy::class,
        ScholarCourse::class => ScholarCoursePolicy::class,
        ScholarResponse::class => ScholarResponsePolicy::class,
        ScholarResponseComment::class => ScholarResponseCommentPolicy::class,
        ScholarshipScholar::class => ScholarshipScholarPolicy::class,
        ScholarshipScholarInvite::class => ScholarshipScholarInvitePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('backup-restore-view', [BackupRestorePolicy::class, 'viewAny']);
        Gate::define('backup-restore-create', [BackupRestorePolicy::class, 'create']);
        Gate::define('backup-restore-download', [BackupRestorePolicy::class, 'download']);
        Gate::define('backup-restore-restore', [BackupRestorePolicy::class, 'restore']);
        Gate::define('backup-restore-delete', [BackupRestorePolicy::class, 'delete']);
        Gate::define('backup-restore-upload', [BackupRestorePolicy::class, 'upload']);
    }
}
