<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\Members;
use App\Models\MembersSports;
use App\Models\MembersRenewHistory;
use App\Models\MembersSportsClasses;
use App\Models\Sports;
use App\Models\Classes;
use App\Models\ClassesDays;
use App\Models\Belt;
use App\Models\Championship;
use App\Models\BeltExam;


use File;

class MemberController extends Controller
{
   
    public function __construct(Request $request)
    {   

    }

    public function AddMember(Request $request)
    {	
        try 
        {
          $sport = Sports::get();
          $belt = Belt::get();


          $time_table = ClassesDays::get();
          return view('member.add',compact('sport','belt','time_table'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function ClassesDurationList(Request $request,$sport_id,$coach_id)
    {	
        try 
        {
          $duration = Classes::where('sport_id',$sport_id)->where('coach_id',$coach_id)->orderBy('duration','asc')->get();
          if (count($duration) == 0) 
          {
          	return false;
          }

        	foreach ($duration as $key) 
        	{  
        		?>
        			<option value="<?php echo $key['id'] ?>"><?php echo $key['duration'] ?></option>
        		<?php
        	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function ClassesDaysList(Request $request,$classes_id)
    {	
        try 
        {
          $days = ClassesDays::where('class_id',$classes_id)->groupBy('day')->get();
          if (count($days) == 0) 
          {
          	?>
            <option value=""><?php echo __('web.No Data Found') ?></option>
          	<?php
          }

          $days = $days->sortBy('days_sort');

        	foreach ($days as $key) 
        	{  
        		?>
        			<option value="<?php echo $key['day'] ?>"><?php echo __('web.'.ucfirst($key['day'])) ?></option>
        		<?php
        	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function ClassesTimeList(Request $request)
    {	
        try 
        {	

        	$input = $request->all();

        	$class_id = $input['class_id'];
        	$classes_day_name = isset($input['days_name'])?$input['days_name']:array();

        	if ($class_id == 0) 
        	{	
        		return false;
        	}

        	if (count($classes_day_name) == 0) 
        	{	
        		return false;
        	}

          	$days = ClassesDays::where('class_id',$class_id)
          					   ->where(function ($query) use($classes_day_name) {
						            for ($i = 0; $i < count($classes_day_name); $i++)
						            {
						                $query->orwhere('day', 'like',  '%' . $classes_day_name[$i] .'%');
						            }      
						        })
          					   ->orderBy('days_sort','asc')
          					   ->orderBy('from_time','asc')
          					   ->get();

          	if (count($days) == 0) 
	        {
	          	?>
	            <option value=""><?php echo __('web.No Data Found') ?></option>
	          	<?php
	        }

	        	foreach ($days as $key) 
	        	{  
	        		if ($key['capacity'] > $key['members_count']) 
	        		{
	        			$disabled = "";
	        			$full_booked = "";
	        		}
	        		else
	        		{
	        			$disabled = "disabled";
	        			$full_booked = " - ".__('web.Full');
	        		}
	        		?>
	        			<option <?php echo $disabled; ?> value="<?php echo $key['id'] ?>"><?php echo __('web.'.ucfirst($key['day'])) .' - '. date("h:i a",strtotime($key['from_time']))." - ".ucfirst($key['location']).$full_booked ?> </option>
	        		<?php
	        	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function ClassesFee(Request $request)
    {	
        try 
        {	

        	$input = $request->all();

        	$classes_ids = isset($input['classes_ids'])?$input['classes_ids']:array();

        	if (count($classes_ids) == 0) 
        	{	
        		return false;
        	}

          $start_date = strtotime($input['start_date']);
          $duration   = Classes::where('id',$input['duration'])->first()->duration;
          $end_date   = strtotime('+'.$duration,$start_date);


          $fee = 0;
          foreach ($classes_ids as $key) 
          {
            $get_day = ClassesDays::where('id',$key)->first();

            if ($get_day->day == "sunday") 
            {
              $day = 0;
            }
            elseif ($get_day->day == "monday") 
            {
              $day = 1;
            }
            elseif ($get_day->day == "tuesday") 
            {
              $day = 2;
            }
            elseif ($get_day->day == "wednesday") 
            {
              $day = 3;
            }
            elseif ($get_day->day == "thursday") 
            {
              $day = 4;
            }
            elseif ($get_day->day == "friday") 
            {
              $day = 5;
            }
            elseif ($get_day->day == "saturday") 
            {
              $day = 6;
            }

            $weekdays_count = $this->countWeekDays($day,$start_date,$end_date);

            $fee = $fee + ($get_day->fee * $weekdays_count);

          }

          	echo $fee;
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function countWeekDays($day,$start,$end)
    {
      //get the day of the week for start and end dates (0-6)
      $w = array(date('w', $start), date('w', $end));

      //get partial week day count
      if ($w[0] < $w[1])
      {            
          $partialWeekCount = ($day >= $w[0] && $day <= $w[1]);
      }else if ($w[0] == $w[1])
      {
          $partialWeekCount = $w[0] == $day;
      }else
      {
          $partialWeekCount = ($day >= $w[0] || $day <= $w[1]);
      }

      //first count the number of complete weeks, then add 1 if $day falls in a partial week.
      return floor( ( $end-$start )/60/60/24/7) + $partialWeekCount;
    }
   














    public function SaveMember(Request $request)
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
                                          'gender'    => 'required',
                                          'age'    => 'required',
                                          'profile_image'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          'id_card_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          'certificate_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          'passport_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          
                                          'guardian_id_card_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',

                                          'sport.*' => 'required',
                                          'coach.*' => 'required',
                                          'start_date.*' => 'required',
                                          'duration.*' => 'required',
                                          'days.*' => 'required',
                                          'classes.*' => 'required',
                                          'fees.*' => 'required',


                                          'payment_date' => 'required',
                                          'total_amount' => 'required',
										  'grand_total' => 'required',
										  'paid_amount' => 'required',
										  'remaining_amount' => 'required',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_duplicate = Members::where('name',trim(strtoupper($input['name'])))
                                        // ->orwhere('email',trim(strtolower($input['email'])))
                                        // ->orwhere('phone',trim($input['phone']))
                                        ->orwhere('national_id',trim($input['national_id']))
                                        ->count();


            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }

			
			     //Profile Image Memeber
            $profile_image= $request->file('profile_image');
            if (empty($profile_image)) 
            {
                $profile_image_path = "";
                
            }
            else
            {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$profile_image->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/profile');

                    if($profile_image->move($destinationPath, $imagename))
                    {
                            $profile_image_path =  'members/profile/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Profile Image Uploading");
                    }
            }


            //ID Card Image Member
            $id_card_image= $request->file('id_card_image');
            if (empty($id_card_image)) 
            {
                $id_card_image_path = "";
                
            }
            else
            {
                foreach ($id_card_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/id_card');

                    if($key->move($destinationPath, $imagename))
                    {
                            $id_card_image_path[] =  'members/id_card/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for ID Card Image Uploading");
                    }
                }
                   
            }


            //Certificate Image Member
            $certificate_image= $request->file('certificate_image');
            if (empty($certificate_image)) 
            {
                $certificate_image_path = "";
                
            }
            else
            {
                foreach ($certificate_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/certificates');

                    if($key->move($destinationPath, $imagename))
                    {
                            $certificate_image_path[] =  'members/certificates/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Image Uploading");
                    }
                }
                   
            }



           	//Passport Image Member
            $passport_image= $request->file('passport_image');
            if (empty($passport_image)) 
            {
                $passport_image_path = "";
                
            }
            else
            {
                foreach ($passport_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/passport');

                    if($key->move($destinationPath, $imagename))
                    {
                            $passport_image_path[] =  'members/passport/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Passport Image Uploading");
                    }
                }
                   
            }


            //Guardian ID Card Image Member
            $guardian_id_card_image= $request->file('guardian_id_card_image');
            if (empty($guardian_id_card_image)) 
            {
                $guardian_id_card_image_path = "";
                
            }
            else
            {
                foreach ($guardian_id_card_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/guardian_id_card');

                    if($key->move($destinationPath, $imagename))
                    {
                            $guardian_id_card_image_path[] =  'members/guardian_id_card/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Guardian ID Card Image Uploading");
                    }
                }
                   
            }
            
          		
          	$data = array(
				'name'			=>trim(strtoupper($input['name'])),
				'email'			=>trim(strtolower($input['email'])),
				'phone'			=>trim($input['phone']),
				'national_id'	=>trim($input['national_id']),
				'dob'			=>$input['dob'],
				'nationality'	=>$input['nationality'],
				'gender'		=>$input['gender'],
				'age'			=>$input['age'],

				'profile_image'	=>$profile_image_path,
				'id_card_image'		=>empty($id_card_image_path)?"":implode(",", $id_card_image_path),
				'certificate_image'	=>empty($certificate_image_path)?"":implode(",", $certificate_image_path),
				'passport_image'	=>empty($passport_image_path)?"":implode(",", $passport_image_path),
				'guardian_name'	 =>isset($input['guardian_name'])?$input['guardian_name']:"",
				'guardian_phone' =>isset($input['guardian_phone'])?$input['guardian_phone']:"",
				'guardian_id_card_image' =>empty($guardian_id_card_image_path)?"":implode(",", $guardian_id_card_image_path),
				
				'total_amount' 		=> $input['total_amount'],
				'discount'			=> $input['discount'],
				'grand_total' 		=> $input['grand_total'],
				'paid_amount' 		=> $input['paid_amount'],
				'remaining_amount' 	=> $input['remaining_amount'],

				'payment_date'		=> $input['payment_date'],

				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
          	);

          		if($member_id = Members::insertGetId($data))
          		{	
          			for ($i=0; $i < count($input['sport']) ; $i++) 
		            { 	
		            	$get_duration = Classes::where('id',$input['duration'][$i])->first();
		            	if ($get_duration == "") 
		            	{
		            		$duration = "";
		            	}
		            	else
		            	{
		            		$duration = $get_duration->duration;
 		            	}


 		            	$expiry_date = date('Y-m-d',strtotime('+'.$duration,strtotime($input['start_date'][$i])));

		            	$member_sport_id =  MembersSports::insertGetId(array(
		            		"member_id" 	=> $member_id,
		            		"class_id"		=> $input['duration'][$i],
		            		"sport_id"		=> $input['sport'][$i],
		            		"current_belt_id"	=> $input['current_belt'][$i],
		            		"coach_id"		=> $input['coach'][$i],
		            		"duration"		=> $duration,
		            		"start_date"	=> $input['start_date'][$i],
		            		"expiry_date"	=> $expiry_date,
		            		"total_fee"		=> $input['fee'][$i],
		            		"created_at"	=> date("Y-m-d H:i:s"),
		            		"updated_at"	=> date("Y-m-d H:i:s"),
		            	));

		            	for ($j=0; $j < count($input['classes'][$i]) ; $j++) 
	          			{ 	
	          				$get_classes_days = ClassesDays::where('id',$input['classes'][$i][$j])->first();
			            	
			            	if ($get_classes_days == "") 
			            	{
			            		$day =	"";
			            		$days_sort = 0 ;
			            		$class_from_time = "";
			            		$class_to_time = "";
			            		$location = "";
			            		$fee = 0;
			            	}
			            	else
			            	{
			            		$day 			 = $get_classes_days->day;
			            		$days_sort 		 = $get_classes_days->days_sort;
			            		$class_from_time = $get_classes_days->from_time;
			            		$class_to_time 	 = $get_classes_days->to_time;
			            		$location 		 = $get_classes_days->location;
			            		$fee 			 = $get_classes_days->fee;
	 		            	}

	          				MembersSportsClasses::insert(array(
	          					"member_sports_id" => $member_sport_id,
	          					"classes_days_id"  => $input['classes'][$i][$j],
	          					"day"			   => $day,
	          					"days_sort"		   => $days_sort,
	          					"class_from_time"  => $class_from_time,
	          					"class_to_time"	   => $class_to_time,
	          					"location"		   => $location,
	          					"fee"		       => $fee,
	          					"created_at"	   => date("Y-m-d H:i:s"),
		            			"updated_at"	   => date("Y-m-d H:i:s"),
	          				));

	          				ClassesDays::where('id',$input['classes'][$i][$j])->update(array('members_count'=>$get_classes_days->members_count+1));
	          			}

		            }



                MembersRenewHistory::insert(array(
                              "member_id"         => $member_id,
                              "total_amount"        => $input['total_amount'],
                              "discount"    => $input['discount'],
                              "grand_total"   => $input['grand_total'],
                              "paid_amount"   => $input['paid_amount'],
                              "remaining_amount"  => $input['remaining_amount'],
                              "payment_date"  => $input['payment_date'],
                              "created_at"=> date('Y-m-d H:i:s'),
                              "updated_at"=> date('Y-m-d H:i:s'),
                ));
        			  return $this->PrintInvoice($member_id);
	          		
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

    public function EditMember(Request $request,$id)
    {	
        try 
        {
        	$member = Members::where('id',$id)->first();

        	if ($member == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

    		  $member_sport = MembersSports::where('member_id',$id)->get();
          	$sport = Sports::get();
            $belt = Belt::get();
          	

          	return view('member.edit',compact('member','member_sport','sport','belt'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function RenewPage(Request $request)
    { 
        try 
        {
          $member = Members::orderBy('created_at','asc')->get();

          return view('member.renew',compact('member'));


        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function RenewMemberForm(Request $request,$id)
    { 
        try 
        {
          $member = Members::where('id',$id)->first();

          if ($member == "") 
          {
              return redirect()->back()->with('failed',__('web.Invalid Data'));
          }

            $member_sport = MembersSports::where('member_id',$id)->get();
            $sport = Sports::get();
            $belt = Belt::get();
            
            $time_table = ClassesDays::get();
            
            return view('member.renew_form',compact('member','member_sport','sport','belt','time_table'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function UpdateMember(Request $request)
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
                                          'gender'    => 'required',
                                          'age'    => 'required',
                                          'profile_image'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          'id_card_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          'certificate_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          'passport_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          
                                          'guardian_id_card_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',

            //                               'sport.*' => 'required',
            //                               'coach.*' => 'required',
            //                               'start_date.*' => 'required',
            //                               'duration.*' => 'required',
            //                               'days.*' => 'required',
            //                               'classes.*' => 'required',
            //                               'fees.*' => 'required',


            //                               'payment_date' => 'required',
            //                               'total_amount' => 'required',
										  // 'grand_total' => 'required',
										  // 'paid_amount' => 'required',
										  // 'remaining_amount' => 'required',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

             $check_duplicate = Members::where('id','!=',$input['id'])
                                       ->where(function($query) use ($input){
                                            $query->orwhere('name',trim(strtoupper($input['name'])));
                                            // $query->orwhere('email',trim(strtolower($input['email'])));
                                            // $query->orwhere('phone',trim($input['phone']));
                                            $query->orwhere('national_id',trim($input['national_id']));
                                        })
                                        ->count();


            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }



            $get_mem = Members::where('id',$input['id'])->first();

			
			      //Profile Image Memeber
            $profile_image= $request->file('profile_image');
            if (empty($profile_image)) 
            {
                $profile_image_path = $get_mem->profile_image;
                
            }
            else
            {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$profile_image->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/profile');

                    if($profile_image->move($destinationPath, $imagename))
                    {
                            $profile_image_path =  'members/profile/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Profile Image Uploading");
                    }

            }


            //ID Card Image Member
            $id_card_image= $request->file('id_card_image');
            if (empty($id_card_image)) 
            {
                $id_card_image_path = $get_mem->id_card_image;
                
            }
            else
            {
                foreach ($id_card_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/id_card');

                    if($key->move($destinationPath, $imagename))
                    {
                            $id_card_image_path[] =  'members/id_card/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for ID Card Image Uploading");
                    }
                }

                if (empty($get_mem->id_card_image)) 
                {
                  $id_card_image_path = implode(",", $id_card_image_path);
                }
                else
                {
                  $id_card_image_path = $get_mem->id_card_image.",".implode(",", $id_card_image_path);
                }
                   
            }


            //Certificate Image Member
            $certificate_image= $request->file('certificate_image');
            if (empty($certificate_image)) 
            {
                $certificate_image_path = $get_mem->certificate_image;
                
            }
            else
            {
                foreach ($certificate_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/certificates');

                    if($key->move($destinationPath, $imagename))
                    {
                            $certificate_image_path[] =  'members/certificates/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Image Uploading");
                    }
                }
                if (empty($get_mem->certificate_image)) 
                {
                  $certificate_image_path = implode(",", $certificate_image_path);
                }
                else
                {
                  $certificate_image_path = $get_mem->certificate_image.",".implode(",", $certificate_image_path);
                }
                   
            }



           	//Passport Image Member
            $passport_image= $request->file('passport_image');
            if (empty($passport_image)) 
            {
                $passport_image_path = $get_mem->passport_image;
                
            }
            else
            {
                foreach ($passport_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/passport');

                    if($key->move($destinationPath, $imagename))
                    {
                            $passport_image_path[] =  'members/passport/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Passport Image Uploading");
                    }
                }
                
                if (empty($get_mem->passport_image)) 
                {
                  $passport_image_path = implode(",", $passport_image_path);
                }
                else
                {
                  $passport_image_path = $get_mem->passport_image.",".implode(",", $passport_image_path);
                }  

            }


            //Guardian ID Card Image Member
            $guardian_id_card_image= $request->file('guardian_id_card_image');
            if (empty($guardian_id_card_image)) 
            {
                $guardian_id_card_image_path = $get_mem->guardian_id_card_image;
                
            }
            else
            {
                foreach ($guardian_id_card_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/guardian_id_card');

                    if($key->move($destinationPath, $imagename))
                    {
                            $guardian_id_card_image_path[] =  'members/guardian_id_card/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Guardian ID Card Image Uploading");
                    }
                }

                if (empty($get_mem->guardian_id_card_image)) 
                {
                  $guardian_id_card_image_path = implode(",", $guardian_id_card_image_path);
                }
                else
                {
                  $guardian_id_card_image_path = $get_mem->guardian_id_card_image.",".implode(",", $guardian_id_card_image_path);
                }  
                   
            }
            
          		
          	$data = array(
				'name'			=>trim(strtoupper($input['name'])),
				'email'			=>trim(strtolower($input['email'])),
				'phone'			=>trim($input['phone']),
				'national_id'	=>trim($input['national_id']),
				'dob'			=>$input['dob'],
				'nationality'	=>$input['nationality'],
				'gender'		=>$input['gender'],
				'age'			=>$input['age'],

				'profile_image'	=>$profile_image_path,
				'id_card_image'		=>$id_card_image_path,
				'certificate_image'	=>$certificate_image_path,
				'passport_image'	=>$passport_image_path,
				'guardian_name'	 =>isset($input['guardian_name'])?$input['guardian_name']:"",
				'guardian_phone' =>isset($input['guardian_phone'])?$input['guardian_phone']:"",
				'guardian_id_card_image' =>$guardian_id_card_image_path,
				
				// 'total_amount' 		=> $input['total_amount'],
				// 'discount'			=> $input['discount'],
				// 'grand_total' 		=> $input['grand_total'],
				// 'paid_amount' 		=> $input['paid_amount'],
				// 'remaining_amount' 	=> $input['remaining_amount'],
				// 'payment_date'		=> $input['payment_date'],
          	);

          	$member_id = $input['id'];

          		if(Members::where('id',$member_id)->update($data))
          		{	
          			//First Delete All Records of member sports and member sports classes before renewal..
          			//it will help to add new records and update the member sports registration
                //$member_sports_ids=MembersSports::where('member_id',$member_id)->pluck('id');

                //before deleting classes... manage members_count 
      					// $classes_days_ids =MembersSportsClasses::whereIn('member_sports_id',$member_sports_ids)->pluck('classes_days_id');					          		 	 
      					// $get_class_capacity = ClassesDays::whereIn('id',$classes_days_ids)->get();

      					// foreach ($get_class_capacity as $class_capacity) 
      					// {
      					// 	ClassesDays::where('id',$class_capacity['id'])->update(array('members_count'=>$class_capacity['members_count']-1));
      					// }

             //      			MembersSportsClasses::whereIn('member_sports_id',$member_sports_ids)->delete();
             //      			MembersSports::where('member_id',$member_id)->delete();


             //      			for ($i=0; $i < count($input['sport']) ; $i++) 
        		   //          { 	
        		   //          	$get_duration = Classes::where('id',$input['duration'][$i])->first();
        		   //          	if ($get_duration == "") 
        		   //          	{
        		   //          		$duration = "";
        		   //          	}
        		   //          	else
        		   //          	{
        		   //          		$duration = $get_duration->duration;
         		  //           	}


         		  //           	$expiry_date = date('Y-m-d',strtotime('+'.$duration,strtotime($input['start_date'][$i])));

        		   //          	$member_sport_id =  MembersSports::insertGetId(array(
        		   //          		"member_id" 	=> $member_id,
        		   //          		"class_id"		=> $input['duration'][$i],
        		   //          		"sport_id"		=> $input['sport'][$i],
        		   //          		"current_belt_id"	=> $input['current_belt'][$i],
        		   //          		"coach_id"		=> $input['coach'][$i],
        		   //          		"duration"		=> $duration,
        		   //          		"start_date"	=> $input['start_date'][$i],
        		   //          		"expiry_date"	=> $expiry_date,
        		   //          		"total_fee"		=> $input['fee'][$i],
        		   //          		"created_at"	=> date("Y-m-d H:i:s"),
        		   //          		"updated_at"	=> date("Y-m-d H:i:s"),
        		   //          	));

        		   //          	for ($j=0; $j < count($input['classes'][$i]) ; $j++) 
        	    //       			{ 	
        	    //       				$get_classes_days = ClassesDays::where('id',$input['classes'][$i][$j])->first();
        			            	
        			  //           	if ($get_classes_days == "") 
        			  //           	{
        			  //           		$day =	"";
        			  //           		$days_sort = 0 ;
        			  //           		$class_from_time = "";
        			  //           		$class_to_time = "";
        			  //           		$location = "";
        			  //           		$fee = 0;
        			  //           	}
        			  //           	else
        			  //           	{
        			  //           		$day 			 = $get_classes_days->day;
        			  //           		$days_sort 		 = $get_classes_days->days_sort;
        			  //           		$class_from_time = $get_classes_days->from_time;
        			  //           		$class_to_time 	 = $get_classes_days->to_time;
        			  //           		$location 		 = $get_classes_days->location;
        			  //           		$fee 			 = $get_classes_days->fee;
        	 		 //            	}

        	    //       				MembersSportsClasses::insert(array(
        	    //       					"member_sports_id" => $member_sport_id,
        	    //       					"classes_days_id"  => $input['classes'][$i][$j],
        	    //       					"day"			   => $day,
        	    //       					"days_sort"		   => $days_sort,
        	    //       					"class_from_time"  => $class_from_time,
        	    //       					"class_to_time"	   => $class_to_time,
        	    //       					"location"		   => $location,
        	    //       					"fee"		       => $fee,
        	    //       					"created_at"	   => date("Y-m-d H:i:s"),
        		   //          			"updated_at"	   => date("Y-m-d H:i:s"),
        	    //       				));

        	    //       				ClassesDays::where('id',$input['classes'][$i][$j])->update(array('members_count'=>$get_classes_days->members_count+1));
        	    //       			}

        		   //          }



             //    			return $this->PrintInvoice($member_id);
        	          	  
              return redirect()->route('register.view-register')->with('success',__('web.Data Updated Successfully'));

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

    public function RenewMember(Request $request)
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
                                          'gender'    => 'required',
                                          'age'    => 'required',
                                          // 'profile_image'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          // 'id_card_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          // 'certificate_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          // 'passport_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',
                                          
                                          // 'guardian_id_card_image.*'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',

                                          'sport.*' => 'required',
                                          'coach.*' => 'required',
                                          'start_date.*' => 'required',
                                          'duration.*' => 'required',
                                          'days.*' => 'required',
                                          'classes.*' => 'required',
                                          'fees.*' => 'required',


                                          'payment_date' => 'required',
                                          'total_amount' => 'required',
                      'grand_total' => 'required',
                      'paid_amount' => 'required',
                      'remaining_amount' => 'required',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();
            
             $check_duplicate = Members::where('id','!=',$input['id'])
                                       ->where(function($query) use ($input){
                                            $query->orwhere('name',trim(strtoupper($input['name'])));
                                            // $query->orwhere('email',trim(strtolower($input['email'])));
                                            // $query->orwhere('phone',trim($input['phone']));
                                            $query->orwhere('national_id',trim($input['national_id']));
                                        })
                                        ->count();


            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }


            /*
            $get_mem = Members::where('id',$input['id'])->first();

      
            //Profile Image Memeber
            $profile_image= $request->file('profile_image');
            if (empty($profile_image)) 
            {
                $profile_image_path = $get_mem->profile_image;
                
            }
            else
            {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$profile_image->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/profile');

                    if($profile_image->move($destinationPath, $imagename))
                    {
                            $profile_image_path =  'members/profile/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Profile Image Uploading");
                    }

            }


            //ID Card Image Member
            $id_card_image= $request->file('id_card_image');
            if (empty($id_card_image)) 
            {
                $id_card_image_path = $get_mem->id_card_image;
                
            }
            else
            {
                foreach ($id_card_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/id_card');

                    if($key->move($destinationPath, $imagename))
                    {
                            $id_card_image_path[] =  'members/id_card/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for ID Card Image Uploading");
                    }
                }

                if (empty($get_mem->id_card_image)) 
                {
                  $id_card_image_path = implode(",", $id_card_image_path);
                }
                else
                {
                  $id_card_image_path = $get_mem->id_card_image.",".implode(",", $id_card_image_path);
                }
                   
            }


            //Certificate Image Member
            $certificate_image= $request->file('certificate_image');
            if (empty($certificate_image)) 
            {
                $certificate_image_path = $get_mem->certificate_image;
                
            }
            else
            {
                foreach ($certificate_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/certificates');

                    if($key->move($destinationPath, $imagename))
                    {
                            $certificate_image_path[] =  'members/certificates/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Image Uploading");
                    }
                }
                if (empty($get_mem->certificate_image)) 
                {
                  $certificate_image_path = implode(",", $certificate_image_path);
                }
                else
                {
                  $certificate_image_path = $get_mem->certificate_image.",".implode(",", $certificate_image_path);
                }
                   
            }



            //Passport Image Member
            $passport_image= $request->file('passport_image');
            if (empty($passport_image)) 
            {
                $passport_image_path = $get_mem->passport_image;
                
            }
            else
            {
                foreach ($passport_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/passport');

                    if($key->move($destinationPath, $imagename))
                    {
                            $passport_image_path[] =  'members/passport/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Passport Image Uploading");
                    }
                }
                
                if (empty($get_mem->passport_image)) 
                {
                  $passport_image_path = implode(",", $passport_image_path);
                }
                else
                {
                  $passport_image_path = $get_mem->passport_image.",".implode(",", $passport_image_path);
                }  

            }


            //Guardian ID Card Image Member
            $guardian_id_card_image= $request->file('guardian_id_card_image');
            if (empty($guardian_id_card_image)) 
            {
                $guardian_id_card_image_path = $get_mem->guardian_id_card_image;
                
            }
            else
            {
                foreach ($guardian_id_card_image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/guardian_id_card');

                    if($key->move($destinationPath, $imagename))
                    {
                            $guardian_id_card_image_path[] =  'members/guardian_id_card/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Guardian ID Card Image Uploading");
                    }
                }

                if (empty($get_mem->guardian_id_card_image)) 
                {
                  $guardian_id_card_image_path = implode(",", $guardian_id_card_image_path);
                }
                else
                {
                  $guardian_id_card_image_path = $get_mem->guardian_id_card_image.",".implode(",", $guardian_id_card_image_path);
                }  
                   
            }
            
        */
        


      $data = array(
        'name'      =>trim(strtoupper($input['name'])),
        'email'     =>trim(strtolower($input['email'])),
        'phone'     =>trim($input['phone']),
        'national_id' =>trim($input['national_id']),
        'dob'     =>$input['dob'],
        'nationality' =>$input['nationality'],
        'gender'    =>$input['gender'],
        'age'     =>$input['age'],

        // 'profile_image' =>$profile_image_path,
        // 'id_card_image'   =>$id_card_image_path,
        // 'certificate_image' =>$certificate_image_path,
        // 'passport_image'  =>$passport_image_path,
        // 'guardian_name'  =>isset($input['guardian_name'])?$input['guardian_name']:"",
        // 'guardian_phone' =>isset($input['guardian_phone'])?$input['guardian_phone']:"",
        // 'guardian_id_card_image' =>$guardian_id_card_image_path,
        
        'total_amount'    => $input['total_amount'],
        'discount'      => $input['discount'],
        'grand_total'     => $input['grand_total'],
        'paid_amount'     => $input['paid_amount'],
        'remaining_amount'  => $input['remaining_amount'],
        'payment_date'    => $input['payment_date'],
            );
        

          
          $member_id = $input['id'];
          
              if(Members::where('id',$member_id)->update($data))
              { 
                //First Delete All Records of member sports and member sports classes before renewal..
                //it will help to add new records and update the member sports registration
                $member_sports_ids=MembersSports::where('member_id',$member_id)->pluck('id');

                //before deleting classes... manage members_count 
                $classes_days_ids =MembersSportsClasses::whereIn('member_sports_id',$member_sports_ids)->pluck('classes_days_id');                           
                $get_class_capacity = ClassesDays::whereIn('id',$classes_days_ids)->get();

                foreach ($get_class_capacity as $class_capacity) 
                {
                  ClassesDays::where('id',$class_capacity['id'])->update(array('members_count'=>$class_capacity['members_count']-1));
                }

                MembersSportsClasses::whereIn('member_sports_id',$member_sports_ids)->delete();
                MembersSports::where('member_id',$member_id)->delete();


                for ($i=0; $i < count($input['sport']) ; $i++) 
                {   
                  $get_duration = Classes::where('id',$input['duration'][$i])->first();
                  if ($get_duration == "") 
                  {
                    $duration = "";
                  }
                  else
                  {
                    $duration = $get_duration->duration;
                  }


                  $expiry_date = date('Y-m-d',strtotime('+'.$duration,strtotime($input['start_date'][$i])));

                  $member_sport_id =  MembersSports::insertGetId(array(
                    "member_id"   => $member_id,
                    "class_id"    => $input['duration'][$i],
                    "sport_id"    => $input['sport'][$i],
                    "current_belt_id" => $input['current_belt'][$i],
                    "coach_id"    => $input['coach'][$i],
                    "duration"    => $duration,
                    "start_date"  => $input['start_date'][$i],
                    "expiry_date" => $expiry_date,
                    "total_fee"   => $input['fee'][$i],
                    "created_at"  => date("Y-m-d H:i:s"),
                    "updated_at"  => date("Y-m-d H:i:s"),
                  ));

                  for ($j=0; $j < count($input['classes'][$i]) ; $j++) 
                  {   
                    $get_classes_days = ClassesDays::where('id',$input['classes'][$i][$j])->first();
                    
                    if ($get_classes_days == "") 
                    {
                      $day =  "";
                      $days_sort = 0 ;
                      $class_from_time = "";
                      $class_to_time = "";
                      $location = "";
                      $fee = 0;
                    }
                    else
                    {
                      $day       = $get_classes_days->day;
                      $days_sort     = $get_classes_days->days_sort;
                      $class_from_time = $get_classes_days->from_time;
                      $class_to_time   = $get_classes_days->to_time;
                      $location      = $get_classes_days->location;
                      $fee       = $get_classes_days->fee;
                    }

                    MembersSportsClasses::insert(array(
                      "member_sports_id" => $member_sport_id,
                      "classes_days_id"  => $input['classes'][$i][$j],
                      "day"        => $day,
                      "days_sort"      => $days_sort,
                      "class_from_time"  => $class_from_time,
                      "class_to_time"    => $class_to_time,
                      "location"       => $location,
                      "fee"          => $fee,
                      "created_at"     => date("Y-m-d H:i:s"),
                      "updated_at"     => date("Y-m-d H:i:s"),
                    ));

                    ClassesDays::where('id',$input['classes'][$i][$j])->update(array('members_count'=>$get_classes_days->members_count+1));
                  }

                }

                $check_renew_history  = MembersRenewHistory::where('member_id',$member_id)->first();

                if ($check_renew_history == "") 
                {
                  $get_mem = Members::where('id',$member_id)->first();
                  MembersRenewHistory::insert(array(
                              "member_id"         => $member_id,
                              "total_amount"        => $get_mem->total_amount,
                              "discount"    => $get_mem->discount,
                              "grand_total"   => $get_mem->grand_total,
                              "paid_amount"   => $get_mem->paid_amount,
                              "remaining_amount"  => $get_mem->remaining_amount,
                              "payment_date"  => $get_mem->payment_date,
                              "created_at"=> $get_mem->created_at,
                              "updated_at"=> $get_mem->updated_at,
                  ));

                }
                else
                {
                   MembersRenewHistory::insert(array(
                              "member_id"         => $member_id,
                              "total_amount"        => $input['total_amount'],
                              "discount"    => $input['discount'],
                              "grand_total"   => $input['grand_total'],
                              "paid_amount"   => $input['paid_amount'],
                              "remaining_amount"  => $input['remaining_amount'],
                              "payment_date"  => $input['payment_date'],
                              "created_at"=> date('Y-m-d H:i:s'),
                              "updated_at"=> date('Y-m-d H:i:s'),
                  ));
                }
              
              return $this->PrintInvoice($member_id);
                
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

    public function DeleteMember(Request $request,$id)
    {	
        try 
        {
        	$get_member = Members::where('id',$id)->first();
          	if ($get_member == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}


          	$member_sports_ids=MembersSports::where('member_id',$id)->pluck('id');
  			//before deleting classes... manage members_count 
  			$classes_days_ids =MembersSportsClasses::whereIn('member_sports_id',$member_sports_ids)->pluck('classes_days_id');					          		 	 
  			$get_class_capacity = ClassesDays::whereIn('id',$classes_days_ids)->get();

					foreach ($get_class_capacity as $class_capacity) 
					{
						ClassesDays::where('id',$class_capacity['id'])->update(array('members_count'=>$class_capacity['members_count']-1));
					}

          	MembersSportsClasses::whereIn('member_sports_id',$member_sports_ids)->delete();
          	MembersSports::where('member_id',$id)->delete();



          	//Delete images file

          	//profile
          	$image_path = public_path('images/'.$get_member->profile_image);  
            if(File::exists($image_path)) 
            {
                File::delete($image_path);
            }

            //id card
            $id_card_img = explode(",", $get_member->id_card_image);
            foreach ($id_card_img as $key) 
            {
	          	$image_path = public_path('images/'.$key);  
    	        if(File::exists($image_path)) 
        	    {
            	    File::delete($image_path);
            	}
            }


            //certificate
          	$certificate_img = explode(",", $get_member->certificate_image);
            foreach ($certificate_img as $key) 
            {
	          	$image_path = public_path('images/'.$key);  
    	        if(File::exists($image_path)) 
        	    {
            	    File::delete($image_path);
            	}
            }

            //passport
          	$passport_img = explode(",", $get_member->passport_image);
            foreach ($passport_img as $key) 
            {
	          	$image_path = public_path('images/'.$key);  
    	        if(File::exists($image_path)) 
        	    {
            	    File::delete($image_path);
            	}
            }

            //guardian id card
          	$guardian_img = explode(",", $get_member->guardian_id_card_image);
            foreach ($guardian_img as $key) 
            {
	          	$image_path = public_path('images/'.$key);  
    	        if(File::exists($image_path)) 
        	    {
            	    File::delete($image_path);
            	}
            }


          	if (Members::where('id',$id)->delete()) 
          	{
	          	return redirect()->route('register.view-register')->with('success',__('web.Data Deleted Successfully'));
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



    public function ViewMember(Request $request)
    {	
        try 
        {	
        	$from_date = "";
        	$to_date = "";
        	$message = "";
        	$member = Members::orderBy('created_at','asc')->get();
          $sport  = Sports::get(); 
          $selected_sport = "";
          $selected_coach = "";
	        return view('member.view',compact('member','from_date','to_date','message','sport','selected_coach','selected_sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function ExpiredMembers(Request $request)
    { 
        try 
        { 
            $member_ids = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->get();

            $member = array();
            $count= 0;
            foreach ($member_ids as $key) 
            {
              $member[] = Members::where('id',$key['member_id'])->first()->toArray();
              $member[$count]['sport_name'] = $key->sport_name->name;
              $member[$count]['coach_name'] = $key->coach_name->name;
              $member[$count]['duration'] = $key['duration'];
              $member[$count]['start_date'] = $key['start_date'];
              $member[$count]['expiry_date'] = $key['expiry_date'];
              $member[$count]['total_fee'] = $key['total_fee'];

              $count++;
            }

            // dump($member);
            // exit();
            $from_date = "";
            $to_date = "";
            $sport  = Sports::get(); 
            $selected_sport = "";
            $selected_coach = "";
            return view('member.expired_member',compact('member','from_date','to_date','sport','selected_coach','selected_sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterExpiredMember(Request $request)
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
                  $member_ids = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->get();
                }
                else
                {
                  $member_ids   = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->where('coach_id',$input['coach'])->get(); 
                }
              }
              else
              {
                if (empty($input['coach'])) 
                {
                  $member_ids   = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->where('sport_id',$input['sport'])->get(); 
                }
                else
                {
                  $member_ids   = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->get(); 
                }
              }

              $message = "";
              $from_date = "";
              $to_date ="";
            }
            else
            {

              if (empty($input['sport'])) 
              {   
                if (empty($input['coach'])) 
                {
                  $member_ids = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->whereBetween('expiry_date',[$input['from_date'],$input['to_date']])->get();
                }
                else
                {
                  $member_ids   = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->where('coach_id',$input['coach'])->whereBetween('expiry_date',[$input['from_date'],$input['to_date']])->get(); 
                }
              }
              else
              {
                if (empty($input['coach'])) 
                {
                  $member_ids   = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->where('sport_id',$input['sport'])->whereBetween('expiry_date',[$input['from_date'],$input['to_date']])->get(); 
                }
                else
                {
                  $member_ids   = MembersSports::whereDate('expiry_date','<=',date('Y-m-d'))->orderBy('expiry_date','asc')->where('sport_id',$input['sport'])->whereBetween('expiry_date',[$input['from_date'],$input['to_date']])->where('coach_id',$input['coach'])->get(); 
                }
              }
              

              $message = __('web.Members Record From :from_date To :to_date',['from_date'=>$input['from_date'],'to_date'=>$input['to_date']]);
              $from_date = $input['from_date'];
              $to_date = $input['to_date'];
            }

           
            $member = array();
            $count= 0;
            foreach ($member_ids as $key) 
            {
              $member[] = Members::where('id',$key['member_id'])->first()->toArray();
              $member[$count]['sport_name'] = $key->sport_name->name;
              $member[$count]['coach_name'] = $key->coach_name->name;
              $member[$count]['duration'] = $key['duration'];
              $member[$count]['start_date'] = $key['start_date'];
              $member[$count]['expiry_date'] = $key['expiry_date'];
              $member[$count]['total_fee'] = $key['total_fee'];

              $count++;
            }

            $sport  = Sports::get(); 
            $selected_sport = $input['sport'];
            $selected_coach = $input['coach'];
            return view('member.expired_member',compact('member','from_date','to_date','sport','selected_coach','selected_sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function PendingPayments(Request $request)
    { 
        try 
        { 
           $member = Members::where('remaining_amount','>',0)->orderBy('created_at','asc')->get();


            $championship = Championship::where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
            $belt_exam = BeltExam::where('remaining_amount','>',0)->orderBy('created_at','asc')->get();


            $from_date = "";
            $to_date = "";
            $sport  = Sports::get(); 
            $selected_sport = "";
            $selected_coach = "";
            return view('member.pending_payment',compact('member','championship','belt_exam','from_date','to_date','sport','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterPendingPayments(Request $request)
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
                  $member = Members::where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                  $championship = Championship::where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                  $belt_exam = BeltExam::where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('coach_id',$input['coach'])->pluck('member_id'); 
                  $member = Members::whereIn('id',$member_ids)->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                  $championship = Championship::where('coach_id',$input['coach_id'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();

                  $belt_exam = BeltExam::where('coach_id',$input['coach_id'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();


                }
              }
              else
              {
                if (empty($input['coach'])) 
                {
                  $member_ids   = MembersSports::where('sport_id',$input['sport'])->pluck('member_id'); 
                  $member = Members::whereIn('id',$member_ids)->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                  $championship = Championship::where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();

                  $belt_exam = BeltExam::where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->pluck('member_id'); 
                  $member = Members::whereIn('id',$member_ids)->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                  $championship = Championship::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();

                  $belt_exam = BeltExam::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->get();
                }
              }

              $from_date = "";
              $to_date ="";
            }
            else
            {

              if (empty($input['sport'])) 
              {   
                if (empty($input['coach'])) 
                {
                  $member = Members::where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                  $championship = Championship::where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                  $belt_exam = BeltExam::where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('coach_id',$input['coach'])->pluck('member_id'); 
                  $member = Members::whereIn('id',$member_ids)->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                  $championship = Championship::where('coach_id',$input['coach_id'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();

                  $belt_exam = BeltExam::where('coach_id',$input['coach_id'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                }
              }
              else
              {
                if (empty($input['coach'])) 
                {
                  $member_ids   = MembersSports::where('sport_id',$input['sport'])->pluck('member_id'); 
                  $member = Members::whereIn('id',$member_ids)->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                  $championship = Championship::where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();

                  $belt_exam = BeltExam::where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->pluck('member_id'); 
                  $member = Members::whereIn('id',$member_ids)->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                  $championship = Championship::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();

                  $belt_exam = BeltExam::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->where('remaining_amount','>',0)->orderBy('created_at','asc')->whereBetween('created_at',[$input['from_date'],$input['to_date']])->get();
                }
              }
              

              $from_date = $input['from_date'];
              $to_date = $input['to_date'];
            }













            


            $sport  = Sports::get(); 
            $selected_sport = $input['sport'];
            $selected_coach = $input['coach'];
            return view('member.pending_payment',compact('member','championship','belt_exam','from_date','to_date','sport','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



    public function FilterMember(Request $request)
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
                  $member = Members::orderBy('created_at','asc')->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('coach_id',$input['coach'])->pluck('member_id'); 
                  $member  = Members::whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
                }
              }
              else
              {
                if (empty($input['coach'])) 
                {
                  $member_ids   = MembersSports::where('sport_id',$input['sport'])->pluck('member_id'); 
                  $member  = Members::whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id'); 
                  $member  = Members::whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
                }
              }

              $message = "";
              $from_date = "";
              $to_date ="";
            }
            else
            {

              if (empty($input['sport'])) 
              {   
                if (empty($input['coach'])) 
                {
                  $member = Members::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->orderBy('created_at','asc')->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('coach_id',$input['coach'])->pluck('member_id'); 
                  $member  = Members::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
                }
              }
              else
              {
                if (empty($input['coach'])) 
                {
                  $member_ids   = MembersSports::where('sport_id',$input['sport'])->pluck('member_id'); 
                  $member  = Members::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
                }
                else
                {
                  $member_ids   = MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id'); 
                  $member  = Members::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
                }
              }
              

              $message = __('web.Members Record From :from_date To :to_date',['from_date'=>$input['from_date'],'to_date'=>$input['to_date']]);
              $from_date = $input['from_date'];
              $to_date = $input['to_date'];
            }
            $sport  = Sports::get(); 
            $selected_sport = $input['sport'];
            $selected_coach = $input['coach'];
          	return view('member.view',compact('member','from_date','to_date','message','sport','selected_sport','selected_coach'));
          
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



    public function DetailsMember(Request $request,$id)
    {	
        try 
        {
        	$member = Members::where('id',$id)->first();

        	if ($member == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

    		$member_sport = MembersSports::where('member_id',$id)->get();
          
          	return view('member.details',compact('member','member_sport'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }



    public function PrintInvoice($member_id)
    {	
    	$member = Members::where('id',$member_id)->first();

    	if ($member == "")
    	{
    		return redirect()->route('register.add-register')->with('failed',__('web.Something went wrong. Try again later'));
    	}


    	$member_sport = MembersSports::where('member_id',$member_id)->get();

    	return view('member.print',compact('member','member_sport'));
    }


    public function PrintMemberDetails($member_id)
    { 
      $member = Members::where('id',$member_id)->first();

      if ($member == "")
      {
        return redirect()->route('register.add-register')->with('failed',__('web.Something went wrong. Try again later'));
      }


      $member_sport = MembersSports::where('member_id',$member_id)->get();

      ?>

          <h3><?php echo ucfirst($member->name); ?>'s Details Information</h3>
            <hr>
            <table style="width: 100%;" cellpadding="10" cellspacing="5">
                <tbody>
                    <tr>
                        <td><b>Name:</b> <?php echo $member->name; ?></td>
                        <td><b>Email:</b> <?php echo $member->email; ?></td>
                        <td><b>Phone:</b> <?php echo $member->phone; ?></td>
                        <td><b>Gender:</b> <?php echo $member->gender; ?></td>
                    </tr>
                     <tr>
                        <td><b>Nationality:</b> <?php echo $member->nationality; ?></td>
                        <td><b>National ID:</b> <?php echo $member->national_id; ?></td>
                        <td><b>Date of Birth:</b> <?php echo date('M d, Y',strtotime($member->dob)); ?></td>
                        <td><b>Age:</b> <?php echo $member->age; ?></td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <br>


      <table style="width: 100%;width: 100%; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px"  border="0" cellspacing="10" cellpadding="5">
                    <thead>
                        <tr style="background: slategray; font-size: 20px; font-weight: 600;">
                            <th>Sport</th>
                            <th>Coach </th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Sports Fee</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        <?php foreach($member_sport as $key): ?>
                        <tr>
                            <td><?php echo $key->sport_name->name ?></td>
                            <td><?php echo $key->coach_name->name ?></td>
                            <td><?php echo $key['duration'] ?></td>
                            <td><?php echo $key['start_date'] ?></td>
                            <td><?php echo $key['expiry_date'] ?></td>
                            <td><?php echo $key['total_fee'] ?></td>
                        </tr>
                        <tr style="background: #F5F5F5; font-size: 18px; font-weight: 600;">
                            <th colspan="6" align="center">Classes</th>
                        </tr>
                            <tr style="background: #F5F5F5;">
                                <th></th>
                                <th>Day</th>
                                <th>Class From Time</th>
                                <th>Class To Time</th>
                                <th>Location</th>
                                <th>Fee</th>

                            </tr>
                            <?php foreach($key->classes_list as $classes): ?>
                            <tr style="background: #F5F5F5;">
                                <td></td>
                                <td><?php echo $classes['day'] ?></td>
                                <td><?php echo date("h:i a",strtotime($classes['class_from_time'])) ?></td>
                                <td><?php echo date("h:i a",strtotime($classes['class_to_time'])) ?></td>
                                <td><?php echo $classes['location'] ?></td>
                                <td><?php echo $classes['fee'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr><td colspan="6" style="border-bottom: solid black 1px;"></td></tr>
                            <tr><td colspan="6"></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Total Amount:</td>
                            <td><?php echo $member->total_amount ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Discount:</td>
                            <td><?php echo $member->discount ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Grand Total:</td>
                            <td><?php echo $member->grand_total ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Total Paid</td>
                            <td><?php echo $member->paid_amount ?></td>
                        </tr>
                         <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Balance</td>
                            <td><?php echo $member->remaining_amount ?></td>
                        </tr>
                    </tfoot>
                </table>

                <?php
    }










    public function DeleteMemberFiles(Request $request)
    {
        try 
        { 

          $input = $request->all();
          $get_files = Members::where('id',$input['member_id'])->first();


          if ($get_files == "") 
          {
            return false;
          }

          if ($input['type'] == "profile-") 
          {
            $get_files->profile_image;

             $image_path = public_path('images/'.$input['file']);  
              if(File::exists($image_path)) 
              {
                  File::delete($image_path);
              }

            Members::where('id',$input['member_id'])->update(array('profile_image'=>""));

            return true;

          }
          elseif ($input['type'] == "id-card-") 
          {
            $files_arr = explode(",",$get_files->id_card_image);
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

            Members::where('id',$input['member_id'])->update(array('id_card_image'=>$files_arr));
            return true;
          }
          elseif ($input['type'] == "certificate-") 
          {
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

            Members::where('id',$input['member_id'])->update(array('certificate_image'=>$files_arr));
            return true;
          }
          elseif ($input['type'] == "passport-") 
          {
            $files_arr = explode(",",$get_files->passport_image);
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

            Members::where('id',$input['member_id'])->update(array('passport_image'=>$files_arr));
            return true;
          }
          elseif ($input['type'] == "g-id-card-") 
          {
            $files_arr = explode(",",$get_files->guardian_id_card_image);
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

            Members::where('id',$input['member_id'])->update(array('guardian_id_card_image'=>$files_arr));
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










}
