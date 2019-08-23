<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request As Request;

use Illuminate\Http\Response;

use StackUtil\Utils\DbUtils;
use App\Utils\MetadataUtils;
use StackUtil\Utils\Utility;
use Exception;


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
        $result = json_decode($result);
        $response = $next($request);
        $userId = $result->id;
        Authenticate::history($request, $response,  $userId);
        return $response;
    }

    public function history($request, $response = null , $userId)
    {
        if ( env('API_DATALOGGER', true) ) {
            $endTime = microtime(true);
            $tableName = 'history';
            $dataToLog['user_id'] = $userId;
            $dataToLog['name'] = 'User_Name';
            $dataToLog['time'] =  gmdate("F j, Y, g:i a");
            $dataToLog['duration'] =  number_format($endTime - LUMEN_START, 3);
            $dataToLog['ipaddress'] =  $request->ip();
            $dataToLog['url'] =    $request->fullUrl();
            $dataToLog['method'] = $request->method();
            $dataToLog['input'] =  $request->getContent();
            $dataToLog['output'] = $response->getContent();
            $dataToLog['status_code'] = $response->getStatusCode();
            $metadata = MetadataUtils::CallMetaData($request, $tableName);
            $object = MetadataUtils::GetObject($metadata,$tableName);
            $dataToLog['id'] = Utility::generateId('s',$object['short_name']);
            $dataToLog['key'] = Utility::generateKey($object['short_name']);
            $result = DbUtils::generateInsert($tableName,$dataToLog);
        }
    }

    public function callIdentityCurlService(Request $request)
    {
        $identityUrl = env('IDENTITY_URL').'auth/v1/authCheck';
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
            throw new Exception('Unauthourized',401);
        }else{
            return $result;
        }
    }
}
