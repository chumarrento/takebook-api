<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfIsAdmin
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if (!$request->header('authorization')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (!$this->auth::user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
