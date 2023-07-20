<?php

namespace App\Domains\Auth\Http\Routes;

use App\Support\Http\Routing\RoutesFile;
use Illuminate\Support\Facades\Route;

class Api extends RoutesFile
{
    public function routes()
    {
        $this->router->post('/auth/login', 'AuthController@authenticate');
    }
}
