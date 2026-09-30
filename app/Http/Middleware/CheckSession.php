<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // Test: mettre une valeur en session
        if (!$request->session()->has('test_value')) {
            $request->session()->put('test_value', 'Test réussi - ' . time());
        }
        
        return $next($request);
    }
}
