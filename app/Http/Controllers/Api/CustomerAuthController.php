<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // A Trait BelongsToTenant vai filtrar o cliente dentro do tenant_id informado no header
        $customer = Customer::query()->where('email', $request->email)->first();

        if (! $customer || ! Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas para este e-commerce estão incorretas.'],
            ]);
        }

        // Gera o token Sanctum exclusivo para o Customer
        $token = $customer->createToken('customer_auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Login de cliente realizado com sucesso.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'customer'     => [
                'id'    => $customer->id,
                'name'  => $customer->name,
                'email' => $customer->email,
            ]
        ]);
    }

    /**
     * Retorna os dados do cliente logado
     */
    public function me(Request $request)
    {
        return response()->json([
            'success'  => true,
            'customer' => new CustomerResource($request->user()),
        ]);
    }
}
