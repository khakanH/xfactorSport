<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseType;
use App\Models\Expenses;

use File;

class ExpenseController extends Controller
{
	public function __construct(Request $request)
    {   

    }


    public function ExpenseSetting(Request $request)
    {   
        try 
        {
           
			$expense_type = ExpenseType::where('is_deleted',0)->where('parent_id',0)->get();

			$beneficiary = array();
			foreach ($expense_type as $key) 
			{
				$beneficiary[] = ExpenseType::where('is_deleted',0)->where('parent_id',$key['id'])->get();

			}

			return view('expenses.expense_type',compact('expense_type','beneficiary'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateExpenseType(Request $request) 
	{	
		try
		{
			$input = $request->all();

			if (empty($input['expense_type_id'])) 
			{
				$data = array(
							"name" => $input['expense_type_name'],
							"parent_id"	=> 0,
							"notification_period" => $input['notification_period'],
							"is_deleted"	=> 0,
							"created_at"	=> date("Y-m-d H:i:s"),
							"updated_at"    => date("Y-m-d H:i:s"),
					);	

				if (ExpenseType::insert($data)) 
				{
					return redirect()->route('expenses.expense-setting')->with('success',__('web.Data Added Successfully'));
				}
				else
				{
					return redirect()->route('expenses.expense-setting')->with('failed',__('web.Something went wrong. Try again later'));
				}
			}
			else
			{
				$data = array(
							"name" => $input['expense_type_name'],
							"notification_period" => $input['notification_period'],
					);	



				if (ExpenseType::where('id',$input['expense_type_id'])->update($data)) 
				{
					return redirect()->route('expenses.expense-setting')->with('success',__('web.Data Updated Successfully'));
				}
				else
				{
					return redirect()->route('expenses.expense-setting')->with('failed',__('web.Something went wrong. Try again later'));
				}
			}
			

		} 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
	}

	public function DeleteExpenseType(Request $request,$id) 
	{
		try 
		{
			if (ExpenseType::where('id',$id)->update(array('is_deleted'=>1))) 
			{
				return redirect()->route('expenses.expense-setting')->with('success',__('web.Data Deleted Successfully'));
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

	public function SaveBeneficiary(Request $request) 
	{	
		try 
		{
			$input = $request->all();

			if (empty($input['id'])) 
			{
				$data = array(
								'name'		 => $input['name'],
								'parent_id'	 => $input['expense_id'],
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							 );		

				if (ExpenseType::insert($data)) 
				{
					return redirect()->route('expenses.expense-setting')->with('success',__('web.Data Added Successfully'));
				}
				else
				{
					return redirect()->route('expenses.expense-setting')->with('failed',__('web.Something went wrong. Try again later'));
				}
			}
			else
			{
				$data = array(
								'name'		 => $input['name'],
							 );		

				if (ExpenseType::where('id',$input['id'])->update($data)) 
				{
					return redirect()->route('expenses.expense-setting')->with('success',__('web.Data Updated Successfully'));
				}
				else
				{
					return redirect()->route('expenses.expense-setting')->with('failed',__('web.Something went wrong. Try again later'));
				}
			}		
		} 
		catch (Exception $e) 
		{
            return response()->json($e,500);
		}
	}
    
    public function DeleteBeneficiary(Request $request,$id) 
	{
		try 
		{
			if (ExpenseType::where('id',$id)->update(array('is_deleted'=>1))) 
			{
				return redirect()->route('expenses.expense-setting')->with('success',__('web.Data Deleted Successfully'));
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







	public function AddExpense(Request $request) 
	{
		try 
		{
			$expense_type = ExpenseType::where('is_deleted',0)->where('parent_id',0)->get();

			
			return view('expenses.add',compact('expense_type'));
			
		} 
		catch (Exception $e) 
		{
            return response()->json($e,500);
		}

	}


	public function GetBeneficiaryNames(Request $request,$id,$selected)
	{	

		try 
		{
			$data =  ExpenseType::where('is_deleted',0)->where('parent_id',$id)->get();

			if (count($data) == 0) 
			{
				?>
					 <option value=""><?php echo __('Select') ?></option>

				<?php
				return;
			}
			else
			{	
				?>
					 <option value=""><?php echo __('Select') ?></option>
				<?php
				foreach ($data as $key => $value) 
				{
					?>
					 <option 
					 	<?php if ($selected == 0): ?>
						 	<?php if ($key == 0): ?>
						 			selected
					 		<?php endif; ?> 
					 	<?php else:  ?>
					 		<?php if ($selected == $value['id']): ?>
					 			selected
					 		<?php endif; ?> 

					 	<?php endif; ?> 
					 value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
					<?php
				}
			}	
		} 
		catch (Exception $e) 
		{
            return response()->json($e,500);
		}
	}


	public function SaveExpense(Request $request) 
	{
		try 
		{
			$input = $request->all();

			$files= $request->file('attach_file');
			if (empty($files)) 
			{
				$path = array();
			}
			else
			{
				 foreach ($files as $key) 
	                {
	                   $imagename =  "expense_".rand(11111,99999).".".$key->getClientOriginalExtension();
	                   
	                    $destinationPath = public_path('/images/expenses');

	                    if($key->move($destinationPath, $imagename))
	                    {
	                            $path[] =  'expenses/'.$imagename;
	                    }
	                    else
	                    {
	                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Files Uploading");
	                    }
	                }
			}




			$data = array(
							"expense_type_id"	=> $input['type'],	
							"beneficiary_id"	=> isset($input['beneficiary'])?$input['beneficiary']:0,	
							"amount"			=> $input['amount'],
							"payment_date"		=> $input['payment_date'],
							"next_payment_date"	=> isset($input['next_payment_date'])?$input['next_payment_date']:'0000-00-00 00:00:00',
							"attach_files"		=> implode(",", $path),
							"details"			=> $input['details'],
							"account_period"	=> $input['account_period'],
							"created_at"		=> date("Y-m-d H:i:s"),
							"updated_at"		=> date("Y-m-d H:i:s"),
					);

			if (Expenses::insert($data)) 
			{
				return redirect()->route('expenses.add-expenses')->with('success',__('web.Data Added Successfully'));
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


	public function EditExpense(Request $request,$id) 
	{
		try 
		{
			$expense_type = ExpenseType::where('is_deleted',0)->where('parent_id',0)->get();

			
			$expense = Expenses::where('id',$id)->first();
			return view('expenses.edit',compact('expense_type','expense'));
		} 
		catch (Exception $e) 
		{
            return response()->json($e,500);
		}

	}

	public function UpdateExpense(Request $request) 
	{
		try 
		{
			$input = $request->all();

            $get_expenses = Expenses::where('id',$input['expense_id'])->first();


			$image= $request->file('attach_file');
            if (empty($image)) 
            {
                $docs_img = $get_expenses->attach_files;
                
            }
            else
            {
                foreach ($image as $key) 
                {
                    $imagename =  "expense_".rand(11111,99999).".".$key->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/expenses');

                    if($key->move($destinationPath, $imagename))
                    {
                            $path[] =  'expenses/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Document Uploading");
                    }
                }
                
                if (empty($get_expenses->attach_files)) 
                {
	                $docs_img = implode(",", $path);
                }
                else
                {
	                $docs_img = $get_expenses->attach_files.",".implode(",", $path);
                }
                   
            }


			$data = array(
							"expense_type_id"	=> $input['type'],	
							"beneficiary_id"	=> isset($input['beneficiary'])?$input['beneficiary']:0,	
							"amount"			=> $input['amount'],
							"payment_date"		=> $input['payment_date'],
							"next_payment_date"	=> isset($input['next_payment_date'])?$input['next_payment_date']:'0000-00-00 00:00:00',
							"details"			=> $input['details'],
							"account_period"	=> $input['account_period'],
							"is_notified"		=> 0,
							"attach_files"		=> $docs_img,
					);

			if (Expenses::where('id',$input['expense_id'])->update($data)) 
			{
				return redirect()->route('expenses.view-expenses')->with('success',__('web.Data Updated Successfully'));
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


	public function ViewExpense(Request $request) 
	{
		try 
		{
			$expense_data = Expenses::orderBy('id','asc')->get();
			$expense_type = ExpenseType::where('is_deleted',0)->where('parent_id',0)->get();


			$from_date = "";
			$to_date = "";
			$selected_expense_type = "";
			$selected_beneficiary = "";
			$overdue_check = "";
			$upcoming_check = "";

			return view('expenses.view',compact('expense_data','expense_type','from_date','to_date','selected_expense_type','selected_beneficiary','overdue_check','upcoming_check'));
		} 
		catch (Exception $e) 
		{
            return response()->json($e,500);
		}
	}


	public function DeleteExpense(Request $request,$id) 
	{
		try 
		{	
			 //delete expenses file docs first
            $get_expenses = Expenses::where('id',$id)->first();
            $files = explode(",", $get_expenses->attach_files);
            foreach ($files as $key) 
            {
              $image_path = public_path('images/'.$key);  
              if(File::exists($image_path)) 
              {
                  File::delete($image_path);
              }
            }

			if(Expenses::where('id',$id)->delete())
			{
				return redirect()->route('expenses.view-expenses')->with('success',__('web.Data Deleted Successfully'));
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



	public function DetailsExpense(Request $request,$id)
	{
		try 
		{
			
		$expense = Expenses::where('id',$id)->first();

		if ($expense == "") 
		{
			?>
			<br><center><p class="text-danger"><?php echo __('web.No Data Found'); ?></p></center>
			<?php
		}
		else
		{
			?>


			<table class="table">
				<tbody >
					<tr>
						<th>Expense Type: </th>
						<td><?php echo $expense->ExpenseTypeName->name ?></td>
						<th>Beneficiary: </th>
						<td><?php echo isset($expense->BeneficiaryName->name)?$expense->BeneficiaryName->name:"-" ?></td>
						<th>Amount: </th>
						<td><?php echo $expense->amount ?></td>
					</tr>
					<tr>
						<th>Payment Date: </th>
						<td><?php echo $expense->payment_date ?></td>
						<th>Next Payment Date: </th>
						<td><?php echo $expense->next_payment_date ?></td>
						<th></th>
						<td></td>
					</tr>

					<tr><th colspan="6">Details:</th></tr>
					<tr><td colspan="6"><?php echo $expense->details ?></td></tr>
					<tr><th colspan="6">Account Period:</th></tr>
					<tr><td colspan="6"><?php echo $expense->account_period ?></td></tr>
				</tbody>
			</table>










			<?php 
		}	
		} 
		catch (Exception $e) 
		{
            return response()->json($e,500);
		}
	}




	public function FilterExpense(Request $request) 
	{	
		try 
		{
			
		$input = $request->all();


		$expense_type = ExpenseType::where('is_deleted',0)->where('parent_id',0)->get();


		if (empty($input['from_date']) || empty($input['to_date'])) 
		{
			
			if (empty($input['overdue'])) 
			{
				if (empty($input['type'])) 
				{
					if (empty($input['beneficiary'])) 
					{
						$expense_data = Expenses::get();
					}
					else
					{
						$expense_data = Expenses::where('beneficiary_id',$input['beneficiary'])->get();
					}
				}
				else
				{
					if (empty($input['beneficiary'])) 
					{
						$expense_data = Expenses::where('expense_type_id',$input['type'])->get();
					}
					else
					{
						$expense_data = Expenses::where('expense_type_id',$input['type'])->where('beneficiary_id',$input['beneficiary'])->get();
					}
				}
			}
			else
			{
				if (empty($input['type'])) 
				{
					if (empty($input['beneficiary'])) 
					{
						$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->get();
					}
					else
					{
						$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->where('beneficiary_id',$input['beneficiary'])->get();
					}
				}
				else
				{
					if (empty($input['beneficiary'])) 
					{
						$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->where('expense_type_id',$input['type'])->get();
					}
					else
					{
						$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->where('expense_type_id',$input['type'])->where('beneficiary_id',$input['beneficiary'])->get();
					}
				}
			}



			$from_date = "";
			$to_date   = "";
		}
		else
		{
			if (empty($input['upcoming'])) 
			{
				if (empty($input['overdue'])) 
				{
					if (empty($input['type'])) 
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
					else
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
				}
				else
				{
					if (empty($input['type'])) 
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
					else
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
				}
			}
			else
			{
				if (empty($input['overdue'])) 
				{
					if (empty($input['type'])) 
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('next_payment_date','>=',$input['from_date'])->whereDate('next_payment_date','<=',$input['to_date'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('next_payment_date','>=',$input['from_date'])->whereDate('next_payment_date','<=',$input['to_date'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
					else
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('next_payment_date','>=',$input['from_date'])->whereDate('next_payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('next_payment_date','>=',$input['from_date'])->whereDate('next_payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
				}
				else
				{
					if (empty($input['type'])) 
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
					else
					{
						if (empty($input['beneficiary'])) 
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->get();
						}
						else
						{
							$expense_data = Expenses::whereDate('next_payment_date','<=',date("Y-m-d"))->where('next_payment_date','!=','0000-00-00')->whereDate('payment_date','>=',$input['from_date'])->whereDate('payment_date','<=',$input['to_date'])->where('expense_type_id',$input['type'])->where('beneficiary_id',$input['beneficiary'])->get();
						}
					}
				}
			}


			$from_date = $input['from_date'];
			$to_date = $input['to_date'];
		}

		$expense_data = $expense_data->sortBy('id');

		$selected_expense_type = $input['type'];
		$selected_beneficiary = $input['beneficiary'];
		$overdue_check = empty($input['overdue'])?0:1;
		$upcoming_check = empty($input['upcoming'])?0:1;

		return view('expenses.view',compact('expense_data','expense_type','from_date','to_date','selected_expense_type','selected_beneficiary','overdue_check','upcoming_check'));	
		} 
		catch (Exception $e) 
		{
			
		}
	}


	public function DeleteExpensesFiles(Request $request)
    {
        try 
        { 

          $input = $request->all();
          $get_files = Expenses::where('id',$input['expense_id'])->first();

          if ($get_files == "") 
          {
            return false;
          }
            $files_arr = explode(",",$get_files->attach_files);
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

          Expenses::where('id',$input['expense_id'])->update(array('attach_files'=>$files_arr));

          return true;
        
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

}
