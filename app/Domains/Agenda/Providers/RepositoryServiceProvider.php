<?php

namespace App\Domains\Agenda\Providers;

use App\Domains\Agenda\Repositories\EventRepository;
use App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bind the interface to an implementation repository class
     */
    public function register()
    {
        $this->app->bind(
            EventRepositoryInterface::class,
            EventRepository::class
        );
    }
}
