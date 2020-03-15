<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            $log = new Log();
            $log->description = 'Пользователь незарегистрирован. Доступ был запрещен.';
            $log->type = 'log';
            $log->level = 'error';
            $log->token = getallheaders()['Authorization'];
            $log->save();

            return response()->json(['error' => 'Forbidden'], 403, ['Content-Type' => 'application/json; charset=UTF-8']);
        }
        return $next($request);
    }
}
