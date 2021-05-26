<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Leave;

use File;
class LeaveController extends Controller
{
    public function __construct(Request $request)
    {   

    }


    public function ViewLeave(Request $request)
    {	
        try 
        {
         	$leave = Leave::get();
          $from_date = "";
          $to_date = "";
         	return  view('leave.view',compact('leave','from_date','to_date'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SubmitLeave(Request $request)
    {	
        try 
        {	
        	$leave_type = LeaveType::get();
          $employee = Employee::get();
          
          foreach ($employee as $key) 
          {
            $key['remaining_employees'] = Employee::where('job_type_id',$key['job_type_id'])->count()-1;
          }  

         	return  view('leave.add',compact('leave_type','employee'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function EmployeeNamesList(Request $request,$name)
    {	
        try 
        {	
        	$names = Employee::where('name','like','%'.$name.'%')->orderBy('name','asc')->get();

          if (count($names) == 0) 
          {
            return false;
          }

        	foreach ($names as $key) 
        	{  
            $key['remaining_employees'] = Employee::where('job_type_id',$key['job_type_id'])->count()-1;
        		?>
        			<button type="button" class="btn btn-secondary" style="width: 80%; margin: 4px;" onclick='SelectEmployee("<?php echo $key['id'] ?>","<?php echo $key['name'] ?>","<?php echo $key['job_title'] ?>","<?php echo $key['remaining_employees'] ?>")'><?php echo $key['name'] ?></button>
        		<?php
        	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



    public function SaveLeave(Request $request)
    {	
        try 
        {	
        	
        	 $validator = \Validator::make($request->all(), 
                                        [
                                          'name' => 'required',
                                          'type' => 'required',
                                          'title'    => 'required',
                                          'leave_date'    => 'required',
                                          'return_date'    => 'required',
                                          // 'reason'    => 'required',
                                          'condition'    => 'required',
                                          'no_of_colleagues'    => 'required',
                                          'document.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,docx|max:5120',
                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_employee = Employee::where('id',$input['employee_id'])->first();

            if ($check_employee == "") 
            {
                return redirect()->back()->withInput()->with('failed',__('web.Invalid Data'));
            }



            $image= $request->file('document');
            if (empty($image)) 
            {
                $path = "";
                
            }
            else
            {
                foreach ($image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/employees/leaves');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'employees/leaves/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Document Uploading");
                    }
                }
                   
            }




            	$data = array(
          					'employee_id'	=> $input['employee_id'],
          					'leave_type_id'	=> $input['type'],
          					'title'			=> $input['title'],
          					'leave_date'	=> $input['leave_date'],
          					'return_date'	=> $input['return_date'],
          					'reason'		=> $input['reason'],
          					'details'		=> $input['details'],
                    'is_paid'   => $input['condition'],
          					'no_of_colleagues'=> $input['no_of_colleagues'],
          					'paid_amount'	=> $input['amount'],
                    'documents'		=>empty($path)?"":implode(",", $path),
          					'created_at' 	=> date('Y-m-d H:i:s'),
          					'updated_at' 	=> date('Y-m-d H:i:s'),
          		);

          		if(Leave::insert($data))
          		{
	          		return redirect()->route('leave.submit-leave')->with('success',__('web.Data Added Successfully'));
          		}
          		else
          		{
	          		return redirect()->back()->withInput()->with('failed',__('web.Something went wrong. Try again later'));
          		}



        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function EditLeave(Request $request,$id)
    {	
        try 
        {	
        	$leave = Leave::where('id',$id)->first();

        	if ($leave == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

        	$leave_type = LeaveType::get();
           $employee = Employee::get();
          
          foreach ($employee as $key) 
          {
            $key['remaining_employees'] = Employee::where('job_type_id',$key['job_type_id'])->count()-1;
          }  
          	return view('leave.edit',compact('leave','leave_type','employee'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateLeave(Request $request)
    {	
        try 
        {	
        	
        	 $validator = \Validator::make($request->all(), 
                                        [
                                          'name' => 'required',
                                          'type' => 'required',
                                          'title'    => 'required',
                                          'leave_date'    => 'required',
                                          'return_date'    => 'required',
                                          // 'reason'    => 'required',
                                          'no_of_colleagues'    => 'required',
                                          'condition'    => 'required',
                                          'document.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,docx|max:5120',
                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_employee = Employee::where('id',$input['employee_id'])->first();

            if ($check_employee == "") 
            {
                return redirect()->back()->withInput()->with('failed',__('web.Invalid Data'));
            }

            $get_leave = Leave::where('id',$input['id'])->first();


            $image= $request->file('document');
            if (empty($image)) 
            {
                $docs_img = $get_leave->documents;
                
            }
            else
            {
                foreach ($image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/employees/leaves');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'employees/leaves/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Document Uploading");
                    }
                }
                
                if (empty($get_leave->documents)) 
                {
	                $docs_img = implode(",", $path);
                }
                else
                {
	                $docs_img = $get_leave->documents.",".implode(",", $path);
                }
                   
            }




            	$data = array(
          					'employee_id'	=> $input['employee_id'],
          					'leave_type_id'	=> $input['type'],
          					'title'			=> $input['title'],
          					'leave_date'	=> $input['leave_date'],
          					'return_date'	=> $input['return_date'],
          					'reason'		=> $input['reason'],
          					'details'		=> $input['details'],
                    'no_of_colleagues'=> $input['no_of_colleagues'],
          					'is_paid'		=> $input['condition'],
          					'paid_amount'	=> ($input['condition'] == 1)?$input['amount']:0,
                      		'documents'		=> $docs_img,
          		);

          		if(Leave::where('id',$input['id'])->update($data))
          		{
	          		return redirect()->route('leave.view-leave')->with('success',__('web.Data Updated Successfully'));
          		}
          		else
          		{
	          		return redirect()->back()->withInput()->with('failed',__('web.Something went wrong. Try again later'));
          		}



        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function DeleteLeave(Request $request,$id)
    {	
        try 
        {	

           //delete leave file docs first
            $get_leave = Leave::where('id',$id)->first();
            $files = explode(",", $get_leave->documents);
            foreach ($files as $key) 
            {
              $image_path = public_path('images/'.$key);  
              if(File::exists($image_path)) 
              {
                  File::delete($image_path);
              }
            }



        	if(Leave::where('id',$id)->delete())
        	{
	          	return redirect()->route('leave.view-leave')->with('success',__('web.Data Deleted Successfully'));
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



    public function DetailsLeave(Request $request,$id)
    { 
        try 
        {
          $get_leave = Leave::where('id',$id)->first();

          if ($get_leave == "")
          {
            ?>
              <center><p class="text-danger"><?php echo __('web.Invalid Data') ?></p></center>
            <?php
            return;
          }

          ?> 

          <table class="table">
            <tbody>
              <tr>
                <td><b><?php echo __('web.Name')?>:</b> <?php echo $get_leave->employee_data->name; ?></td>
                <td><b><?php echo __('web.Email')?>:</b> <?php echo $get_leave->employee_data->email; ?></td>
                <td><b><?php echo __('web.Phone')?>:</b> <?php echo $get_leave->employee_data->phone; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Job_Type')?>:</b> <?php echo $get_leave->employee_data->job_type_name->name; ?></td>
                <td><b><?php echo __('web.Leave Type')?>:</b> <?php echo $get_leave->leave_type_name->name; ?></td>
                <td><b><?php echo __('web.Title')?>:</b> <?php echo $get_leave->title; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Leave Date')?>:</b> <?php echo $get_leave->leave_date; ?></td>
                <td><b><?php echo __('web.Return Date')?>:</b> <?php echo $get_leave->return_date; ?></td>
                <td><b><?php echo __('web.Reason')?>:</b> <?php echo $get_leave->reason; ?></td>
              </tr>

               <tr>
                <td><b><?php echo __('web.Condition')?>:</b> <?php echo ($get_leave->is_paid ==1)?__('web.Paid'):__('web.Non Paid'); ?></td>
                <td><b><?php echo __('web.Amount')?>:</b> <?php echo empty($get_leave->paid_amount)?"-":$get_leave->paid_amount; ?></td>
                <td><b><?php echo __('web.Documents')?>:</b>
                  <ul>
                    <?php 
                    $docs = explode(",", $get_leave->documents); 
                    foreach ($docs as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><?php echo $key; ?></a></li>
                    <?php endforeach; ?>
                  </ul></td>
              </tr>

              
              
              <tr>
                <td colspan="3"><b><?php echo __('web.Details')?>:</b> <?php echo $get_leave->details; ?></td>
                
              </tr>

            </tbody>
          </table>


           

          <?php




        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterLeave(Request $request)
    { 
        try 
        { 

          $input = $request->all();

          if (empty($input['from_date']) || empty($input['to_date'])) 
          {
            $leave = Leave::get();
            $from_date = "";
            $to_date = "";
          }
          else
          {
           
                $leave = Leave::whereDate('leave_date','>=',$input['from_date'])->whereDate('leave_date','<=',$input['to_date'])->get();
            
            $from_date = $input['from_date'];
            $to_date = $input['to_date'];
          }

          return  view('leave.view',compact('leave','from_date','to_date'));
        
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


}
