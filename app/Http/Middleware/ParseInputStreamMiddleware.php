<?php

namespace App\Http\Middleware;

use Closure;
use ParseInputStream;

class ParseInputStreamMiddleware
{
    public function handle($request, Closure $next)
    {
        // if (in_array($request->getMethod(), ['PUT', 'PATCH'])) {
        //     $data = [];
        //     new ParseInputStream($data);

        //     if (!empty($data['parameters'])) {
        //         foreach ($data['parameters'] as $key => $value) {
        //             $request->request->set($key, $value);
        //         }
        //     }

        //     if (!empty($data['files'])) {
        //         foreach ($data['files'] as $key => $file) {
        //             $request->files->set($key, $file);
        //         }
        //     }
        // }

        return $next($request);
    }
}
