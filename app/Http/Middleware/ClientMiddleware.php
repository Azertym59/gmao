<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// Nous n'utilisons plus le façade Session, nous utilisons $request->session() à la place
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('ClientMiddleware: vérification de la session client');
        
        if (!$request->session()->has('client_id')) {
            Log::info('ClientMiddleware: aucun client connecté, redirection vers la page de login');
            return redirect()->route('client.login');
        }
        
        Log::info('ClientMiddleware: client connecté, ID: ' . $request->session()->get('client_id'));
        return $next($request);
    }
}