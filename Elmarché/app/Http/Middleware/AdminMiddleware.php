<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            Log::warning('Unauthenticated admin access attempt', [
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        // Check user role with case-insensitive comparison
        $userRole = strtolower(auth()->user()->role ?? '');
        if ($userRole !== 'vendeur') {
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => auth()->id(),
                'role' => $userRole,
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);
            
            abort(403, 'Accès administrateur requis.');
        }

        return $next($request);
    }
}
