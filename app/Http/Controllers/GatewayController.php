<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class GatewayController extends Controller
{
    private $userService = "http://localhost:8001";
    private $rentalService = "http://localhost:8002";

    public function handleRequest(Request $request)
    {
        $path = $request->path(); 
        $method = $request->method();

        
        if (str_contains($path, 'users')) {
            $url = $this->userService . '/' . $path;
        } elseif (str_contains($path, 'rentals')) {
            $url = $this->rentalService . '/' . $path;
        } else {
            return response()->json(['error' => 'Gateway: Path not found'], 404);
        }

       
        $userId = auth()->user() ? auth()->user()->id : null;

        
        try {
            $response = Http::withHeaders([
                'Authorization'    => $request->header('Authorization'), // I-pasa ang JWT Token
                'Gateway-User-ID'  => $userId,                          // I-pasa ang ID sa nag-login
                'Accept'           => 'application/json',
            ])->send($method, $url, [
                'json' => $request->all(),
                'query' => $request->query() // I-apil sab nato ang URL parameters (?id=1)
            ]);
            
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gateway: Service Unavailable',
                'message' => $e->getMessage()
            ], 503);
        }
    }
}