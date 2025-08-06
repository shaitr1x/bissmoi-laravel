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

        if (!auth()->user()->isApprovedMerchant()) {
            abort(403, 'Accès non autorisé. Compte commerçant requis et approuvé.');
        }

        return $next($request);
    }
}
