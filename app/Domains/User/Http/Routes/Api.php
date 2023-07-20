<?php

namespace App\Domains\User\Http\Routes;

use App\Support\Http\Routing\RoutesFile;

class Api extends RoutesFile
{
    public function routes()
    {
        $this->router->get('/usersx', function () {
            dd(15);
        });
        $this->router->apiResource('/users', 'UserController');


//        Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//            return $request->user();
//        });

    }
}
