<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
class CheckPermission
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
        $permission = $request->route()->getName();

        list($route) = explode('.', $permission);
        if ($request->user()->can($permission)) {
                return $next($request);
        }

       abort(403, 'Usuario no autorizado');
    }
}
