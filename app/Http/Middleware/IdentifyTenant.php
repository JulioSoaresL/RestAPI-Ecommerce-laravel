<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID');

        if (!$tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'tenant não encontrado.'
            ], 400);
        }

        $tenant = Tenant::query()->where('status', 1)->find($tenantId);

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant não encontrado.'
            ], 444);
        }

        if ($request->user() && $request->user() instanceof User) {
            if (!$request->user()->canAccessTenant($tenant->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para gerenciar esta loja.'
                ], 403);
            }
        }

        app()->instance('currentTenant', $tenant);

        return $next($request);
    }
}
