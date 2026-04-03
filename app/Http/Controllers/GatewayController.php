<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class GatewayController extends Controller
{
    public $userService = "https://site1-lw7p.onrender.com";
    public $rentalService = "https://site2-xv5f.onrender.com";

    public function handleRequest(Request $request)
{
    $path = $request->path(); 
    $method = $request->method();

    // 1. I-update ang logic sa pagpili og Service
    if (str_contains($path, 'users') || str_contains($path, 'register')) {
        // I-add ang /api/ prefix para mo-match sa Site1 routes
        $url = rtrim($this->userService, '/') . '/api/' . ltrim($path, '/');
    } elseif (str_contains($path, 'rentals')) {
        // I-add sab ang /api/ prefix para sa Site2 kon naggamit sab ka og prefix didto
        $url = rtrim($this->rentalService, '/') . '/api/' . ltrim($path, '/');
    } else {
        return response()->json(['error' => 'Gateway: Path not found'], 404);
    }

    $userId = auth()->user() ? auth()->user()->id : null;

    try {
        // 2. I-forward ang request
        $response = Http::withHeaders([
            'Authorization'    => $request->header('Authorization'),
            'Gateway-User-ID'  => $userId,
            'Accept'           => 'application/json',
        ])->send($method, $url, [
            // Gamit ang 'json' para sa POST data, 'query' para sa URL params
            'json' => $request->all(),
            'query' => $request->query()
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