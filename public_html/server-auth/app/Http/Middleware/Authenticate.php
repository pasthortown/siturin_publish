<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Exception;
use App\User;
use App\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

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
        $token = $request->header('api_token');
        if(!$token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $timeRemaining = 10;//$credentials->expiration_time - time();
            if ($timeRemaining <= 0) {
                return response()->json([
                    'error' => 'Provided token is expired.'
                ], 400);
            }
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }
        $user = User::find($credentials->subject);
        $request->auth = $user;
        $this->audit($request, $credentials->subject);
        return $next($request);
    }

    protected function audit($request, $user_id) {
        $args = $request->json()->all();
        $ip=$_SERVER['REMOTE_ADDR'];
        $httpMethod=$_SERVER['REQUEST_METHOD'];
        if($httpMethod=='GET'){
            return;
        }
        $uri=$_SERVER['REQUEST_URI'];
        $client_info = [
                    "IP"=>$ip,
                    "url"=>$uri,
                    "method"=>$httpMethod,
                ];
        $toLog = ["request"=>$args, "client_info"=>$client_info];
        try{
            $log = new Log();
            $lastLog = Log::orderBy('id')->get()->last();
            if($lastLog) {
               $log->id = $lastLog->id + 1;
            } else {
               $log->id = 1;
            }
            $log->date_time = date("Y-m-d H:i:s"); 
            $log->request = json_encode($toLog);
            $log->user_id = $user_id;
            $log->save();
         } catch (Exception $e) {
            // NOTHING
         }
    }
}
