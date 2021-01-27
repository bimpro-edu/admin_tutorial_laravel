<?php

namespace ThaoHR\Providers;

use Carbon\Carbon;
use ThaoHR\Repositories\Announcement\EloquentAnnouncements;
use ThaoHR\Repositories\Activity\ActivityRepository;
use ThaoHR\Repositories\Activity\EloquentActivity;
use ThaoHR\Repositories\Announcement\AnnouncementsRepository;
use ThaoHR\Repositories\Country\CountryRepository;
use ThaoHR\Repositories\Country\EloquentCountry;
use ThaoHR\Repositories\Permission\EloquentPermission;
use ThaoHR\Repositories\Permission\PermissionRepository;
use ThaoHR\Repositories\Role\EloquentRole;
use ThaoHR\Repositories\Role\RoleRepository;
use ThaoHR\Repositories\Session\DbSession;
use ThaoHR\Repositories\Session\SessionRepository;
use ThaoHR\Repositories\User\EloquentUser;
use ThaoHR\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;
use ThaoHR\Http\ViewComposers\NavbarItems;
use ThaoHR\Repositories\Client\ClientRepository;
use ThaoHR\Repositories\Client\EloquentClient;
use ThaoHR\Repositories\SaleStage\EloquentSaleStage;
use ThaoHR\Repositories\SaleStage\SaleStageRepository;
use ThaoHR\Repositories\Campaign\EloquentCampaign;
use ThaoHR\Repositories\Campaign\CampaignRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(config('app.locale'));
        config(['app.name' => setting('app_name')]);
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);
        view()->composer('announcement.partials.navbar.list', NavbarItems::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserRepository::class, EloquentUser::class);
        $this->app->singleton(UserRepository::class, EloquentUser::class);
        $this->app->singleton(RoleRepository::class, EloquentRole::class);
        $this->app->singleton(PermissionRepository::class, EloquentPermission::class);
        $this->app->singleton(SessionRepository::class, DbSession::class);
        $this->app->singleton(CountryRepository::class, EloquentCountry::class);

        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
        $this->app->singleton(ActivityRepository::class, EloquentActivity::class);
        $this->app->singleton(AnnouncementsRepository::class, EloquentAnnouncements::class);
        $this->app->singleton(ClientRepository::class, EloquentClient::class);
        $this->app->singleton(SaleStageRepository::class, EloquentSaleStage::class);
        $this->app->singleton(CampaignRepository::class, EloquentCampaign::class);
    }
}
