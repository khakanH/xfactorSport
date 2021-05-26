<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserRole;
use App\Models\Modules;

use Auth;
class CheckRole
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
        
        $module_id = Modules::where('route',\Request::route()->getName())->first();
        
        if ($module_id == "") 
        {
            return $next($request);
        }
        else
        {
            $check = UserRole::where('user_type',Auth::user()->user_type)->where('module_id',$module_id->id)->first();
            if ($check == "") 
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
            else
            {
                return $next($request);
            }
        }
    }
}
