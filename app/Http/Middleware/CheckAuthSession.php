<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuthSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est en session (déconnecté = redirection obligatoire vers login)
        if (!Session::has('user')) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.')
                ->with('intended', $request->url());
        }

        return $next($request);
    }
}
