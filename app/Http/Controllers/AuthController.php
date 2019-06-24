<?php

namespace App\Http\Controllers;

use App\Entities\AccessLog;
use App\Entities\Auth\User;
use App\Mail\ResetPasswordMail;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'loginPortal',
            'loginAdmin',
            'forgot',
            'checkToken',
            'reset'
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Autenticação de usuário",
     *     operationId="AuthLogin",
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail para autenticação",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Senha para autenticação",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     *
     * )
     */

    public function loginPortal(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::where([
            ['email',$request->input('email')],
            ['is_admin',0]
        ])->first();

        if(! $user ){
            return response()->json(['error' => 'User not found'], 401);
        }

        if(! Hash::check($request->input('password'),$user->password) ){
            return response()->json(['error' => 'Incorrect Password'], 401);
        }

        if (! $token = JWTAuth::attempt($credentials) ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        AccessLog::create(['user_id' => Auth::user()->id,"ip" => $request->ip(), "type" => "login"]);
        return response()->json([
            'status' => 'success',
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/admin/auth/login",
     *     summary="Autenticação da administração",
     *     operationId="AuthAdminLogin",
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail para autenticação",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Senha para autenticação",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     *
     * )
     */
    public function loginAdmin(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::where([
            ['email',$request->input('email')],
            ['is_admin',1]
        ])->first();

        if(! $user ){
            return response()->json(['error' => 'User not found'], 401);
        }

        if(! Hash::check($request->input('password'),$user->password) ){
            return response()->json(['error' => 'Incorrect Password'], 401);
        }

        if (! $token = JWTAuth::attempt($credentials) ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        AccessLog::create(['user_id' => Auth::user()->id,"ip" => $request->ip(), "type" => "login"]);
        return response()->json([
            'status' => 'success',
            'token' => $token
        ]);
    }
    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     summary="Atualiza o token atual do usuário",
     *     operationId="AuthRefresh",
     *     tags={"auth"},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     *
     * )
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();
        $new = JWTAuth::refresh($token);
        
        AccessLog::create(['user_id' => Auth::user()->id,"ip" => $request->ip(), "type" => "refresh"]);
        return response()->json([
            'token' => $new
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/forgot",
     *     summary="Autenticação da administração",
     *     operationId="ForgotPassword",
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function forgot(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->input('email'))->firstOrFail();
        $token = $user->generateResetToken();

        Mail::to($user)->send(new ResetPasswordMail(['user' => $user, 'token' => $token]));

        return $this->success();
    }

    /**
     * @OA\Post(
     *     path="/auth/checkToken",
     *     summary="Verificação do token",
     *     operationId="CheckToken",
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token enviado no email do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function checkToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required'
        ]);
        $token = DB::table('password_resets')->where([
            ['email', '=', $request->input('email')],
            ['token', '=', $request->input('token')],
            ['used', '=', 0]
        ])->first();

        if($token){
            return $this->success();
        }
        return $this->unprocessable();
    }

    /**
     * @OA\Post(
     *     path="/auth/reset",
     *     summary="Reset na senha do usuário",
     *     operationId="ResetPassword",
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token enviado no email do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Nova senha do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Confirmação da nova senha",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function reset(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed',
            'token' => 'required'
        ]);

        $checkToken = DB::table('password_resets')->where([
                ['email', $request->input('email')],
                ['token', $request->input('token')],
                ['used' , '=', 0]
            ])->first();

        if(!$checkToken){
            return $this->unprocessable(['This token doest exists or is already used.']);
        }

        User::where('email', $request->input('email'))->update(
            ['password' => Hash::make($request->input('password'))]
        );

        DB::table('password_resets')->where('token', $request->input('token'))->update(['used' => 1]);
        return $this->success();
    }
}
