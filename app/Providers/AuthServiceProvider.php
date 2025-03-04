<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Event;
use App\Models\Journal;
use App\Models\News;
use App\Models\Project;
use App\Models\Rating;
use App\Models\User;
use App\Policies\EventPolicy;
use App\Policies\JournalPolicy;
use App\Policies\NewsPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RatingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        Event::class => EventPolicy::class,
        News::class => NewsPolicy::class,
        Journal::class => JournalPolicy::class,
        Rating::class => RatingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-any', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('view', function (User $user, User $model) {
            return $user->is_admin;
        });

        Gate::define('create', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('update', function (User $user, User $model) {
            return $user->is_admin;
        });

        Gate::define('delete', function (User $user, User $model) {
            return $user->is_admin;
        });
    }
}
