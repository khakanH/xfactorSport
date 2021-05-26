<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class OnlySuperAdmin
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
        if(Auth::user()->user_type == 0)
        {
            return $next($request);
        }
        else
        {
             if($request->ajax()) 
              {
                  return response()->json(['status'=>"0",'msg' => 'Restricted Page'],403);
                  
              }
              else
              {
                 return redirect()->back()->with('failed',__("web.Sorry, You're not allowed to visit requested page."));

              }
        }
    }
}
