<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('active_location_id')) {
            $locationId = auth()->user()?->locations()->value('locations.id');
            if ($locationId) {
                session(['active_location_id' => $locationId]);
            }
        }
        return $next($request);
    }
}
