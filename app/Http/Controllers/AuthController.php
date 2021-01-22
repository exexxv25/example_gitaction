<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

/**
 * @OA\Info(title="Neighbors", version="release 1.0")
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Header autorization toker JWT",
 *     name="Token Bearer",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * ),
 */
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Post(
     * path="/auth/login",
     * summary="Login que responde un Token",
     * description="Login usando usuario y pass",
     * operationId="authLogin",
     * tags={"AUTH"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="admin@neighbors.com.ar"),
     *       @OA\Property(property="password", type="string", format="password", example="neighbors3212021"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6NjA5MFwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MTA5ODc0MDIsImV4cCI6MTYxMDk5MTAwMiwibmJmIjoxNjEwOTg3NDAyLCJqdGkiOiJlUXlTTzN2ZnVmY0UzS0F6Iiwic3ViIjoxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.HE5tmEaJ57_Z-PAgq9kTTQIvT_w7ycd-Hrtmez9YI2g"),
     *       @OA\Property(property="token_type", type="string", example="bearer"),
     *       @OA\Property(property="expires_in", type="int", example=3600)
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="email", type="string", example="The email field is required."),
     *       @OA\Property(property="password", type="string", example="The password field is required.")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Post(
     * path="/auth/register",
     * summary="Registrarse",
     * description="Registro de usuarios",
     * operationId="authRegister",
     * tags={"AUTH"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del usuario",
     *    @OA\JsonContent(
     *       required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", format="text", example="Administrador"),
     *       @OA\Property(property="email", type="string", format="email", example="admin@neighbors.com"),
     *       @OA\Property(property="password", type="string", format="password", example="neighbors3212021"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="neighbors3212021"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Created",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="User successfully registered"),
     *        @OA\Property(
     *           property="user",
     *           type="object",
     *          @OA\Property(property="name", type="string", format="text", example="Administrador"),
     *          @OA\Property(property="email", type="string", format="email", example="admin@neighbors.com"),
     *          @OA\Property(property="updated_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="created_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="id", type="int", format="number", example=2),
     *        )
     *     )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="email", type="string", example="The email has already been taken.")
     *        )
     *     ),
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Post(
     * path="/auth/logout",
     * summary="Logout",
     * description="Logout ( Primero autorizar los headers con el token JWT provisto en el login)",
     * operationId="authLogout",
     * tags={"AUTH"},
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User successfully signed out"),
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Error: Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthenticated")
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Post(
     * path="/auth/refresh",
     * summary="Refrescar Token",
     * description="Refrescar Token ( Primero autorizar los headers con el token JWT provisto en el login)",
     * operationId="authRefresh",
     * tags={"AUTH"},
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6NjA5MFwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MTA5ODc0MDIsImV4cCI6MTYxMDk5MTAwMiwibmJmIjoxNjEwOTg3NDAyLCJqdGkiOiJlUXlTTzN2ZnVmY0UzS0F6Iiwic3ViIjoxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.HE5tmEaJ57_Z-PAgq9kTTQIvT_w7ycd-Hrtmez9YI2g"),
     *       @OA\Property(property="token_type", type="string", example="bearer"),
     *       @OA\Property(property="expires_in", type="int", example=3600)
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="The access token provided is expired,",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            // 'user' => auth()->user()
        ]);
    }
}
