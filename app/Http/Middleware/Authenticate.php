<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request As Request;

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
        $result = $this->callIdentityCurlService($request);
        if($result->status() == 401){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }

    public function callIdentityCurlService(Request $request){
        $identityUrl = env('IDENTITY_URL');
        $authorization = $request->header('authorization');

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $identityUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: '.$authorization;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        if($httpcode === 401){
            return response()->json(['error' => 'Unauthorized'], 401);
        }else{
            return response()->json(['message' => 'Authorized'], 200);
        }
    } 
}
