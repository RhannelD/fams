<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        ScholarshipPost::class => ScholarshipPostPolicy::class,
        ScholarshipPostComment::class => ScholarshipPostCommentPolicy::class,
        ScholarshipCategory::class => ScholarshipCategoryPolicy::class,
        ScholarshipRequirement::class => ScholarshipRequirementPolicy::class,
        ScholarshipRequirementItem::class => ScholarshipRequirementItemPolicy::class,
        ScholarshipRequirementItemOption::class => ScholarshipRequirementItemOptionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
