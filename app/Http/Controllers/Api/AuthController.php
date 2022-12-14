<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
        ]);


        $user = User::where('email', $request->email)->first();

        // VALIDA O EMAIL
        if (!$user) {
            return response(['message' => 'Credencias Inválidas!'], 401);
        }

        $token = $user->createToken('newToken')->plainTextToken;

        User::where('email', $request->email)->update(['access_token' => $token]);

        $response = [
            'user' => $user->name,
            'token' => $token,
            'code' => 200,
        ];

        return response()->json($response, 201);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'bearer',
        //     'expires_in' => auth('api')->factory()->getTTL() * 1,
        //     'code' => '200',
        // ]);
    }


    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('firstToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response, 201);
    }
}
