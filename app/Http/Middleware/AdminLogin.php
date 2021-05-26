<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

use App\Models\User;

class AdminLogin
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
        if(!$request->ajax()) 
        {
            $request->session()->put("last_url",$request->url());
        }
        
        if (empty(Auth::user()->id)) 
        {   
            if($request->ajax()) 
            {
                return response()->json(['status'=>"0",'msg' => 'Session expired'],401);
                
            }
            else
            {
                $request->session()->put('failed', "Kindly Login First!");
                return redirect()->route('index');
            }
        }
        else
        {       
            $user = User::where('id',Auth::user()->id)->first();

            if ($user == "") 
            {   

                if($request->ajax()) 
                {
                    return response()->json(['status'=>"0",'msg' => 'Session expired'],401);
                }
                else
                {
                    $request->session()->put("failed","Something went wrong.");
                    header('Location:'.url('logout'));
                }
            
            }
            else
            {   
                return $next($request);
            }

        }
    }
}
