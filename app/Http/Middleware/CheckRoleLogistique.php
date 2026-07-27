<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleLogistique
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //1.On verifie si l'utilisateur est connecte
        //2.On verifie si son role est logistique ou admin
        if (!$request->user() || !in_array($request->user()->role, ['logistique', 'admin'])) {
            return response()->json([
                'message' => 'Accès refusé. Vous n\'avez pas les droits nécessaires.'
            ], 403); // 403 signifie "Interdit" (Forbidden)
        }
        return $next($request);
    }
}
