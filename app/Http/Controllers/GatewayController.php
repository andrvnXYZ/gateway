<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class GatewayController extends Controller
{
    // I-double check kon mao ba gyud ni ang saktong URLs sa imong Render Services
    public $userService = "https://site1-lw7p.onrender.com";
    public $rentalService = "https://site2-xv5f.onrender.com";

    public function handleRequest(Request $request)
    {
        $path = $request->path(); 
        $method = $request->method();

        /**
         * 1. SERVICE ROUTING LOGIC
         * Gi-apil nato ang 'login' ug 'register' sa condition para dili na mag-404.
         * Gi-apil sab nato ang '/api/' prefix tungod sa imong Site1/Site2 setup.
         */
        if (str_contains($path, 'users') || str_contains($path, 'register') || str_contains($path, 'login')) {
            
            // Padung sa SITE1 (User Service)
            $url = rtrim($this->userService, '/') . '/api/' . ltrim($path, '/');
            
        } elseif (str_contains($path, 'rentals')) {
            
            // Padung sa SITE2 (Rental Service)
            $url = rtrim($this->rentalService, '/') . '/api/' . ltrim($path, '/');
            
        } else {
            // Error kon ang path wala sa atong listahan
            return response()->json([
                'error' => 'Gateway: Path not found',
                'debug_info' => [
                    'requested_path' => $path,
                    'method' => $method
                ]
            ], 404);
        }

        // Makuha ang User ID kon authenticated na (para sa restricted routes)
        $userId = auth()->user() ? auth()->user()->id : null;

        try {
            /**
             * 2. REQUEST FORWARDING (PROXING)
             * I-forward ang tanang Headers, JSON Body, ug Query Parameters.
             */
            $response = Http::withHeaders([
                'Authorization'    => $request->header('Authorization'), // I-pasa ang JWT Token
                'Gateway-User-ID'  => $userId,                         // I-pasa ang ID sa nag-login
                'Accept'           => 'application/json',
            ])->send($method, $url, [
                'json' => $request->all(),      // I-pasa ang data (username, password, etc.)
                'query' => $request->query()    // I-pasa ang URL params (?id=1)
            ]);
            
            // I-return ang response gikan sa Microservice balik sa Client (Postman)
            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            // Error kon ang Site1 o Site2 "Down" o dili ma-contact
            return response()->json([
                'error' => 'Gateway: Service Unavailable',
                'message' => $e->getMessage(),
                'target_url' => $url
            ], 503);
        }
    }
}