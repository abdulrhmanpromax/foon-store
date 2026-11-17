<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResellerApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'مفتاح الـ API مفقود. يرجى تمرير X-API-KEY في ترويسات الطلب.',
            ], 401);
        }

        $reseller = User::query()
            ->where('api_key', $apiKey)
            ->whereIn('role', ['reseller', 'admin'])
            ->first();

        if (! $reseller) {
            return response()->json([
                'success' => false,
                'message' => 'مفتاح الـ API غير صالح أو غير مصرح له كـ موزع.',
            ], 401);
        }

        auth()->setUser($reseller);
        $request->setUserResolver(fn () => $reseller);

        return $next($request);
    }
}
