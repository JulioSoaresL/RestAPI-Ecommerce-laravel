<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $authRequest)
    {
        $credentials = $authRequest->validated();

        $user = \App\Models\User::query()->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($authRequest->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $user->tokens()->delete();

        $deviceName = $request->device_name ?? 'API Client';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Token revogado com sucesso.']);
    }
}
