<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\JobType;
use App\Models\Sports;

use App\Models\Classes;
use App\Models\ClassesDays;



class ClassesController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function AddClasses(Request $request)
    {	
        try 
        {
          $sport = Sports::get();
          return view('classes.add',compact('sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


     public function CoachNamesList(Request $request,$sport_id)
    {	
        try 
        {	
        	$coach = Employee::where('job_type_id',2)->where('sport_id',$sport_id)->orderBy('name','asc')->get();

          if (count($coach) == 0) 
          {
          	?>
            <option value=""><?php echo __('web.Select') ?></option>
            <?php
          }
          else
          {
            ?>
            <option value=""><?php echo __('web.Select') ?></option>
            <?php
          	foreach ($coach as $key) 
          	{  
              
          		?>
          			<option value="<?php echo $key['id'] ?>"><?php echo $key['name'] ?></option>
          		<?php
          	}
          }
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function SaveClasses(Request $request)
    {	
        try 
        { 	
        	$validator = \Validator::make($request->all(), 
                                        [
                                          'sport' => 'required',
                                          'coach' => 'required',
                                          'duration' => 'required',
                                          'class-day.*' => 'required',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            
            $input = $request->all();

            if (Classes::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->where('duration',$input['duration'])->count() > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }


            $data = array(
            			"sport_id"		=> $input['sport'],
            			"coach_id"		=> $input['coach'],
            			"duration"		=> $input['duration'],
            			"created_at"	=> date('Y-m-d H:i:s'),
            			"updated_at"	=> date('Y-m-d H:i:s'),
            		);

            $class_id = Classes::insertGetId($data);

            if ($class_id) 
            {
 				for ($i=0; $i < count($input['class-day']) ; $i++) 
	            { 	
	            	ClassesDays::insert(array(
	            					"class_id"			=> $class_id,
	            					"day"				=> $input['class-day'][$i],
	            					"from_time"			=> $input['class-from-time'][$i],
	            					"to_time"			=> $input['class-to-time'][$i],
	            					"location"			=> $input['class-location'][$i],
	            					"capacity"			=> $input['class-capacity'][$i],
	            					"fee"				=> $input['class-fee'][$i],
	            					"members_count"	=> 0,
                        "days_sort"     => $this->DaysSort($input['class-day'][$i]),
	            					"created_at"		=> date("Y-m-d H:i:s"),
	            					"updated_at"		=> date("Y-m-d H:i:s"),
	            	));

	            }


	          	return redirect()->route('classes.add-classes')->with('success',__('web.Data Added Successfully'));

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

    public function DaysSort($day)
    {
      if ($day == "monday") 
      {
        return 1;
      }
      elseif ($day == "tuesday") 
      {
        return 2;
      }
      elseif ($day == "wednesday") 
      {
        return 3;
      }
      elseif ($day == "thursday") 
      {
        return 4;
      }
      elseif ($day == "friday") 
      {
        return 5;
      }
      elseif ($day == "saturday") 
      {
        return 6;
      }
      else
      {
        return 7;
      }
    }

    public function EditClasses(Request $request,$id)
    {	
        try 
        {
        	$classes = Classes::where('id',$id)->first();

        	if ($classes == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

          $sport = Sports::get();
          $coach = Employee::where('id',$classes->coach_id)->first();
          $classes_day = ClassesDays::where('class_id',$id)->get();
          return view('classes.edit',compact('classes','coach','sport','classes_day'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateClasses(Request $request)
    {	
        try 
        { 
        	$validator = \Validator::make($request->all(), 
                                        [
                                          'sport' => 'required',
                                          'coach' => 'required',
                                          'duration' => 'required',
                                          'class-day.*' => 'required',
                                          
                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            
            $input = $request->all();

            if (Classes::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->where('duration',$input['duration'])->where('id','!=',$input['id'])->count() > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }


            $data = array(
            			"sport_id"		=> $input['sport'],
            			"coach_id"		=> $input['coach'],
            			"duration"		=> $input['duration'],
            		);



            
            if (Classes::where('id',$input['id'])->update($data)) 
            {
            	ClassesDays::where('class_id',$input['id'])->delete();

            	for ($i=0; $i < count($input['class-day']) ; $i++) 
	            { 	
	            		ClassesDays::insert(array(
	            					"class_id"			=> $input['id'],
	            					"day"				=> $input['class-day'][$i],
	            					"from_time"			=> $input['class-from-time'][$i],
	            					"to_time"			=> $input['class-to-time'][$i],
	            					"location"			=> $input['class-location'][$i],
	            					"capacity"			=> $input['class-capacity'][$i],
	            					"fee"				=> $input['class-fee'][$i],
	            					"members_count"		=> 0,
                        "days_sort"     => $this->DaysSort($input['class-day'][$i]),
	            					"created_at"		=> date("Y-m-d H:i:s"),
	            					"updated_at"		=> date("Y-m-d H:i:s"),
	            		));

	            }
	          	return redirect()->route('classes.view-classes')->with('success',__('web.Data Updated Successfully'));

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

    public function DeleteClasses(Request $request,$id)
    {	
        try 
        {	
        	//First Delete Related Classes Days
        	ClassesDays::where('class_id',$id)->where('members_count',0)->delete();

        	if(Classes::where('id',$id)->delete())
        	{	
	          	return redirect()->route('classes.view-classes')->with('success',__('web.Data Deleted Successfully'));
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


    public function DeleteTimeTable(Request $request,$id)
    {	
        try 
        {	
        	$get_class_id = ClassesDays::where('id',$id)->first();

        	if ($get_class_id == "") 
        	{
        		return false;
        	}
          //at least one class day row should be left.. 
          //can not delete all classes days for one class.. one should be left
        	if (ClassesDays::where('class_id',$get_class_id->class_id)->count() == 1) 
        	{
        		return false;
        	}

         	if(ClassesDays::where('id',$id)->where('members_count',0)->delete())
        	{	
	          	return true;
        	}
        	else
        	{
				    return false;
        	}
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function ViewClasses(Request $request)
    {	
        try 
        {
         	$sport = Sports::get();

         	$classes = Classes::get();


         	return view('classes.view',compact('sport','classes'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function GetClasses(Request $request)
    {	
        try 
        {
            $input = $request->all();
         	
         	$classes = Classes::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->get();

         	if (count($classes) == 0) 
         	{	
         		?>
         		<br><center><p class="text-danger"><?php echo __('web.No Data Found'); ?></p></center>
         		<?php
         		return false;
         	}

         	?>
         		<div class="card-body p-4 table-responsive">
                  <table class="table table-striped" id="DataTableClasses">
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="10%"><?php echo __('web.Sports') ?></th>
                      <th width="10%"><?php echo __('web.Coach') ?></th>
                      <th width="10%"><?php echo __('web.Duration') ?></th>
                      <th width="10%"><?php echo __('web.Time Table') ?></th>
                      <th style="text-align: center;" width="20%"><?php echo __('web.Action') ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php $count = 1; foreach($classes as $key): ?>
                    <tr>
                      <td><?php echo $count ?></td>
                      <td><?php echo $key->sport_name->name ?></td>
                      <td><?php echo $key->coach_name->name ?></td>
                      <td><?php echo $key['duration'] ?></td>
                      <td><a href="javascript:void(0)" onclick='GetClassesTimeTable("<?php echo $key['id'] ?>")'><?php echo __('web.View')?></a></td>
                      <td style="text-align: center;"><a title="<?php echo __('web.Edit') ?>" data-toggle="tooltip" href="<?php echo route('classes.edit-classes',[$key['id']]) ?>"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="<?php echo __('web.Delete') ?>" data-toggle="tooltip" onclick="return confirm('<?php echo __("web.Are you sure?") ?>')" href="<?php echo route('classes.delete-classes',[$key['id']]) ?>"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                  <?php $count++; endforeach; ?>
                  </tbody>
                  </table>
                </div>
                <script type="text/javascript">
             		$(document).ready(function() {
	        			$('#DataTableClasses').DataTable({
	                    "pageLength": 10,
	                    dom: 'Bfrtip',
	                    buttons: [
	                        'copy', 'csv', 'excel', 'pdf', 'print'
	                    ]
                		} );
 				    } );
                </script>
        	<?php
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function ViewTimeTable(Request $request,$class_id)
    {	
        try 
        {
        	$classes_day = ClassesDays::where('class_id',$class_id)->orderBy('days_sort','asc')->orderBy('from_time','asc')->get();

        	if (count($classes_day) == 0) 
        	{	
        		?>
        		<br><center><p class="text-danger"><?php echo __('web.No Data Found'); ?></p></center>
        		<?php 
        		return false;
        	}


        	?>

        		<div class="card-body table-responsive">
                  <table class="table table-striped" id="DataTableTimeTable">
                  <thead>
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
                  </thead>
                  <tbody>
                  <?php $count = 1; foreach($classes_day as $key): ?>
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
                  </tbody>
                  </table>
                </div>
                <script type="text/javascript">
             		$(document).ready(function() {
	        			$('#DataTableTimeTable').DataTable({
	                    "pageLength": 10,
	                    dom: 'Bfrtip',
	                    buttons: [
	                        'copy', 'csv', 'excel', 'pdf', 'print'
	                    ]
                		} );
 				    } );
                </script>


        	<?php


         	
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

}
