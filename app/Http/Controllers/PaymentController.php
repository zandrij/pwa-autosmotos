<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client; 
use GuzzleHttp\Exception\RequestException; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function verify(Request $request)
    {
        $certPath = storage_path('certified/merchant_id_cert.pem');
        $keyPath = storage_path('certified/merchant_identity_private_key.pem');

        try {
            $response = Http::withOptions([
                'verify' => true,
                'cert' => $certPath,
                'key' => $keyPath,
            ])->post('https://apple-pay-gateway-cert.apple.com/paymentservices/startSession', [
                'merchantIdentifier' => 'merchant.es.autosmotos.app',
                'displayName' => 'AutosMotos',
                'initiative' => 'web',
                'initiativeContext' => 'pwa.dattatech.com',
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
