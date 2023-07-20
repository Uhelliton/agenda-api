<?php

namespace App\Domains\Auth\Http\Controllers;
use Illuminate\Http\Request;
use App\Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\OpenApi(
 *  @OA\Info(
 *      title="Returns Services API",
 *      version="1.0.0",
 *      description="API documentation for Returns Service App",
 *      @OA\Contact(
 *          email="sushil@stepfront.com"
 *      )
 *  ),
 *  @OA\Server(
 *      description="Returns App API",
 *      url="https://localhost/api/"
 *  ),
 *  @OA\PathItem(
 *      path="/"
 *  )
 * )
 */
class AuthController extends Controller
{


    public function authenticate(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('JWT');

            return response()->json([
                'user' => $user,
                'tokenType' => 'bearer',
                'token'      => $token->plainTextToken
            ]);

        }

        return response()->json([
            'message'   => 'Não podemos encontrar uma conta com essas credenciais.'
        ], 401);
    }
}
