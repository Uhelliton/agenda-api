<?php

namespace App\Domains\User\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Domains\User\Http\Routes\Api;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to controller routes.
     *
     * @var string
     */
    protected $namespace = 'App\Domains\User\Http\Controllers';

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
            'middleware' => ['web'],
            'namespace'  => $this->namespace
        ]))->register();
    }

}
