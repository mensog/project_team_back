<?php

namespace App\Providers;

use App\Repositories\EventRepository;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\JournalRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Repositories\JournalRepository;
use App\Repositories\NewsRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\RatingRepository;
use App\Repositories\UserRepository;
use App\Repositories\CertificateRepository;
use App\Services\EventService;
use App\Services\Interfaces\EventServiceInterface;
use App\Services\Interfaces\JournalServiceInterface;
use App\Services\Interfaces\NewsServiceInterface;
use App\Services\Interfaces\ProjectServiceInterface;
use App\Services\Interfaces\RatingServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\Interfaces\CertificateServiceInterface;
use App\Services\JournalService;
use App\Services\NewsService;
use App\Services\ProjectService;
use App\Services\RatingService;
use App\Services\UserService;
use App\Services\CertificateService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);

        $this->app->bind(RatingRepositoryInterface::class, RatingRepository::class);
        $this->app->bind(RatingServiceInterface::class, RatingService::class);

        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(EventServiceInterface::class, EventService::class);

        $this->app->bind(JournalRepositoryInterface::class, JournalRepository::class);
        $this->app->bind(JournalServiceInterface::class, JournalService::class);

        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(NewsServiceInterface::class, NewsService::class);

        $this->app->bind(CertificateRepositoryInterface::class, CertificateRepository::class);
        $this->app->bind(CertificateServiceInterface::class, CertificateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
