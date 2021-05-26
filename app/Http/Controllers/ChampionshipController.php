<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Members;
use App\Models\Sports;
use App\Models\Championship;

use File;

class ChampionshipController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function AddChampionship(Request $request)
    {	
        try 
        {
          $sport = Sports::get();
          $member = Members::get();
          return view('championship.add',compact('sport','member'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    
	public function MemberNamesList(Request $request,$name)
    {	
        try 
        {	
        	$names = Members::where('name','like','%'.$name.'%')->orderBy('name','asc')->get();

          if (count($names) == 0) 
          {
            return false;
          }

        	foreach ($names as $key) 
        	{  
        		?>
        			<button type="button" class="btn btn-secondary" style="width: 80%; margin: 4px;" onclick='SelectMember("<?php echo $key['id'] ?>","<?php echo $key['name'] ?>")'><?php echo $key['name'] ?></button>
        		<?php
        	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function SaveChampionship(Request $request)
    {	
        try 
        { 
            $validator = \Validator::make($request->all(), 
                                        [
                                          'championship_name' => 'required',
                                          'name' => 'required',
                                          'sport' => 'required',
                                          'coach' => 'required',
                                          'date'    => 'required',
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
                   
                    $destinationPath = public_path('/images/members/championship');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'members/championship/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Uploading");
                    }
                }
                   
            }


            
          		
          		$data = array(
                        'championship_name' => $input['championship_name'],
	                    	'member_id' => $input['member_id'],
                        'sport_id' => $input['sport'],
	                    	'coach_id' => $input['coach'],
	                    	'championship_date' => $input['date'],
	                    	'total_amount' => $input['total_amount'],
	                    	'paid_amount' => $input['paid_amount'],
	                    	'remaining_amount' => $input['remaining_amount'],
	                    	'result' => $input['result'],
	                    	'documents'=>empty($path)?"":implode(",", $path),
	          				'created_at' => date('Y-m-d H:i:s'),
	          				'updated_at' => date('Y-m-d H:i:s'),
	          			);

          		if(Championship::insert($data))
          		{
	          		return redirect()->route('register.add-championship')->with('success',__('web.Data Added Successfully'));
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

    public function EditChampionship(Request $request,$id)
    {	
        try 
        {
        	$championship = Championship::where('id',$id)->first();

        	if ($championship == "") 
        	{
	          	return redirect()->back()->with('failed',__('web.Invalid Data'));
        	}

          	$sport = Sports::get();
            $member = Members::get();
          	return view('championship.edit',compact('championship','sport','member'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateChampionship(Request $request)
    {	
        try 
        {

            $validator = \Validator::make($request->all(), 
                                        [
                                          'championship_name' => 'required',
                                          'name' => 'required',
                                          'sport' => 'required',
                                          'coach' => 'required',
                                          'date'    => 'required',
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


            $get_championship = Championship::where('id',$input['id'])->first();

          	
            $image= $request->file('document');
            if (empty($image)) 
            {
                $docs_img = $get_championship->documents;
                
            }
            else
            {
                foreach ($image as $key) 
                {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/members/championship');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'members/championship/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Certificates Uploading");
                    }
                }
                 

                if (empty($get_championship->documents)) 
                {
	                $docs_img = implode(",", $path);
                }
                else
                {
	                $docs_img = $get_championship->documents.",".implode(",", $path);
                }

            }


            
          		
          		$data = array(
                        'championship_name' => $input['championship_name'],
	                    	'member_id' => $input['member_id'],
	                    	'sport_id' => $input['sport'],
                        'coach_id' => $input['coach'],
	                    	'championship_date' => $input['date'],
	                    	'total_amount' => $input['total_amount'],
	                    	'paid_amount' => $input['paid_amount'],
	                    	'remaining_amount' => $input['remaining_amount'],
	                    	'result' => $input['result'],
	                    	'documents'=>$docs_img,
	          			);

          		if(Championship::where('id',$input['id'])->update($data))
          		{
	          		return redirect()->route('register.view-championship')->with('success',__('web.Data Updated Successfully'));
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

    


    public function ViewChampionship(Request $request)
    {	
        try 
        {
        	$championship = Championship::orderBy('created_at','asc')->get();
          $sport = Sports::get();
          $from_date = "";
          $to_date = "";
          $selected_sport = "";
          $selected_coach = "";
       		return view('championship.view',compact('championship','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterChampionship(Request $request)
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
                $championship = Championship::orderBy('created_at','asc')->get();
              }
              else
              {
                $championship = Championship::where('coach_id',$input['coach'])->orderBy('created_at','asc')->get();
              }              
              
            }
            else
            { 
              if (empty($input['coach'])) 
              {
                $championship = Championship::where('sport_id',$input['sport'])->orderBy('created_at','asc')->get();
              }
              else
              {
                $championship = Championship::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->orderBy('created_at','asc')->get();
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
                $championship = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->orderBy('created_at','asc')->get();
              }
              else
              {
                $championship = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->orderBy('created_at','asc')->where('coach_id',$input['coach'])->get();
              }

                
            }
            else
            { 
              if (empty($input['coach'])) 
              {
                $championship = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->where('sport_id',$input['sport'])->orderBy('created_at','asc')->get();
              }
              else
              {
                $championship = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->orderBy('created_at','asc')->get();
              }
                
            }
            $from_date = $input['from_date'];
            $to_date = $input['to_date'];
          }







          $sport = Sports::get();
          $selected_sport = $input['sport'];
          $selected_coach = $input['coach'];
          return view('championship.view',compact('championship','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

	public function DeleteChampionship(Request $request,$id)
    {	
        try 
        {	

        	 //delete championship file docs first
            $get_championship = Championship::where('id',$id)->first();
            $images = explode(",", $get_championship->documents);
            foreach ($images as $key) 
            {
	          	$image_path = public_path('images/'.$key);  
    	        if(File::exists($image_path)) 
        	    {
            	    File::delete($image_path);
            	}
            }


        	if(Championship::where('id',$id)->delete())
        	{
	          	return redirect()->route('register.view-championship')->with('success',__('web.Data Deleted Successfully'));
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

    

    public function DetailsChampionship(Request $request,$id)
    { 
        try 
        {
          $get_championship = Championship::where('id',$id)->first();

          if ($get_championship == "")
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
                <td style="text-align: center;" colspan="3"><b><?php echo __('web.Championship Name')?>:</b> <?php echo $get_championship->championship_name; ?></td>
              </tr>
              <tr>
                <td><b><?php echo __('web.Member Name')?>:</b> <?php echo $get_championship->member_info->name; ?></td>
                <td><b><?php echo __('web.Email')?>:</b> <?php echo $get_championship->member_info->email; ?></td>
                <td><b><?php echo __('web.Phone')?>:</b> <?php echo $get_championship->member_info->phone; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Age')?>:</b> <?php echo $get_championship->member_info->age; ?></td>
                <td><b><?php echo __('web.Sports')?>:</b> <?php echo $get_championship->sport_name->name; ?></td>
                <td><b><?php echo __('web.Championship Date')?>:</b> <?php echo $get_championship->championship_date; ?></td>
              </tr>

              <tr>
                <td><b><?php echo __('web.Total Amount')?>:</b> <?php echo $get_championship->total_amount; ?></td>
                <td><b><?php echo __('web.Paid Amount')?>:</b> <?php echo $get_championship->paid_amount; ?></td>
                <td><b><?php echo __('web.Remaining Amount')?>:</b> <?php echo $get_championship->remaining_amount; ?></td>
              </tr>

               <tr>
                <td colspan="3"><b><?php echo __('web.Documents')?>:</b>
                  <ul>
                    <?php 
                    $docs = explode(",", $get_championship->documents); 
                    foreach ($docs as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><?php echo $key; ?></a></li>
                    <?php endforeach; ?>
                  </ul></td>
              </tr>

              
              
              <tr>
                <td colspan="3"><b><?php echo __('web.Result')?>:</b> <?php echo $get_championship->result; ?></td>
                
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
