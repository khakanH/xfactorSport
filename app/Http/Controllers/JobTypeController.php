<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobType;

class JobTypeController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function AddJob(Request $request)
    {	
        try 
        {
          return view('job_type.add');
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveJob(Request $request)
    {	
        try 
        {
            $input = $request->all();
          	
          	if (JobType::where('name',trim(strtolower($input['name'])))->count() > 0) 
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

          		if(JobType::insert($data))
          		{
	          		return redirect()->route('job_type.add-job')->with('success',__('web.Data Added Successfully'));
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

    public function EditJob(Request $request,$id)
    {	
        try 
        {
        	$job_type = JobType::where('id',$id)->first();
        	if ($job_type == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data.'));
        	}

          return view('job_type.edit',compact('job_type'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateJob(Request $request)
    {	
        try 
        {
            $input = $request->all();
          	
          	if (JobType::where('name',trim(strtolower($input['name'])))->where('id','!=',$input['id'])->count() > 0) 
          	{
          		return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
          	}
          	else
          	{
          		if(JobType::where('id',$input['id'])->update(array('name'=>trim(strtolower($input['name'])))))
          		{
	          		return redirect()->route('job_type.view-job')->with('success',__('web.Data Updated Successfully'));
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

    public function DeleteJob(Request $request,$id)
    {	
        try 
        {
        	if(JobType::where('id',$id)->delete())
        	{
	          	return redirect()->route('job_type.view-job')->with('success',__('web.Data Deleted Successfully'));
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



    public function ViewJob(Request $request)
    {	
        try 
        {
        	$job_type = JobType::get();
       		return view('job_type.view',compact('job_type'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

}
