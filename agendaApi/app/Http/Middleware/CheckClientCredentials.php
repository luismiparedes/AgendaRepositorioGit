<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Contact;
use Closure;
use Illuminate\Http\Request;

class CheckClientCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $apiToken = $request->request->get('api_token');
        $apiToken = $request->bearerToken();
        
        $user = User::where('api_token',$apiToken)->first();

        if($user){
           
            //return response("Token permitido");
            return $next($request);
        }else{
            return response("Token no permitido",401);
        }

        
    }
}
