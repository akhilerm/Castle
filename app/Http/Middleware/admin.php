<?php

namespace App\Http\Middleware;

use Closure;

class admin
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
        if ($request->user() === null){
            return redirect('/');
        }
        if ($request->user()['role'] === 0){
            return redirect()->back();
        }
        return $next($request);
    }
}
