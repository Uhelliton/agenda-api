<?php

namespace App\Domains\Auth\Http\Controllers;
use App\Core\Http\Controllers\Controller;
use App\Domains\Auth\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
/**
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * ),
 * @OA\OpenApi(
 *  @OA\Info(
 *     title="API Agenda Swagger Documentation",
 *      version="1.0.0",
 *      description="API documentation for Returns Service App",
 *      @OA\Contact(
 *          email="uhelliton@uol.com.br"
 *      )
 *  ),
 *  @OA\Server(
 *      description="Returns App API",
 *      url="http://localhost:8098/"
 *  ),
 *  @OA\PathItem(
 *      path="/"
 *  )
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Post(
     *    tags={"Authentication"},
     *    description="This endpoints return a new token user authentication for use on protected endpoints",
     *    path="/api/auth/login",
     *  @OA\RequestBody(
     *    @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *       required={"email","password","device_name"},
     *       @OA\Property(property="email", type="string", example="admin@agenda.com.br"),
     *       @OA\Property(property="password", type="string", example="admin"),
     *     )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Token generated",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="object", example="{}"),
     *       @OA\Property(property="token", type="string", example="2|MZEBxLy1zulPtND6brlf8GOPy57Q4DwYunlibXGj"),
     *       @OA\Property(property="tokenType", type="string", example="bearer")
     *    )
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Incorrect credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Não podemos encontrar uma conta com essas credenciais"),
     *    )
     *  )
     * )
     * @extends
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function authenticate(LoginRequest $request): JsonResponse
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
