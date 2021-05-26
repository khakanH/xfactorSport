<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


use App\Models\UserType;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Modules;
use App\Models\Supplier;


use Auth;

class SupplierController extends Controller
{
    public function __construct(Request $request)
    {   
        
    }

    public function AddSupplier(Request $request)
    {	
        try 
        {
        	$user_type = UserType::get();
        	return view('supplier.add_supplier',compact('user_type'));
          
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SupplierList(Request $request){
        try{
            $all_supplier = Supplier::get();
        	return view('supplier.supplier_list',compact('all_supplier'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SupplierDetail(Request $request, $id){
        try{
            $supplier = Supplier::where('supplier_id', $id)->first();
        	return view('supplier.details',compact('supplier'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SearchSupplier(Request $request){
        try{
            $input = $request->all();
            // dd($input);
            $name = $input['name'];
            $address = $input['address'];
            $contact = $input['contact'];
            $all_supplier = Supplier::where('supplier_name', 'LIKE', "%$name%")
            ->where('supplier_address', 'LIKE', "%$address%")
            ->where('supplier_contact', 'LIKE', "%$contact%")
            ->get();
        	return view('supplier.supplier_list',compact('all_supplier', 'name', 'address', 'contact'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function EditSupplier(Request $request, $id){
        try{
            $supplier_data = Supplier::where('supplier_id',$id)->first();

         	return view('supplier.edit_supplier',compact('supplier_data'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }


    //Data submit Functions
    public function CreateSupplier(Request $request){
        try{
            $input = $request->all();
            // dd($input);
            if(Supplier::where('supplier_name',trim(strtolower($input['supplier_name'])))->count() > 0)
            {
	          	return redirect()->back()->withInput()->with('failed','Supplier Already Exist.');
            }
            if(Supplier::insert([
                        'supplier_name'    => $input['supplier_name'],
                        'supplier_address' => $input['supplier_address'],
                        'supplier_contact' => $input['supplier_contact'],
                        'contact_person' => $input['contact_person'],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]))
            {
	          	return redirect()->route('supplier.supplier-list')->with('success','Supplier Added Successfully.');
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
    
    public function UpdateSupplier(Request $request){
        try{
            $input = $request->all();
          	
            // if (Supplier::where('supplier_name',trim(strtolower($input['supplier_name'])))->where('supplier_id','!=',$input['id'])->count() > 0) 
            // {
            //     return redirect()->back()->withInput()->with('failed','Supplier Name Already Exists');
            // }
            // else
            // {
                if(Supplier::where('supplier_id',$input['id'])->update([
                    'supplier_name'    => $input['supplier_name'],
                    'supplier_address' => $input['supplier_address'],
                    'supplier_contact' => $input['supplier_contact'],
                    'contact_person' => $input['contact_person'],
                ]))
                {
                    return redirect()->route('supplier.supplier-list')->with('success','Supplier Updated Successfully');
                }
                else
                {
                    return redirect()->back()->withInput()->with('failed','Something went wrong. Try again later');
                }
            // }
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function DeleteSupplier(Request $request, $id){
        try{
            if(Supplier::where('supplier_id',$id)->delete())
        	{
	          	return redirect()->route('supplier.supplier-list')->with('success','Supplier Deleted Successfully');
        	}
        	else
        	{
				return redirect()->back()->with('failed','Something went wrong. Try again later');
        	}
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    

}
