<?php

namespace App\Core\Http\Routes;

use App\Support\Http\Routing\RoutesFile;

class Web extends RoutesFile
{
    public function routes()
    {
        $this->router->get('/', fn() => view('welcome'));

        $this->router->get('/documentation', function () {
            return \File::get(public_path() . '/api-documentation/index.html');
        });
    }
}
