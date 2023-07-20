<?php

namespace App\Core\Http\Routes;

use App\Support\Http\Routing\RoutesFile;

class Web extends RoutesFile
{
    public function routes()
    {
        $this->router->any('/', function () {});
    }
}
