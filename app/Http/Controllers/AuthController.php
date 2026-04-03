<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $this->validate($request, [
        'username' => 'required', // Gikan sa 'email' ngadto sa 'username'
        'password' => 'required'
    ]);

    $credentials = $request->only(['username', 'password']);

    if (! $token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Invalid username or password'], 401);
    }

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer'
    ]);
}
}