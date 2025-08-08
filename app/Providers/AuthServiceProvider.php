<?php

namespace App\Providers;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Journal;
use App\Models\News;
use App\Models\Project;
use App\Models\Rating;
use App\Models\User;
use App\Policies\CertificatePolicy;
use App\Policies\EventPolicy;
use App\Policies\JournalPolicy;
use App\Policies\NewsPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RatingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\DatabaseNotification;
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
        Certificate::class => CertificatePolicy::class,
        DatabaseNotification::class => NotificationPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        //
    }
}
