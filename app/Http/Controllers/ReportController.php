<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
use App\Models\BeltExam;
use App\Models\Championship;
use App\Models\Leave;
use App\Models\ExpenseType;
use App\Models\Expenses;
use App\Models\Unions;
use App\Models\UnionMembers;

use App\Models\Sales;
use App\Models\Items;

use DB;

class ReportController extends Controller
{
    public function __construct(Request $request)
    { 
    }


    public function ProfitLossReport(Request $request)
    {	
        try 
        {	
	       	$registrations 	= MembersRenewHistory::sum('grand_total');
	        $sales			= Sales::select([DB::raw('SUM(total_amount - discount) as total_sales')])->first();
            $sales          = floatval($sales->total_sales);
	        $unions 		= UnionMembers::sum('amount');
            $championships  = Championship::sum('total_amount');
	        $belt_exam      = BeltExam::sum('total_amount');
	        $purchases		= Items::select([DB::raw('SUM(purchase_price * qty) as total_purchases')])->first();
            $purchases      = floatval($purchases->total_purchases);
	        $salary			= EmployeeSalary::sum('total_salary');
	        $leave			= Leave::sum('paid_amount');

	        $expense_type = ExpenseType::select(['id','name'])->where('is_deleted',0)->where('parent_id',0)->get();

	        $expense_total = 0;
	        foreach ($expense_type as $key) 
	        {
	            $key['total_expense'] = Expenses::where('expense_type_id',$key['id'])->sum('amount');

	            $expense_total += $key['total_expense'];
	        }

       
	        $total_income	= number_format($registrations+$sales+$unions+$championships+$belt_exam,2);
	        $total_expense	= number_format($purchases+$salary+$leave+$expense_total,2);
	        $profit_loss	= number_format(($registrations+$sales+$unions+$championships+$belt_exam)-($purchases+$salary+$leave+$expense_total),2);

        	$sport = Sports::get();
        	$from_date = "";
        	$to_date = "";
        	$selected_sport = "";
        	$selected_coach = "";
        	return view('report.profit_loss',compact('sport','from_date','to_date','selected_sport','selected_coach','registrations','sales','unions','championships','belt_exam','purchases','salary','leave','expense_type','total_income','total_expense','profit_loss'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function FilterProfitLossReport(Request $request)
    {	
        try 
        {	

        	$input = $request->all();



        	if (empty($input['from_date']) || empty($input['to_date'])) 
        	{
				$sales         = Sales::select([DB::raw('SUM(total_amount - discount) as total_sales')])->first();
                $sales          = floatval($sales->total_sales);
                $purchases     = Items::select([DB::raw('SUM(purchase_price * qty) as total_purchases')])->first();
                $purchases      = floatval($purchases->total_purchases);
        		
                $expense_type = ExpenseType::select(['id','name'])->where('is_deleted',0)->where('parent_id',0)->get();

				$expense_total = 0;
				foreach ($expense_type as $key) 
				{
				    $key['total_expense'] = Expenses::where('expense_type_id',$key['id'])->sum('amount');
				    $expense_total += $key['total_expense'];
				}

        		if (empty($input['sport'])) 
        		{
        			if (empty($input['coach'])) 
        			{
        				$registrations 	= MembersRenewHistory::sum('grand_total');
				        $unions 		= UnionMembers::sum('amount');
				        $championships  = Championship::sum('total_amount');
                        $belt_exam      = BeltExam::sum('total_amount');

				        $salary			= EmployeeSalary::sum('total_salary');
				        $leave			= Leave::sum('paid_amount');

				        
        			}
        			else
        			{	
        				$member_ids 	= MembersSports::where('coach_id',$input['coach'])->pluck('member_id'); 
        				$registrations 	= MembersRenewHistory::whereIn('member_id',$member_ids)->sum('grand_total');
				        $unions 		= UnionMembers::whereIn('member_id',$member_ids)->sum('amount');
				        $championships  = 0;
                        $belt_exam      = 0;
				        $employee_id 	= Employee::where('id',$input['coach'])->first();
				        $salary			= EmployeeSalary::where('employee_id',$employee_id)->sum('total_salary');
				        $leave			= Leave::where('employee_id',$employee_id)->sum('paid_amount');
        			}
        		}
        		else
        		{
        			if (empty($input['coach'])) 
        			{
        				$member_ids 	= MembersSports::where('sport_id',$input['sport'])->pluck('member_id'); 
        				$registrations 	= MembersRenewHistory::whereIn('member_id',$member_ids)->sum('grand_total');
				        $unions 		= UnionMembers::whereIn('member_id',$member_ids)->sum('amount');
                        $championships  = Championship::where('sport_id',$input['sport'])->sum('total_amount');
				        $belt_exam      = BeltExam::where('sport_id',$input['sport'])->sum('total_amount');
				        $employee_ids 	= Employee::where('sport_id',$input['sport'])->pluck('id');
				        $salary			= EmployeeSalary::whereIn('employee_id',$employee_ids)->sum('total_salary');
				        $leave			= Leave::whereIn('employee_id',$employee_ids)->sum('paid_amount');
        			}
        			else
        			{
        				$member_ids 	= MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id'); 
        				$registrations 	= MembersRenewHistory::whereIn('member_id',$member_ids)->sum('grand_total');
				        $unions 		= UnionMembers::whereIn('member_id',$member_ids)->sum('amount');
                        $championships  = Championship::where('sport_id',$input['sport'])->sum('total_amount');
				        $belt_exam      = BeltExam::where('sport_id',$input['sport'])->sum('total_amount');
				        $employee_ids 	= Employee::where('sport_id',$input['sport'])->where('id',$input['coach'])->pluck('id');
				        $salary			= EmployeeSalary::whereIn('employee_id',$employee_ids)->sum('total_salary');
				        $leave			= Leave::whereIn('employee_id',$employee_ids)->sum('paid_amount');
        			}
        		}
        		$from_date = "";
        		$to_date = "";
        	}
        	else
        	{	

        		$sales         = Sales::select([DB::raw('SUM(total_amount - discount) as total_sales')])->whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->first();
                $sales          = floatval($sales->total_sales);
                $purchases     = Items::select([DB::raw('SUM(purchase_price * qty) as total_purchases')])->whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->first();
                $purchases      = floatval($purchases->total_purchases);
        		$expense_type = ExpenseType::select(['id','name'])->where('is_deleted',0)->where('parent_id',0)->get();

				$expense_total = 0;
				foreach ($expense_type as $key) 
				{
				    $key['total_expense'] = Expenses::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$key['id'])->sum('amount');
				    $expense_total += $key['total_expense'];
				}

        		if (empty($input['sport'])) 
        		{
        			if (empty($input['coach'])) 
        			{	
						$registrations 	= MembersRenewHistory::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->sum('grand_total');
				        $unions 		= UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->sum('amount');
                        $championships  = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->sum('total_amount');
				        $belt_exam  = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->sum('total_amount');
				        $salary			= EmployeeSalary::whereDate('salary_date','>=',$input['from_date'])->whereDate('salary_date','<=',$input['to_date'])->sum('total_salary');
				        $leave			= Leave::whereDate('leave_date','>=',$input['from_date'])->whereDate('leave_date','<=',$input['to_date'])->sum('paid_amount');        				
        			}
        			else
        			{
        				$member_ids 	= MembersSports::where('coach_id',$input['coach'])->pluck('member_id'); 
        				$registrations 	= MembersRenewHistory::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->whereIn('member_id',$member_ids)->sum('grand_total');
				        $unions 		= UnionMembers::whereIn('member_id',$member_ids)->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->sum('amount');
                        $championships  = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->sum('total_amount');
				        $belt_exam      = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->sum('total_amount');
				        $employee_id 	= Employee::where('id',$input['coach'])->first();
				        $salary			= EmployeeSalary::whereDate('salary_date','>=',$input['from_date'])->whereDate('salary_date','<=',$input['to_date'])->where('employee_id',$employee_id)->sum('total_salary');
				        $leave			= Leave::whereDate('leave_date','>=',$input['from_date'])->whereDate('leave_date','<=',$input['to_date'])->where('employee_id',$employee_id)->sum('paid_amount');	
        			}
        		}
        		else
        		{
        			if (empty($input['coach'])) 
        			{
        				$member_ids 	= MembersSports::where('sport_id',$input['sport'])->pluck('member_id'); 
        				$registrations 	= MembersRenewHistory::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->whereIn('member_id',$member_ids)->sum('grand_total');
				        $unions 		= UnionMembers::whereIn('member_id',$member_ids)->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->sum('amount');
                        $championships  = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->where('sport_id',$input['sport'])->sum('total_amount');
				        $belt_exam  = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->where('sport_id',$input['sport'])->sum('total_amount');
				        $employee_ids 	= Employee::where('sport_id',$input['sport'])->pluck('id');
				        $salary			= EmployeeSalary::whereDate('salary_date','>=',$input['from_date'])->whereDate('salary_date','<=',$input['to_date'])->whereIn('employee_id',$employee_ids)->sum('total_salary');
				        $leave			= Leave::whereDate('leave_date','>=',$input['from_date'])->whereDate('leave_date','<=',$input['to_date'])->whereIn('employee_id',$employee_ids)->sum('paid_amount');
        			}
        			else
        			{
        				$member_ids 	= MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id'); 
        				$registrations 	= MembersRenewHistory::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->whereIn('member_id',$member_ids)->sum('grand_total');
				        $unions 		= UnionMembers::whereIn('member_id',$member_ids)->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->sum('amount');
                        $championships  = Championship::whereDate('championship_date','>=',$input['from_date'])->whereDate('championship_date','<=',$input['to_date'])->where('sport_id',$input['sport'])->sum('total_amount');
				        $belt_exam     = BeltExam::whereDate('exam_date','>=',$input['from_date'])->whereDate('exam_date','<=',$input['to_date'])->where('sport_id',$input['sport'])->sum('total_amount');
				        $employee_ids 	= Employee::where('sport_id',$input['sport'])->where('id',$input['coach'])->pluck('id');
				        $salary			= EmployeeSalary::whereDate('salary_date','>=',$input['from_date'])->whereDate('salary_date','<=',$input['to_date'])->whereIn('employee_id',$employee_ids)->sum('total_salary');
				        $leave			= Leave::whereDate('leave_date','>=',$input['from_date'])->whereDate('leave_date','<=',$input['to_date'])->whereIn('employee_id',$employee_ids)->sum('paid_amount');
        			}
        		}
        		$from_date = $input['from_date'];
        		$to_date = $input['to_date'];
        	}





        	$sport = Sports::get();
        	$selected_sport = $input['sport'];
        	$selected_coach = $input['coach'];
        	$total_income	= number_format($registrations+$sales+$unions+$championships,2);
	        $total_expense	= number_format($purchases+$salary+$leave+$expense_total,2);
	        $profit_loss	= number_format(($registrations+$sales+$unions+$championships)-($purchases+$salary+$leave+$expense_total),2);
        	return view('report.profit_loss',compact('sport','from_date','to_date','selected_sport','selected_coach','registrations','sales','unions','championships','belt_exam','purchases','salary','leave','expense_type','total_income','total_expense','profit_loss'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function MembersRegistrationReport(Request $request)
    {	
        try 
        {	
        	$sport = Sports::get();
        	$member = Members::orderBy('created_at','asc')->get();
        	$from_date = "";
        	$to_date = "";
        	$selected_sport = "";
        	$selected_coach = "";

            foreach ($member as $key) 
            {   
                $get_totals = MembersRenewHistory::where('member_id',$key['id']);
                $key['grand_total']      = $get_totals->sum('grand_total');
                $key['paid_amount']      = $get_totals->sum('paid_amount');
                $key['remaining_amount'] = $get_totals->sum('remaining_amount');
            }

        	return view('report.member_registration',compact('member','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function FilterMembersRegistrationReport(Request $request)
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
        				$member_ids = MembersSports::where('coach_id',$input['coach'])->pluck('member_id');

        				$member = Members::whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
        			}
        		}
        		else
        		{
        			if (empty($input['coach'])) 
        			{
        				$member_ids = MembersSports::where('sport_id',$input['sport'])->pluck('member_id');
        				$member = Members::whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
        			}
        			else
        			{
        				$member_ids = MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id');

        				$member = Members::whereIn('id',$member_ids)->orderBy('created_at','asc')->get();
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

        				$member = Members::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->orderBy('payment_date','asc')->get();
        			}
        			else
        			{
        				$member_ids = MembersSports::where('coach_id',$input['coach'])->pluck('member_id');

        				$member = Members::whereIn('id',$member_ids)->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->orderBy('payment_date','asc')->get();
        			}
        		}
        		else
        		{
        			if (empty($input['coach'])) 
        			{
        				$member_ids = MembersSports::where('sport_id',$input['sport'])->pluck('member_id');
        				$member = Members::whereIn('id',$member_ids)->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->orderBy('payment_date','asc')->get();
        			}
        			else
        			{
        				$member_ids = MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id');

        				$member = Members::whereIn('id',$member_ids)->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->orderBy('payment_date','asc')->get();
        			}
        		}
        		$from_date = $input['from_date'];
        		$to_date = $input['to_date'];
        	}


            foreach ($member as $key) 
            {   
                $get_totals = MembersRenewHistory::where('member_id',$key['id']);
                $key['grand_total']      = $get_totals->sum('grand_total');
                $key['paid_amount']      = $get_totals->sum('paid_amount');
                $key['remaining_amount'] = $get_totals->sum('remaining_amount');
            }

        	$sport = Sports::get();
        	
        	$selected_sport = $input['sport'];
        	$selected_coach = $input['coach'];

        	return view('report.member_registration',compact('member','sport','from_date','to_date','selected_sport','selected_coach'));
        	
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }








	public function UnionRegistrationReport(Request $request)
    {	
        try 
        {   

            $sport = Sports::get();
            $union_members = UnionMembers::orderBy('created_at','asc')->get();
            $from_date = "";
            $to_date = "";
            $selected_sport = "";
            $selected_coach = "";
            return view('report.union_registration',compact('union_members','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function FilterUnionRegistrationReport(Request $request)
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
                        $union_members = UnionMembers::orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $member_ids     = MembersSports::where('coach_id',$input['coach'])->pluck('member_id');
                        $union_members = UnionMembers::wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
                    }                    
                }
                else
                {   
                    if (empty($input['coach'])) 
                    {
                        $member_ids     = MembersSports::where('sport_id',$input['sport'])->pluck('member_id');
                        $union_members = UnionMembers::wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $member_ids     = MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id');
                        $union_members = UnionMembers::wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
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
                        $union_members = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $member_ids     = MembersSports::where('coach_id',$input['coach'])->pluck('member_id');
                        $union_members = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
                    }
                }
                else
                {   
                    if (empty($input['coach'])) 
                    {
                        $member_ids     = MembersSports::where('sport_id',$input['sport'])->pluck('member_id');
                        $union_members = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $member_ids     = MembersSports::where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->pluck('member_id');
                        $union_members = UnionMembers::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->wherein('member_id',$member_ids)->orderBy('created_at','asc')->get();
                    }
                }
                $from_date = $input['from_date'];
                $to_date = $input['to_date'];
            }







            $sport = Sports::get();
            $selected_sport = $input['sport'];
            $selected_coach = $input['coach'];
            return view('report.union_registration',compact('union_members','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function ChampionshipRegistrationReport(Request $request)
    {	
        try 
        {
        	$sport = Sports::get();
        	$championship = Championship::orderBy('created_at','asc')->get();
        	$from_date = "";
        	$to_date = "";
            $selected_sport = "";
        	$selected_coach = "";
       		return view('report.championship_registration',compact('championship','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterChampionshipRegistrationReport(Request $request)
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
        				$championship = Championship::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $championship = Championship::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->orderBy('created_at','asc')->where('coach_id',$input['coach'])->get();

                    }
        		}
        		else
        		{
                    if (empty($input['coach']))
                    {
        				$championship = Championship::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->where('sport_id',$input['sport'])->orderBy('created_at','asc')->get();
                    }
                    else
                    {
                        $championship = Championship::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->where('sport_id',$input['sport'])->where('coach_id',$input['coach'])->orderBy('created_at','asc')->get();

                    }
        		}
        		$from_date = $input['from_date'];
        		$to_date = $input['to_date'];
        	}







        	$sport = Sports::get();
            $selected_sport = $input['sport'];
        	$selected_coach = $input['coach'];
       		return view('report.championship_registration',compact('championship','sport','from_date','to_date','selected_sport','selected_coach'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    public function FilterSalesReport(Request $request)
    {   
        try 
        {

            $input = $request->all();

            if (empty($input['from_date']) || empty($input['to_date'])) 
            {
                 
                $sale = Sales::orderBy('created_at','asc')->get();
                    
               
                $from_date = "";
                $to_date = "";
            }
            else
            {
               
                $sale = Sales::whereDate('created_at','>=',$input['from_date'])->whereDate('created_at','<=',$input['to_date'])->orderBy('created_at','asc')->get();
               
                $from_date = $input['from_date'];
                $to_date = $input['to_date'];
            }







            return view('report.sales',compact('sale','from_date','to_date'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


}
