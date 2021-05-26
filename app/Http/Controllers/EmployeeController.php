<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\JobType;
use App\Models\Sports;

use App\Models\Classes;
use App\Models\ClassesDays;

use File;
class EmployeeController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function AddEmployee(Request $request)
    {	
        try 
        {
          $job_type = JobType::get();
          $sport = Sports::get();
          return view('employee.add',compact('job_type','sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveEmployee(Request $request)
    {	
        try 
        { 
             $validator = \Validator::make($request->all(), 
                                        [
                                          'name' => 'required',
                                          'email' => 'required|email',
                                          'phone'    => 'required',
                                          'dob'    => 'required',
                                          'nationality'    => 'required',
                                          'national_id'    => 'required|numeric',
                                          'job_type'    => 'required',
                                          'job_title'    => 'required',
                                          'salary'    => 'required|numeric',
                                          // 'commission'    => 'required|numeric',
                                          'joining_date'    => 'required',
                                          'working_days'    => 'required',
                                          'annual_leaves'    => 'required',
                                          'expiry_date'    => 'required',
                                          'certificate_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,docx|max:5120',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_duplicate = Employee::where('name',trim(strtoupper($input['name'])))
                                        ->orwhere('email',trim(strtolower($input['email'])))
                                        ->orwhere('phone',trim($input['phone']))
                                        ->orwhere('national_id',trim($input['national_id']))
                                        ->count();


            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }

          	
            $image= $request->file('certificate_image');
            if (empty($image)) 
            {
                $path = "";
                
            }
            else
            {
                foreach ($image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/employees/certificates');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'employees/certificates/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Uploading");
                    }
                }
                   
            }


            
          		
          		$data = array(
          						'name'=>trim(strtoupper($input['name'])),
                      'email'=>trim(strtolower($input['email'])),
                      'phone'=>trim($input['phone']),
                      'national_id'=>trim($input['national_id']),
                      'dob'=>$input['dob'],
                      'nationality'=>$input['nationality'],
                      'job_type_id'=>$input['job_type'],
                      'sport_id'=>$input['sport'],
                      'job_title'=>$input['job_title'],
                      'salary'=>$input['salary'] + (float)$input['commission'],
                      'commission'=>$input['commission'],
                      'joining_date'  => $input['joining_date'],
                      'working_days'  => $input['working_days'],
                      'annual_leaves' => $input['annual_leaves'],
                      'id_expiry_date'=>$input['expiry_date'],
                      'certificate_image'=>empty($path)?"":implode(",", $path),
          						'created_at' => date('Y-m-d H:i:s'),
          						'updated_at' => date('Y-m-d H:i:s'),
          					);

          		if(Employee::insert($data))
          		{
	          		return redirect()->route('employee.add-employee')->with('success',__('web.Data Added Successfully'));
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

    public function EditEmployee(Request $request,$id)
    {	
        try 
        {
        	$employee = Employee::where('id',$id)->first();

        	if ($employee == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

          $job_type = JobType::get();
          $sport = Sports::get();
          return view('employee.edit',compact('employee','job_type','sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateEmployee(Request $request)
    {	
        try 
        { 
             $validator = \Validator::make($request->all(), 
                                        [
                                          'name' => 'required',
                                          'email' => 'required|email',
                                          'phone'    => 'required',
                                          'dob'    => 'required',
                                          'nationality'    => 'required',
                                          'national_id'    => 'required|numeric',
                                          'job_type'    => 'required',
                                          'job_title'    => 'required',
                                          'salary'    => 'required|numeric',
                                          // 'commission'    => 'required|numeric',
                                          'joining_date'    => 'required',
                                          'working_days'    => 'required',
                                          'annual_leaves'    => 'required',
                                          'expiry_date'    => 'required',
                                          'certificate_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,docx|max:5120',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_duplicate = Employee::where('id','!=',$input['id'])
                                       ->where(function($query) use ($input){
                                            $query->orwhere('name',trim(strtoupper($input['name'])));
                                            $query->orwhere('email',trim(strtolower($input['email'])));
                                            $query->orwhere('phone',trim($input['phone']));
                                            $query->orwhere('national_id',trim($input['national_id']));
                                        })
                                        ->count();


            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }

            $get_emp = Employee::where('id',$input['id'])->first();

            $image= $request->file('certificate_image');
            if (empty($image)) 
            {
                $certificate_image = $get_emp->certificate_image;
                
            }
            else
            {
                foreach ($image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/employees/certificates');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'employees/certificates/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Uploading");
                    }
                }
                
                if (empty($get_emp->certificate_image)) 
                {
                  $certificate_image = implode(",", $path);
                }
                else
                {
                  $certificate_image = $get_emp->certificate_image.",".implode(",", $path);
                }
            }



              $data = array(
                      'name'=>trim(strtoupper($input['name'])),
                      'email'=>trim(strtolower($input['email'])),
                      'phone'=>trim($input['phone']),
                      'national_id'=>trim($input['national_id']),
                      'dob'=>$input['dob'],
                      'nationality'=>$input['nationality'],
                      'job_type_id'=>$input['job_type'],
                      'sport_id'=>($input['job_type'] == 2)?$input['sport']:0,
                      'job_title'=>$input['job_title'],
                      'salary'=>$input['salary'] + (float)$input['commission'],
                      'commission'=>$input['commission'],
                      'id_expiry_date'=>$input['expiry_date'],
                      'joining_date'  => $input['joining_date'],
                      'working_days'  => $input['working_days'],
                      'annual_leaves' => $input['annual_leaves'],
                      'certificate_image'=>$certificate_image,
                    );

              if(Employee::where('id',$input['id'])->update($data))
              {
                return redirect()->route('employee.view-employee')->with('success',__('web.Data Updated Successfully'));
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

    public function DeleteEmployee(Request $request,$id)
    {	
        try 
        { 

           //delete employe file docs first
            $get_emp = Employee::where('id',$id)->first();
            $files = explode(",", $get_emp->certificate_image);
            foreach ($files as $key) 
            {
              $image_path = public_path('images/'.$key);  
              if(File::exists($image_path)) 
              {
                  File::delete($image_path);
              }
            }


        	if(Employee::where('id',$id)->delete())
        	{
	          	return redirect()->route('employee.view-employee')->with('success',__('web.Data Deleted Successfully'));
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



    public function ViewEmployee(Request $request)
    {	
        try 
        {
          $from_date = "";
          $to_date = "";
          $message = "";
        	$employee = Employee::orderBy('created_at','asc')->get();
       		return view('employee.view',compact('employee','from_date','to_date','message'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function ViewCoaches(Request $request)
    { 
        try 
        {
          $coach = Employee::where('job_type_id',2)->orderBy('created_at','asc')->get();
          return view('employee.coaches',compact('coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


     public function ViewCoachesTimeTable(Request $request,$coach_id)
    { 
        try 
        { 
          $classes = Classes::where('coach_id',$coach_id)->get();

          if (count($classes) == 0) 
          { 
            ?>
            <br><center><p class="text-danger"><?php echo __('web.No Data Found'); ?></p></center>
            <?php 
            return false;
          }
          ?>
          <div class="card-body table-responsive">
            <table class="table" id="DataTableTimeTable">
                  
               
          <?php
          foreach ($classes as $key) 
          {
            ?>

                    <tr style="background: slategray; color: white;">
                      <td colspan="3"><b><?php echo __('web.Name')?>:</b> <?php echo $key->coach_name->name?></td>
                      <td colspan="2"><b><?php echo __('web.Sports')?>:</b> <?php echo $key->sport_name->name?></td>
                      <td colspan="3"><b><?php echo __('web.Duration')?>:</b> <?php echo $key['duration']; ?></td>
                    </tr>


                    <tr>
                      <th width="2%">#</th>
                      <th width="5%"><?php echo __('web.Day') ?></th>
                      <th width="12%"><?php echo __('web.From Time') ?></th>
                      <th width="10%"><?php echo __('web.To Time') ?></th>
                      <th width="10%"><?php echo __('web.Location') ?></th>
                      <th width="7%"><?php echo __('web.Capacity') ?></th>
                      <th width="7%"><?php echo __('web.Fee') ?></th>
                      <th width="8%"><?php echo __('web.Members') ?></th>
                    </tr>
                 
                    <?php 
                      $classes_day = ClassesDays::where('class_id',$key['id'])->orderBy('days_sort','asc')->orderBy('from_time','asc')->get(); 
                      $count = 1; foreach($classes_day as $key): 
                    ?>
                    <tr>
                      <td><?php echo $count ?></td>
                      <td><?php echo __('web.'.ucfirst($key['day'])) ?></td>
                      <td><?php echo date("h:i a",strtotime($key['from_time'])) ?></td>
                      <td><?php echo date("h:i a",strtotime($key['to_time'])) ?></td>
                      <td><?php echo $key['location'] ?></td>
                      <td><?php echo $key['capacity'] ?></td>
                      <td><?php echo $key['fee'] ?></td>
                      <td><?php echo $key['members_count'] ?></td>
                    </tr>
                    
                    <?php $count++; endforeach; ?>


                    <tr><td colspan="8"><br></td></tr>
            <?php
          }

          ?>
            </table>
          </div>
         
          <?php
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }




    public function PendingSalaryEmployee(Request $request)
    { 
        try 
        {
            $paid_employees = EmployeeSalary::whereMonth('salary_date',date('m'))->whereYear('salary_date',date('Y'))->pluck('employee_id');


            $employee = Employee::whereNotIn('id',$paid_employees)->orderBy('created_at','asc')->get();
            $from_date = "";
            $to_date = "";
          return view('employee.pending_salary',compact('employee','from_date','to_date'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function FilterEmployeePendings(Request $request)
    { 
        try 
        {
          $input = $request->all();

            
          if (empty($input['from_date']) || empty($input['to_date'])) 
          {
            $paid_employees = EmployeeSalary::whereMonth('salary_date',date('m'))->whereYear('salary_date',date('Y'))->pluck('employee_id');


            $employee = Employee::whereNotIn('id',$paid_employees)->orderBy('created_at','asc')->get();

            $from_date = "";
            $to_date   = "";
          }
          else
          {

            $paid_employees = EmployeeSalary::whereMonth('salary_date',date('m'))->whereYear('salary_date',date('Y'))->pluck('employee_id');


            $employee = Employee::whereBetween('created_at',[$input['from_date'],$input['to_date']])->whereNotIn('id',$paid_employees)->orderBy('created_at','asc')->get();

            $from_date = $input['from_date'];
            $to_date = $input['to_date'];
          }

          return view('employee.pending_salary',compact('employee','from_date','to_date'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function IdExpiryEmployee(Request $request)
    { 
        try 
        {
            $plus_one_month = date("Y-m-d", strtotime("+1 month", strtotime(date('Y-m-d'))));
            $employee = Employee::whereDate('id_expiry_date','<=',$plus_one_month)->orwhereDate('id_expiry_date','<=',date('Y-m-d'))->orderBy('created_at','asc')->get();
            $from_date = "";
            $to_date = "";
          return view('employee.id_expiry',compact('employee','from_date','to_date'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterEmployeeExpiry(Request $request)
    { 
        try 
        {
          $input = $request->all();

            
          if (empty($input['from_date']) || empty($input['to_date'])) 
          {
            $plus_one_month = date("Y-m-d", strtotime("+1 month", strtotime(date('Y-m-d'))));
            $employee = Employee::whereDate('id_expiry_date','<=',$plus_one_month)->orwhereDate('id_expiry_date','<=',date('Y-m-d'))->orderBy('created_at','asc')->get();

            $from_date = "";
            $to_date   = "";
          }
          else
          {

            $plus_one_month = date("Y-m-d", strtotime("+1 month", strtotime(date('Y-m-d'))));
            $employee = Employee::whereBetween('created_at',[$input['from_date'],$input['to_date']])->where(
           function($query) use ($plus_one_month) {
             return $query
                    ->whereDate('id_expiry_date','<=',$plus_one_month)
                    ->orwhereDate('id_expiry_date','<=',date('Y-m-d'));
            })->orderBy('created_at','asc')->get();

            $from_date = $input['from_date'];
            $to_date = $input['to_date'];
          }

          return view('employee.id_expiry',compact('employee','from_date','to_date'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function FilterEmployee(Request $request)
    { 
        try 
        {
          $input = $request->all();

            
          if (empty($input['from_date']) || empty($input['to_date'])) 
          {
            $employee = Employee::orderBy('created_at','asc')->get();

            $from_date = "";
            $to_date   = "";
            $message   = "";
          }
          else
          {

            $employee = Employee::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->orderBy('created_at','asc')->get();

            $from_date = $input['from_date'];
            $to_date = $input['to_date'];
            $message = __('web.Employees Record From :from_date To :to_date',['from_date'=>$input['from_date'],'to_date'=>$input['to_date']]);
          }

          return view('employee.view',compact('employee','from_date','to_date','message'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }




    public function DetailsEmployee(Request $request,$id)
    { 
        try 
        {
          $get_employee = Employee::where('id',$id)->first();

          if ($get_employee == "")
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
                <td><b><?php echo __('web.Name')?>:</b> <?php echo $get_employee->name; ?></td>
                <td><b><?php echo __('web.Email')?>:</b> <?php echo $get_employee->email; ?></td>
                <td><b><?php echo __('web.Phone')?>:</b> <?php echo $get_employee->phone; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Date of Birth')?>:</b> <?php echo $get_employee->dob; ?></td>
                <td><b><?php echo __('web.Nationality')?>:</b> <?php echo $get_employee->nationality; ?></td>
                <td><b><?php echo __('web.National ID')?>:</b> <?php echo $get_employee->national_id; ?></td>
              </tr>

              <tr>
               
                <td><b><?php echo __('web.Salary')?>:</b> <?php echo $get_employee->salary - (float)$get_employee->commission; ?></td>
                <td><b><?php echo __('web.Commission')?>:</b> <?php echo $get_employee->commission; ?></td>
                <td><b><?php echo __('web.ID Expiry Date')?>:</b> <?php echo $get_employee->id_expiry_date; ?></td>
              </tr>
              
              <tr>
                
                 <td><b><?php echo __('web.Job_Type')?>:</b> <?php echo $get_employee->job_type_name->name; ?></td>

                  <?php if($get_employee->job_type_id == 2): ?>
                    <td><b><?php echo __('web.Sports')?>:</b> <?php echo $get_employee->sport_name->name; ?></td>
                  <?php endif;?>

                  <td <?php if ($get_employee->job_type_id != 2): ?>
                    colspan="2"
                  <?php endif ?>><b><?php echo __('web.Job Title')?>:</b> <?php echo $get_employee->job_title; ?></td>
                
              </tr>


              <tr>
               
                <td><b><?php echo __('web.Joining Date')?>:</b> <?php echo empty($get_employee->joining_date)?"-":date("d-M-Y",strtotime($get_employee->joining_date)); ?></td>
                <td><b><?php echo __('web.Working Days')?>:</b> <?php echo $get_employee->working_days; ?></td>
                <td><b><?php echo __('web.Annual Leaves')?>:</b> <?php echo $get_employee->annual_leaves; ?></td>
              </tr>


              <tr>
                <td colspan="3"><b><?php echo __('web.Certificates')?>:</b>
                  <ul>
                    <?php 
                    if (!empty($get_employee->certificate_image)) 
                    {
                    $cert = explode(",", $get_employee->certificate_image); 
                    foreach ($cert as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $key ?>"></a></li>
                    <?php endforeach; 
                    }
                    ?>
                  </ul>
                </td>
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



    public function DetailsEmployeeSalary(Request $request,$id)
    { 
        try 
        {
          $get_salary = EmployeeSalary::where('id',$id)->first();

          if ($get_salary == "")
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
                <td><b><?php echo __('web.Name')?>:</b> <?php echo $get_salary->employee_data->name; ?></td>
                <td><b><?php echo __('web.Email')?>:</b> <?php echo $get_salary->employee_data->email; ?></td>
                <td><b><?php echo __('web.Phone')?>:</b> <?php echo $get_salary->employee_data->phone; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Salary')?>:</b> <?php echo $get_salary->salary; ?></td>
                <td><b><?php echo __('web.Deduction')?>:</b> <?php echo $get_salary->deduction; ?></td>
                <td><b><?php echo __('web.Honorarium')?>:</b> <?php echo $get_salary->honorarium; ?></td>
              </tr>

              <tr>
               
                <td><b><?php echo __('web.Salary Date')?>:</b> <?php echo $get_salary->salary_date; ?></td>
                <td><b><?php echo __('web.Total Salary')?>:</b> <?php echo $get_salary->total_salary; ?></td>
                <td></td>
              </tr>
              
              <tr><td colspan="3"><b><?php echo __('web.Deduction Reason')?>:</b> <?php echo $get_salary->deduction_reason; ?></td></tr>
              <tr><td colspan="3"><b><?php echo __('web.Honorarium Reason')?>:</b> <?php echo $get_salary->honorarium_reason; ?></td></tr>
             
            </tbody>
          </table>


           

          <?php




        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }





    public function SaveEmployeeSalary(Request $request)
    { 
        try 
        { 
             $validator = \Validator::make($request->all(), 
                                        [
                                          'employee_name' => 'required',
                                          'employee_salary' => 'required|numeric',
                                          'salary_date' => 'required',
                                          'total_salary' => 'required|numeric',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $input['salary_date'] =  date("Y-m-d",strtotime($input['salary_date']));

            $check_duplicate = EmployeeSalary::where('employee_id',$input['employee_id'])
                                             ->whereDate('salary_date',$input['salary_date'])
                                             ->count();


            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }

              $deduction    = isset($input['deduction'])?$input['deduction']:0;
              $honorarium   = isset($input['honorarium'])?$input['honorarium']:0;
              $total_salary = $input['employee_salary'] + $honorarium - $deduction;

              
              $data = array(
                      'employee_id' => $input['employee_id'],
                      'salary'      => $input['employee_salary'],
                      'deduction'   => $deduction,
                      'honorarium'   => $honorarium,
                      'deduction_reason' =>$input['deduction_reason'],
                      'honorarium_reason' =>$input['honorarium_reason'],
                      'total_salary'  =>$total_salary,
                      'salary_date'   => $input['salary_date'],
                      'created_at'  => date('Y-m-d H:i:s'),
                      'updated_at'  => date('Y-m-d H:i:s'),
                    );

              if(EmployeeSalary::insert($data))
              {
                return redirect()->route('employee.view-employee')->with('success',__('web.Data Added Successfully'));
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

    public function UpdateEmployeeSalary(Request $request)
    { 
        try 
        {   
             $validator = \Validator::make($request->all(), 
                                        [
                                          'employee_name' => 'required',
                                          'employee_salary' => 'required|numeric',
                                          'salary_date' => 'required',
                                          'total_salary' => 'required|numeric',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $input['salary_date'] =  date("Y-m-d",strtotime($input['salary_date']));

            $check_duplicate = EmployeeSalary::where('employee_id',$input['employee_id'])
                                             ->whereDate('salary_date',$input['salary_date'])
                                             ->where('id','!=',$input['salary_id'])
                                             ->count();


            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }

              $deduction    = isset($input['deduction'])?$input['deduction']:0;
              $honorarium   = isset($input['honorarium'])?$input['honorarium']:0;
              $total_salary = $input['employee_salary'] + $honorarium - $deduction;

              
              $data = array(
                      'salary'      => $input['employee_salary'],
                      'deduction'   => $deduction,
                      'honorarium'   => $honorarium,
                      'deduction_reason' =>$input['deduction_reason'],
                      'honorarium_reason' =>$input['honorarium_reason'],
                      'total_salary'  =>$total_salary,
                      'salary_date'   => $input['salary_date'],
                    );

              if(EmployeeSalary::where('id',$input['salary_id'])->update($data))
              {
                return redirect()->route('employee.view-salary')->with('success',__('web.Data Updated Successfully'));
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

    public function ViewEmployeeSalary(Request $request)
    { 
        try 
        { 
          
          $salary = EmployeeSalary::orderBy('created_at','asc')->get();
          $from_date = "";
          $to_date   = "";
          return view('employee.salary',compact('salary','from_date','to_date'));          

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function DeleteEmployeeSalary(Request $request,$id)
    { 
        try 
        { 


          if(EmployeeSalary::where('id',$id)->delete())
          {
              return redirect()->route('employee.view-salary')->with('success',__('web.Data Deleted Successfully'));
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

    public function FilterEmployeeSalary(Request $request)
    { 
        try 
        { 

          $input = $request->all();

          if (empty($input['from_date']) || empty($input['to_date'])) 
          {
            $salary = EmployeeSalary::orderBy('created_at','asc')->get();
            $from_date = "";
            $to_date = "";
          }
          else
          {
           
                $salary = EmployeeSalary::whereDate('salary_date','>=',$input['from_date'])->whereDate('salary_date','<=',$input['to_date'])->orderBy('created_at','asc')->get();
            
            $from_date = $input['from_date'];
            $to_date = $input['to_date'];
          }

          return view('employee.salary',compact('salary','from_date','to_date'));          
        
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function DeleteEmployeeFiles(Request $request)
    {
        try 
        { 

          $input = $request->all();
          $get_files = Employee::where('id',$input['employee_id'])->first();

          if ($get_files == "") 
          {
            return false;
          }
            $files_arr = explode(",",$get_files->certificate_image);
            if (in_array($input['file'], $files_arr)) 
            {
                unset($files_arr[array_search($input['file'],$files_arr)]);
            }

            $files_arr = implode(",", $files_arr);

          //also delete storage file
          $image_path = public_path('images/'.$input['file']);  
              if(File::exists($image_path)) 
              {
                  File::delete($image_path);
              }

          Employee::where('id',$input['employee_id'])->update(array('certificate_image'=>$files_arr));

          return true;
        
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

}
