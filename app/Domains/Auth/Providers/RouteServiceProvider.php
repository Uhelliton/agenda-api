<?php

namespace App\Domains\Auth\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Domains\Auth\Http\Routes\Api;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to controller routes.
     * @var string
     */
    protected $namespace = 'App\Domains\Auth\Http\Controllers';

    /**
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
    }

    /**
     * Define routes "api" this domains.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        (new Api([
            'prefix' => 'api',
            'middleware' => ['api'],
            'namespace'  => $this->namespace
        ]))->register();
    }

}
