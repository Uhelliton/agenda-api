<?php

namespace App\Domains\Agenda\Http\Routes;

use App\Support\Http\Routing\RoutesFile;
use Illuminate\Support\Facades\Route;

class Api extends RoutesFile
{
    public function routes()
    {
        $this->router->middleware(['auth:sanctum'])->group(function () {
            $this->router->apiResource('/agenda/events', 'EventController');
        });
    }
}
