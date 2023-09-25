<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
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

        $dataNow = new DateTime();

        // VALIDA O EMAIL
        if (!$user) {
            return response(['message' => 'Credencias Inválidas!','code' => '401'], 200);
        }

        $token = $user->createToken('newToken')->plainTextToken;

        User::where('email', $request->email)->update(['access_token' => $token]);

        if (strtotime($user->expira_prazo) > strtotime($dataNow->format('Y-m-d H:i:s'))) {
            $response = [
                'user' => $user->name,
                'token' => $token,
                'code' => 200,
                'expira_prazo' => $user->expira_prazo,
                'created_at' => $user->created_at
            ];
        } else {
            $response = [
                'user' => $user->name,
                'token' => "000",
                'code' => 200,
                'expira_prazo' => $user->expira_prazo,
                'created_at' => $user->created_at
            ];
        }


        return response()->json($response, 201);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(Request $request)
    {
        $user = User::where('access_token',$request->token)->first();

        if(!$user){
            return response()->json([
                'code' => '401',
            ]);
        }

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
