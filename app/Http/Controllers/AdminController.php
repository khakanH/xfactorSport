<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ResetPassword;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Members;
use App\Models\MembersSports;
use App\Models\MembersRenewHistory;
use App\Models\MembersSportsClasses;
use App\Models\Sports;
use App\Models\Classes;
use App\Models\ClassesDays;
use App\Models\Belt;
use App\Models\Championship;
use App\Models\Leave;
use App\Models\ExpenseType;
use App\Models\Expenses;


use Auth;

use App;
use Mail;

class AdminController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function ChangeLanguage(Request $request,$lang)
    {   
        try 
        {
            App::setLocale($lang);
            session()->put('locale', $lang);

            return redirect()->route('dashboard');
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }
    

    public function Login(Request $request)
    {	
        try 
        {
            $validator = \Validator::make($request->all(), 
                                        ['email' => 'required|email',
                                         'password'    => 'required',
                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            
            $input = $request->all();    

          

            $user_data =array('email'=>$input['email'],'password'=>$input['password']);

            if (Auth::attempt($user_data)) 
            {   
                if (empty(session('last_url'))) 
                {   




                	return redirect()->route('dashboard');
                }
                else
                {   
                    
                    
                    return redirect()->to(session('last_url'));
                }
            }
            else
            {
            	return redirect()->back()->withInput()->with('failed','Invalid Login Credentials');
            }
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }


    public function Dashboard(Request $request)
    {   
        try 
        {   
            $registrations  = MembersRenewHistory::sum('grand_total');
            $members_count  = Members::count();
            $sales          = 0;
            $unions         = 0;
            $championships  = Championship::sum('total_amount');
            $purchases      = 0;
            $salary         = EmployeeSalary::sum('total_salary');
            $leave          = Leave::sum('paid_amount');

            $expense_type = ExpenseType::select(['id','name'])->where('is_deleted',0)->where('parent_id',0)->get();

            $expense_total = 0;
            foreach ($expense_type as $key) 
            {
                $key['total_expense'] = Expenses::where('expense_type_id',$key['id'])->sum('amount');

                $expense_total += $key['total_expense'];
            }

       
            $total_income   = number_format($registrations+$sales+$unions+$championships,2);
            $total_expense  = number_format($purchases+$salary+$leave+$expense_total,2);
            $salary         = number_format($salary,2);


            $time_table = ClassesDays::get();

            $from_date = "";
            $to_date = "";

            return view('dashboard',compact('total_income','total_expense','salary','members_count','time_table','from_date','to_date'));

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }



    public function DashboardFilter(Request $request)
    {   
        try 
        {   $input = $request->all();



            if (empty($input['from_date']) || empty($input['to_date'])) 
            {
                $registrations  = MembersRenewHistory::sum('grand_total');
                $members_count  = Members::count();
                $sales          = 0;
                $unions         = 0;
                $championships  = Championship::sum('total_amount');
                $purchases      = 0;
                $salary         = EmployeeSalary::sum('total_salary');
                $leave          = Leave::sum('paid_amount');

                $expense_type = ExpenseType::select(['id','name'])->where('is_deleted',0)->where('parent_id',0)->get();

                $expense_total = 0;
                foreach ($expense_type as $key) 
                {
                    $key['total_expense'] = Expenses::where('expense_type_id',$key['id'])->sum('amount');

                    $expense_total += $key['total_expense'];
                }

           
               

                $from_date = "";
                $to_date = "";
            }
            else
            {   


                $sales          = 0;
                $purchases      = 0;
                $expense_type = ExpenseType::select(['id','name'])->where('is_deleted',0)->where('parent_id',0)->get();

                $expense_total = 0;
                foreach ($expense_type as $key) 
                {
                    $key['total_expense'] = Expenses::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$key['id'])->sum('amount');
                    $expense_total += $key['total_expense'];
                }

                $registrations  = MembersRenewHistory::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->sum('grand_total');
                $members_count  = Members::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->count();
                $unions         = 0;
                $championships  = Championship::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->sum('total_amount');
                $salary         = EmployeeSalary::whereDate('salary_date','>=',$input['from_date'])->whereDate('salary_date','<=',$input['to_date'])->sum('total_salary');
                $leave          = Leave::whereDate('leave_date','>=',$input['from_date'])->whereDate('leave_date','<=',$input['to_date'])->sum('paid_amount');
                
                $from_date = $input['from_date'];
                $to_date = $input['to_date'];
            }

            $total_income   = number_format($registrations+$sales+$unions+$championships,2);
            $total_expense  = number_format($purchases+$salary+$leave+$expense_total,2);
            $salary         = number_format($salary,2);
            $time_table = ClassesDays::get();


            return view('dashboard',compact('total_income','total_expense','salary','members_count','time_table','from_date','to_date'));

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }



    public function ForgotPassword(Request $request,$email)
    {   
        try 
        {
            $check_user = User::where('email',$email)->first();

            if ($check_user == "") 
            {
                return redirect()->route('index')->with('failed','Sorry, provided email address doesn\'t associated with any account.');
            }

            $generate_token         = Str::random(32);
            $token_expiry_time      = time()+172800;

            $data= array('user_id'=>$check_user->id,'token'=>$generate_token,'expiry_time'=>$token_expiry_time,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'));

            if(ResetPassword::insert($data))
            {
                $reset_url = config('app.url')."reset-password/".$generate_token;

                $data = array('reset_url'=>$reset_url,'expire'=>date("Y-m-d H:i:s",$token_expiry_time));
                Mail::send('reset_password_mail', $data, function($message) use ($email){
                                                    $message->to($email,'')
                                                            ->subject('X-Factor Reset Password Request');
                                                  });


                return redirect()->route('index')->with('success','Reset Password Link has been send to your Email Address ('.$email.')');
            }
            else
            {
                return redirect()->route('index')->with('failed','Something went wrong. Try again later');
            }

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }



    public function ResetPassword(Request $request,$token)
    {   
        try 
        {
            $check_token = ResetPassword::where('token',$token)->first();

            if ($check_token == "") 
            {
                return redirect()->route('index')->with('failed','Bad Request | Error:400');
            }

            if (time() > $check_token->expiry_time) 
            {
                return redirect()->route('index')->with('failed','Link Expire | Error:401');
            }


            return view('reset_password',compact('check_token'));

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }


    public function ChangePassword(Request $request)
    {   
        try 
        {
            $input = $request->all();
            $data = array('password'=>Hash::make($input['password']));

            if(User::where('id',$input['user_id'])->update($data))
            {   
                ResetPassword::where('user_id',$input['user_id'])->delete();
                return redirect()->route('index')->with('success','Password Reset Successfully');
            }
            else
            {
                return redirect()->route('index')->withInput()->with('failed','Something went wrong. Try again later');
            }

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }

    public function Logout(Request $request)
    {	
        try 
        {
            Auth::logout();
            $request->session()->flush(); 

           return redirect()->route('index');
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }


   

}
