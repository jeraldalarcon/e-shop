<?php

namespace App\Http\Middleware;

use Closure;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Content-type,X-Requested-With, X-Auth-Token,No-Auth, Authorization, enctype');
        header('Access-Control-Request-Headers: GET, POST, OPTIONS, DELETE, PUT');
        header('Allow: GET, HEAD, PUT, PATCH, DELETE');
        return $next($request);
        
    }
}
