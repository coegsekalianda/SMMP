<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PenjaminMutu
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
        if (auth()->user()->otoritas == 'Penjamin Mutu') {
            return $next($request);
        } elseif (auth()->user()->otoritas == 'Admin') {
            return redirect('/admin/dashboard');
        } elseif (auth()->user()->otoritas == 'Dosen') {
            return redirect('/dosen/dashboard');
        }
        return redirect('/');
    }
}
