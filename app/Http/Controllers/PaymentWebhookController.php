<?php

namespace App\Http\Controllers;

use App\Services\Payments\BinancePayService;
use App\Services\Payments\LemonSqueezyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    /**
     * Handle Lemon Squeezy incoming webhook.
     */
    public function handleLemonSqueezy(Request $request, LemonSqueezyService $service): JsonResponse
    {
        $rawBody = (string) $request->getContent();
        $signature = (string) $request->header('X-Signature', '');

        $processed = $service->verifyAndProcessWebhook($rawBody, $signature);

        if (! $processed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid webhook signature or payload',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Handle Binance Pay incoming webhook.
     */
    public function handleBinance(Request $request, BinancePayService $service): JsonResponse
    {
        $rawBody = (string) $request->getContent();
        $headers = $request->headers->all();

        $processed = $service->verifyAndProcessWebhook($headers, $rawBody);

        if (! $processed) {
            return response()->json([
                'returnCode' => 'FAIL',
                'returnMessage' => 'Invalid signature or payload',
            ], 400);
        }

        return response()->json([
            'returnCode' => 'SUCCESS',
            'returnMessage' => null,
        ]);
    }
}
