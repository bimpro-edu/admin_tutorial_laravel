<?php

namespace ThaoHR\Providers;

use Route;
use ThaoHR\Repositories\Role\RoleRepository;
use ThaoHR\Repositories\Session\SessionRepository;
use ThaoHR\Repositories\User\UserRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ThaoHR\Repositories\Client\ClientRepository;
use ThaoHR\Repositories\Campaign\CampaignRepository;
use ThaoHR\Repositories\SaleStage\SaleStageRepository;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your web routes file.
     * In addition, it is set as the URL generator's root namespace.
     * @var string
     */
    protected $webNamespace = 'ThaoHR\Http\Controllers\Web';

    /**
     * This namespace is applied to the controller routes in your api routes file.
     * @var string
     */
    protected $apiNamespace = 'ThaoHR\Http\Controllers\Api';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->bindUser();
        $this->bindRole();
        $this->bindSession();
        $this->bindClient();
        $this->bindCampaign();
        $this->bindSaleStage();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        if ($this->app['config']->get('auth.expose_api')) {
            $this->mapApiRoutes();
        }

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'namespace' => $this->webNamespace,
            'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->apiNamespace,
            'prefix' => 'api',
        ], function () {
            require base_path('routes/api.php');
        });
    }

    private function bindUser()
    {
        $this->bindUsingRepository('user', UserRepository::class);
    }

    private function bindRole()
    {
        $this->bindUsingRepository('role', RoleRepository::class);
    }
    
    private function bindClient()
    {
        $this->bindUsingRepository('client', ClientRepository::class);
    }
    
    private function bindCampaign()
    {
        $this->bindUsingRepository('campaign', CampaignRepository::class);
    }
    
    private function bindSaleStage()
    {
        $this->bindUsingRepository('saleStage', SaleStageRepository::class);
    }

    private function bindSession()
    {
        $this->bindUsingRepository('session', SessionRepository::class);
    }

    private function bindUsingRepository($entity, $repository, $method = 'find')
    {
        Route::bind($entity, function ($id) use ($repository, $method) {
            if ($object = app($repository)->$method($id)) {
                return $object;
            }

            throw new NotFoundHttpException("Resource not found.");
        });
    }
}