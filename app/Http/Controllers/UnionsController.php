<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


use App\Models\Unions;
use App\Models\UnionMembers;
use App\Models\Members;
use App\Models\MembersSports;
use App\Models\Sports;


use Auth;

class UnionsController extends Controller
{
    public function __construct(Request $request)
    {   
        // dd('herererer');
    }

    public function AddUnions(Request $request)
    {	
        try 
        {
          return view('unions.add-unions');
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function EditUnions(Request $request, $id)
    {   
        try 
        {
            $union = Unions::where('union_id',$id)->first();
            if ($union == "") 
            {
                return redirect()->route('unions.view-unions')->with('failed','Invalid Data');
            }

          return view('unions.edit-unions',compact('union'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function DeleteUnions(Request $request, $id)
    {   
        try 
        {
            if(Unions::where('union_id',$id)->delete())
            {
                return redirect()->route('unions.view-unions')->with('success','Union Deleted Successfully.');
            }
            else
            {
                return redirect()->back()->withInput()->with('failed','Something went wrong. Try again later.');
            }
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateUnions(Request $request)
    {   
        try 
        {   
            $input = $request->all();

            if(Unions::where('union_id',$input['id'])->update([
                        'name'       => $input['name'],
                        'fee'    => $input['fee'],
                    ]))
            {
                return redirect()->route('unions.view-unions')->with('success','Unions Updated Successfully.');
            }   
            else
            {
                return redirect()->back()->withInput()->with('failed','Something went wrong. Try again later.');
            }
            
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function ViewUnions(Request $request){
        try{
            $all_unions = Unions::get();
        	return view('unions.view-unions',compact('all_unions'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function SaveUnions(Request $request)
    {	
        try 
        {	
            $input = $request->all();
            // echo "<pre>"; print_r($input);
            // echo "</pre>"; exit;

            // if(User::where('email',trim(strtolower($input['email'])))->where('user_type',$input['type'])->count() > 0)
            // {
	        //   	return redirect()->back()->withInput()->with('failed','User Email Address Already Exist.');
            // }

            if(Unions::insert([
                        'name' 		 => $input['name'],
                        'fee' 	 => $input['fee'],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]))
            {
	          	return redirect()->route('unions.view-unions')->with('success','Unions Added Successfully.');
            }	
            else
            {
	          	return redirect()->back()->withInput()->with('failed','Something went wrong. Try again later.');
            }
	    	
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function ReceivePayment(Request $request)
    {
        try 
        {  
          $union = Unions::get();  
          $member = Members::get();
          return view('unions.add_receive_payment',compact('union','member'));
        

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveReceivePayment(Request $request)
    {
        try 
        {  
            $input = $request->all();

            $check_member = Members::where('id',$input['member_id'])->first();

            if ($check_member == "") 
            {
                return redirect()->back()->withInput()->with('failed',__('web.Invalid Data'));
            }

            $data = array(
                        'member_id'     => $input['member_id'],
                        'union_id'      => $input['union_name'],
                        'year'          => $input['year'],
                        'amount'        => $input['amount'],
                        'payment_date'  => $input['payment_date'],
                        'created_at'    => date("Y-m-d H:i:s"),
                        'updated_at'    => date("Y-m-d H:i:s"),
                    );

            $check_duplicate = UnionMembers::where('member_id',$input['member_id'])->where('union_id',$input['union_name'])->count();

            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }

            if (UnionMembers::insert($data)) 
            {
                    return redirect()->route('unions.receive-payment')->with('success',__('web.Data Added Successfully'));
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



    public function ViewAllPayment(Request $request)
    {
        try
        {   
            $union_payment = UnionMembers::get();
            $sport = Sports::get();
            $from_date = "";
            $to_date = "";
            $selected_sport = "";
            $selected_coach = "";
            return view('unions.view_payment',compact('union_payment','sport','from_date','to_date','selected_sport','selected_coach'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterAllPayment(Request $request)
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
                        $union_payment = UnionMembers::orderBy('created_at','asc')->get();
                    } 
                    else
                    {
                        $member_ids     = MembersSports::where('coach_id',$input['coach'])->pluck('member_id');
                        $union_payment = UnionMembers::wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
                    }    
                    
                }
                else
                {   
                    if (empty($input['coach'])) 
                    {
                        $member_ids     = MembersSports::where('sport_id',$input['sport'])->pluck('member_id');
                        $union_payment = UnionMembers::wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $member_ids     = MembersSports::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->pluck('member_id');
                        $union_payment = UnionMembers::wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
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
                        $union_payment = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $member_ids     = MembersSports::where('coach_id',$input['coach'])->pluck('member_id');
                        $union_payment = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->orderBy('created_at','asc')->wherein('member_id',$member_ids)->get();
                    }
                }
                else
                {   
                    if (empty($input['coach'])) 
                    {
                        $member_ids     = MembersSports::where('sport_id',$input['sport'])->pluck('member_id');
                        $union_payment = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();

                    }
                    else
                    {
                        $member_ids     = MembersSports::where('coach_id',$input['coach'])->where('sport_id',$input['sport'])->pluck('member_id');
                        $union_payment = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();

                    }
                }
                $from_date = $input['from_date'];
                $to_date = $input['to_date'];
            }







            $sport = Sports::get();
            $selected_sport = $input['sport'];
            $selected_coach = $input['coach'];
            return view('unions.view_payment',compact('union_payment','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function EditReceivePayment(Request $request,$id)
    {
        try 
        {  

            $union = Unions::get();  
            
            $union_member = UnionMembers::where('id',$id)->first();

            if ($union_member == "") 
            {
                return redirect()->back()->with('failed',__('web.Invalid Data'));
            }

            $member = Members::get();
            return view('unions.edit_receive_payment',compact('union','union_member','member'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function UpdateReceivePayment(Request $request)
    {
        try 
        {  
            $input = $request->all();

            $check_member = Members::where('id',$input['member_id'])->first();

            if ($check_member == "") 
            {
                return redirect()->back()->withInput()->with('failed',__('web.Invalid Data'));
            }

            $data = array(
                        'member_id'     => $input['member_id'],
                        'union_id'      => $input['union_name'],
                        'year'          => $input['year'],
                        'amount'        => $input['amount'],
                        'payment_date'  => $input['payment_date'],
                    );

            $check_duplicate = UnionMembers::where('member_id',$input['member_id'])->where('union_id',$input['union_name'])->where('id','!=',$input['id'])->count();

            if ($check_duplicate > 0) 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Data Already Exist'));
            }

            if (UnionMembers::where('id',$input['id'])->update($data)) 
            {
                    return redirect()->route('unions.view-payment')->with('success',__('web.Data Updated Successfully'));
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

    public function DeleteReceivePayment(Request $request,$id)
    {
        try 
        {  
            if(UnionMembers::where('id',$id)->delete())
            {
                return redirect()->route('unions.view-payment')->with('success',__('web.Data Deleted Successfully'));
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
}
