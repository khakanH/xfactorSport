<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Members;
use App\Models\Sports;
use App\Models\Belt;
use App\Models\BeltExam;

use File;

class BeltExamController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function AddBeltExam(Request $request)
    {	
        try 
        {
          $belt = Belt::get();
          $sport = Sports::get();
            $member = Members::get();

          return view('belt_exam.add',compact('sport','belt','member'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    
    public function SaveBeltExam(Request $request)
    {	
        try 
        { 
            $validator = \Validator::make($request->all(), 
                                        [
                                          'belt_exam_name' => 'required',
                                          'name' => 'required',
                                          'sport' => 'required',
                                          'coach' => 'required',
                                          'current_belt'    => 'required',
                                          'next_belt'    => 'required',
                                          'exam_date'    => 'required',
                                          'result_date'    => 'required',
                                          'total_amount'    => 'required',
                                          'paid_amount'    => 'required',
                                          'remaining_amount'    => 'required',
                                          'document.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,docx|max:5120',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_member = Members::where('id',$input['member_id'])->first();

            if ($check_member == "") 
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
                   
                    $destinationPath = public_path('/images/members/belt_exam');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'members/belt_exam/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Uploading");
                    }
                }
                   
            }


            
          		
          		$data = array(
                        'belt_exam_name' => $input['belt_exam_name'],
	                    	'member_id' => $input['member_id'],
                        'sport_id' => $input['sport'],
	                    	'coach_id' => $input['coach'],
	                    	'current_belt_id' => $input['current_belt'],
	                    	'next_belt_id' => $input['next_belt'],
	                    	'exam_date' => $input['exam_date'],
	                    	'result_date' => $input['result_date'],
	                    	'total_amount' => $input['total_amount'],
	                    	'paid_amount' => $input['paid_amount'],
	                    	'remaining_amount' => $input['remaining_amount'],
	                    	'result' => $input['result'],
	                    	'documents'=>empty($path)?"":implode(",", $path),
	          				'created_at' => date('Y-m-d H:i:s'),
	          				'updated_at' => date('Y-m-d H:i:s'),
	          			);

          		if(BeltExam::insert($data))
          		{
	          		return redirect()->route('register.add-belt-exam')->with('success',__('web.Data Added Successfully'));
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

    public function EditBeltExam(Request $request,$id)
    {	
        try 
        {
        	$belt_exam = BeltExam::where('id',$id)->first();

        	if ($belt_exam == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

          	$sport = Sports::get();
            $belt = Belt::get();
            $member = Members::get();
          	return view('belt_exam.edit',compact('belt_exam','sport','belt','member'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateBeltExam(Request $request)
    {	
        try 
        {

           $validator = \Validator::make($request->all(), 
                                        [
                                          'belt_exam_name' => 'required',
                                          'name' => 'required',
                                          'sport' => 'required',
                                          'coach' => 'required',
                                          'current_belt'    => 'required',
                                          'next_belt'    => 'required',
                                          'exam_date'    => 'required',
                                          'result_date'    => 'required',
                                          'total_amount'    => 'required',
                                          'paid_amount'    => 'required',
                                          'remaining_amount'    => 'required',
                                          'document.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,docx|max:5120',

                                        ]);


            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_member = Members::where('id',$input['member_id'])->first();

            if ($check_member == "") 
            {
                return redirect()->back()->withInput()->with('failed',__('web.Invalid Data'));
            }


            $get_belt_exam = BeltExam::where('id',$input['id'])->first();

          	
            $image= $request->file('document');
            if (empty($image)) 
            {
                $docs_img = $get_belt_exam->documents;
                
            }
            else
            {
                foreach ($image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/belt_exam');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'members/belt_exam/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Uploading");
                    }
                }
                 

                if (empty($get_belt_exam->documents)) 
                {
	                $docs_img = implode(",", $path);
                }
                else
                {
	                $docs_img = $get_belt_exam->documents.",".implode(",", $path);
                }

            }


            
          		
          		$data = array(
                        'belt_exam_name' => $input['belt_exam_name'],
	                    	'member_id' => $input['member_id'],
                        'sport_id' => $input['sport'],
	                    	'coach_id' => $input['coach'],
	                    	'current_belt_id' => $input['current_belt'],
	                    	'next_belt_id' => $input['next_belt'],
	                    	'exam_date' => $input['exam_date'],
	                    	'result_date' => $input['result_date'],
	                    	'total_amount' => $input['total_amount'],
	                    	'paid_amount' => $input['paid_amount'],
	                    	'remaining_amount' => $input['remaining_amount'],
	                    	'result' => $input['result'],
	                    	'documents'=>$docs_img,
	          			);


          		if(BeltExam::where('id',$input['id'])->update($data))
          		{
	          		return redirect()->route('register.view-belt-exam')->with('success',__('web.Data Updated Successfully'));
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

    


    public function ViewBeltExam(Request $request)
    {	
        try 
        {
        	$belt_exam = BeltExam::orderBy('created_at','asc')->get();
          $sport = Sports::get();
          $from_date = "";
          $to_date = "";
          $selected_sport = "";
          $selected_coach = "";
       		return view('belt_exam.view',compact('belt_exam','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterBeltExam(Request $request)
    { 
        try 
        {

          $input = $request->all();

          if (empty($input['from_date']) || empty($input['to_date'])) 
          {
            if (empty($input['sport'])) 
            {
              if (empty($input['coach'])) 
              {
                $belt_exam = BeltExam::orderBy('created_at','asc')->get();
              }
              else
              {
                $belt_exam = BeltExam::where('coach_id',$input['coach'])->orderBy('created_at','asc')->get();

              }
              
            }
            else
            { 
              if (empty($input['coach'])) 
              {
                $belt_exam = BeltExam::where('sport_id',$input['sport'])->orderBy('created_at','asc')->get();
              }
              else
              {
                $belt_exam = BeltExam::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->orderBy('created_at','asc')->get();
              }
            }
            $from_date = "";
            $to_date = "";
          }
          else
          {
            if (empty($input['sport'])) 
            { 
              if (empty($input['coach'])) 
              {
                $belt_exam = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->orderBy('created_at','asc')->get();
              }
              else
              {
                $belt_exam = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->where('coach_id',$input['coach'])->orderBy('created_at','asc')->get();

              }
            }
            else
            { 
              if (empty($input['coach'])) 
              {
                $belt_exam = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->where('sport_id',$input['sport'])->orderBy('created_at','asc')->get();
              }
              else
              {
                $belt_exam = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->orderBy('created_at','asc')->get();
                
              }
            }
            $from_date = $input['from_date'];
            $to_date = $input['to_date'];
          }







          $sport = Sports::get();
          $selected_sport = $input['sport'];
          $selected_coach = $input['coach'];
          return view('belt_exam.view',compact('belt_exam','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



	public function DeleteBeltExam(Request $request,$id)
    {	
        try 
        {	

        	 //delete belt exam file docs first
            $get_belt_exam = BeltExam::where('id',$id)->first();
            $images = explode(",", $get_belt_exam->documents);
            foreach ($images as $key) 
            {
	          	$image_path = public_path('images/'.$key);  
    	        if(File::exists($image_path)) 
        	    {
            	    File::delete($image_path);
            	}
            }


        	if(BeltExam::where('id',$id)->delete())
        	{
	          	return redirect()->route('register.view-belt-exam')->with('success',__('web.Data Deleted Successfully'));
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

    

    public function DetailsBeltExam(Request $request,$id)
    { 
        try 
        {
          $get_belt_exam = BeltExam::where('id',$id)->first();

          if ($get_belt_exam == "")
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
                <td style="text-align: center;" colspan="3"><b><?php echo __('web.Belt Exam Name')?>:</b> <?php echo $get_belt_exam->belt_exam_name; ?></td>
              </tr>
              <tr>
                <td><b><?php echo __('web.Name')?>:</b> <?php echo $get_belt_exam->member_info->name; ?></td>
                <td><b><?php echo __('web.Email')?>:</b> <?php echo $get_belt_exam->member_info->email; ?></td>
                <td><b><?php echo __('web.Phone')?>:</b> <?php echo $get_belt_exam->member_info->phone; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Age')?>:</b> <?php echo $get_belt_exam->member_info->age; ?></td>
                <td><b><?php echo __('web.Sports')?>:</b> <?php echo $get_belt_exam->sport_name->name; ?></td>
                <td><b><?php echo __('web.Current Belt')?>:</b> <?php echo $get_belt_exam->current_belt_name->name; ?></td>
              </tr>

              <tr>
              	<td><b><?php echo __('web.Next Belt')?>:</b> <?php echo $get_belt_exam->next_belt_name->name; ?></td>
              	<td><b><?php echo __('web.Exam Date')?>:</b> <?php echo $get_belt_exam->exam_date; ?></td>
              	<td><b><?php echo __('web.Result Date')?>:</b> <?php echo $get_belt_exam->result_date; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Total Amount')?>:</b> <?php echo $get_belt_exam->total_amount; ?></td>
                <td><b><?php echo __('web.Paid Amount')?>:</b> <?php echo $get_belt_exam->paid_amount; ?></td>
                <td><b><?php echo __('web.Remaining Amount')?>:</b> <?php echo $get_belt_exam->remaining_amount; ?></td>
              </tr>

               <tr>
                <td colspan="3"><b><?php echo __('web.Documents')?>:</b>
                  <ul>
                    <?php 
                    $docs = explode(",", $get_belt_exam->documents); 
                    foreach ($docs as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><?php echo $key; ?></a></li>
                    <?php endforeach; ?>
                  </ul></td>
              </tr>

              
              
              <tr>
                <td colspan="3"><b><?php echo __('web.Result')?>:</b> <?php echo $get_belt_exam->result; ?></td>
                
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


}
