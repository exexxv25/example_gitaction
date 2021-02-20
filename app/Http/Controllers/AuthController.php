<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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
     *       @OA\Property(property="email", type="string", format="email", example="admin@neighbors.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6NjA5MFwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MTA5ODc0MDIsImV4cCI6MTYxMDk5MTAwMiwibmJmIjoxNjEwOTg3NDAyLCJqdGkiOiJlUXlTTzN2ZnVmY0UzS0F6Iiwic3ViIjoxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.HE5tmEaJ57_Z-PAgq9kTTQIvT_w7ycd-Hrtmez9YI2g"),
     *       @OA\Property(property="token_type", type="string", example="bearer"),
     *       @OA\Property(property="ok", type="string", example="true"),
     *       @OA\Property(property="expires_in", type="int", example=3600),
     *        @OA\Property(
     *           property="(obj)usuario",
     *           type="object",
     *          @OA\Property(property="habilitado", type="string", format="boolean", example="1"),
     *          @OA\Property(property="nombre", type="string", format="text", example="adminNombre"),
     *          @OA\Property(property="apellido", type="string", format="text", example="adminApellido"),
     *          @OA\Property(property="dni", type="int", format="number", example="87654321"),
     *          @OA\Property(property="telefono", type="int", format="number", example="12345678"),
     *          @OA\Property(property="email", type="string", format="text", example="admin@neighbors.com"),
     *          @OA\Property(property="img", type="string", format="text", example="mi_foto.jpg"),
     *          @OA\Property(property="uid", type="string", format="text", example="HQ21SD1R1F8S"),
     *          @OA\Property(property="updated_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="created_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="id", type="int", format="number", example=2),
     *        @OA\Property(
     *           property="role",
     *           type="object",
     *          @OA\Property(property="0", type="string", format="boolean", example="ADMIN_ROL")
     *          ),
     *        @OA\Property(
     *           property="(array)permisos",
     *           type="object",
     *              @OA\Property(
     *                  property="(array)VECINO_ROL",
     *                  type="object",
     *                  @OA\Property(
     *                      property="dashboard",
     *                      type="object",
     *                      @OA\Property(property="0", type="string", format="text", example="read")
     *          ),
     *          ),
     *          ),
     *        @OA\Property(
     *           property="(array)barrios",
     *           type="object",
     *          @OA\Property(property="id_barrio", type="int", format="number", example="1"),
     *          @OA\Property(property="nombre_barrio", type="string", format="text", example="Jake O'Keefe")
     *          ),
     *        @OA\Property(
     *           property="(array)viviendas",
     *           type="object",
     *          @OA\Property(property="id_lote", type="string", format="text", example="1"),
     *          @OA\Property(property="nombre_lote", type="string", format="text", example="miloteEjemplo1"),
     *          @OA\Property(property="nombre_barrio", type="string", format="text", example="Jake O'Keefe")
     *          ),
     *          ),
     *        ),
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
     *          @OA\Property(property="name", type="string", format="text", example="Administrador"),
     *          @OA\Property(property="password", type="int", format="number", example="12345678"),
     *          @OA\Property(property="password_confirmation", type="int", format="number", example="12345678"),
     *          @OA\Property(property="allow", type="string", format="boolean", example="1"),
     *          @OA\Property(property="lastname", type="string", format="text", example="admin2Apellido"),
     *          @OA\Property(property="passport", type="int", format="number", example="87654321"),
     *          @OA\Property(property="phone", type="int", format="number", example="12345678"),
     *          @OA\Property(property="email", type="string", format="text", example="admin2@neighbors.com"),
     *          @OA\Property(property="avatar", type="string", format="text", example="mi_foto2.jpg"),
     *          @OA\Property(property="user_relative", type="integer", format="number", example="1")
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
     *          @OA\Property(property="habilitado", type="string", format="boolean", example="1"),
     *          @OA\Property(property="nombre", type="string", format="text", example="adminNombre"),
     *          @OA\Property(property="apellido", type="string", format="text", example="adminApellido"),
     *          @OA\Property(property="dni", type="int", format="number", example="87654321"),
     *          @OA\Property(property="telefono", type="int", format="number", example="12345678"),
     *          @OA\Property(property="email", type="string", format="text", example="admin@neighbors.com"),
     *          @OA\Property(property="img", type="string", format="text", example="mi_foto.jpg"),
     *          @OA\Property(property="uid", type="string", format="text", example="HQ21SD1R1F8S"),
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
     *       @OA\Property(property="expires_in", type="int", example=3600),
     *        @OA\Property(
     *           property="(obj)usuario",
     *           type="object",
     *          @OA\Property(property="habilitado", type="string", format="boolean", example="1"),
     *          @OA\Property(property="nombre", type="string", format="text", example="adminNombre"),
     *          @OA\Property(property="apellido", type="string", format="text", example="adminApellido"),
     *          @OA\Property(property="dni", type="int", format="number", example="87654321"),
     *          @OA\Property(property="telefono", type="int", format="number", example="12345678"),
     *          @OA\Property(property="email", type="string", format="text", example="admin@neighbors.com"),
     *          @OA\Property(property="img", type="string", format="text", example="mi_foto.jpg"),
     *          @OA\Property(property="uid", type="string", format="text", example="HQ21SD1R1F8S"),
     *          @OA\Property(property="updated_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="created_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="id", type="int", format="number", example=2),
     *        @OA\Property(
     *           property="role",
     *           type="object",
     *          @OA\Property(property="0", type="string", format="boolean", example="ADMIN_ROL")
     *          ),
     *        @OA\Property(
     *           property="(array)permisos",
     *           type="object",
     *              @OA\Property(
     *                  property="(array)VECINO_ROL",
     *                  type="object",
     *                  @OA\Property(
     *                      property="dashboard",
     *                      type="object",
     *                      @OA\Property(property="0", type="string", format="text", example="read")
     *          ),
     *          ),
     *          ),
     *        @OA\Property(
     *           property="(array)barrios",
     *           type="object",
     *          @OA\Property(property="id_barrio", type="int", format="number", example="1"),
     *          @OA\Property(property="nombre_barrio", type="string", format="text", example="Jake O'Keefe")
     *          ),
     *        @OA\Property(
     *           property="(array)viviendas",
     *           type="object",
     *          @OA\Property(property="id_lote", type="string", format="text", example="1"),
     *          @OA\Property(property="nombre_lote", type="string", format="text", example="miloteEjemplo1"),
     *          @OA\Property(property="nombre_barrio", type="string", format="text", example="Jake O'Keefe")
     *          ),
     *          ),
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
            'ok' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'usuario' => User::dataEs(auth()->user())
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/v1/user/find/passport",
     * summary="Buscar DNI",
     * description="BuscarDni ( Primero autorizar los headers con el token JWT provisto en el login)",
     * operationId="findPassport",
     * tags={"Buscar"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Buscar un dni",
     *    @OA\JsonContent(
     *       required={"dni"},
     *       @OA\Property(property="dni", type="int", format="number", example="12345678"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(property="0", type="string", example="true"),
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

    public function existPassport(Request $request){

        $validator = Validator::make($request->all(), [
            'dni' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $exists = User::wherePassport($request->dni)->exists();

        return response()->json(["message" => $exists], 200);
    }
}
