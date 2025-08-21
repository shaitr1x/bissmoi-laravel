<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MerchantMiddleware
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
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Autoriser tous les marchands (approuvés ou non)
        if (auth()->user()->role !== 'merchant') {
            abort(403, 'Accès non autorisé. Compte marchand requis.');
        }

        return $next($request);
    }
}
