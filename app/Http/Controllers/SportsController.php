<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sports;

class SportsController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function AddSport(Request $request)
    {	
        try 
        {
          return view('sport.add');
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveSport(Request $request)
    {	
        try 
        {
            $input = $request->all();
          	
          	if (Sports::where('name',trim(strtolower($input['name'])))->count() > 0) 
          	{
          		return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
          	}
          	else
          	{	
          		$data = array(
          						'name'=>trim(strtolower($input['name'])),
          						'created_at' => date('Y-m-d H:i:s'),
          						'updated_at' => date('Y-m-d H:i:s'),
          					);

          		if(Sports::insert($data))
          		{
	          		return redirect()->route('sports.add-sport')->with('success',__('web.Data Added Successfully'));
          		}
          		else
          		{
	          		return redirect()->back()->withInput()->with('failed',__('web.Something went wrong. Try again later'));
          		}
          	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function EditSport(Request $request,$id)
    {	
        try 
        {
        	$sport = Sports::where('id',$id)->first();
        	if ($sport == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

          return view('sport.edit',compact('sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateSport(Request $request)
    {	
        try 
        {
            $input = $request->all();
          	
          	if (Sports::where('name',trim(strtolower($input['name'])))->where('id','!=',$input['id'])->count() > 0) 
          	{
          		return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
          	}
          	else
          	{
          		if(Sports::where('id',$input['id'])->update(array('name'=>trim(strtolower($input['name'])))))
          		{
	          		return redirect()->route('sports.view-sport')->with('success',__('web.Data Updated Successfully'));
          		}
          		else
          		{
	          		return redirect()->back()->withInput()->with('failed',__('web.Something went wrong. Try again later'));
          		}
          	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function DeleteSport(Request $request,$id)
    {	
        try 
        {
        	if(Sports::where('id',$id)->delete())
        	{
	          	return redirect()->route('sports.view-sport')->with('success',__('web.Data Deleted Successfully'));
        	}
        	else
        	{
				return redirect()->back()->with('failed',__('web.Something went wrong. Try again later'));
        	}
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



    public function ViewSport(Request $request)
    {	
        try 
        {
        	$sports = Sports::get();
       		return view('sport.view',compact('sports'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

}
