<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Response;
class AuthenticateAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // explode then check for the token if  token exists proceed or else abort
        // $validSecrets = explode(',', env('ACCEPTED_SECRETS'));
        $validSecrets = explode(',', env('JWT_SECRET'));
        if(in_array($request->header('Authorization'), $validSecrets))
        {
            print_r($request);
            dd($request);
            die;
            return $next($request);
        }
        abort(Response::HTTP_UNAUTHORIZED);
    }
}