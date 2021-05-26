<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;

class ModuleController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function Index(Request $request)
    {	
        try 
        {
           $modules = Modules::get();

           return view('modules.module_list',compact('modules'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }

    public function AddModule(Request $request)
    {
    	try 
        {
           return view('modules.add_module');
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


}
