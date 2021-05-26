<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


use App\Models\UserType;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Modules;
use App\Models\Items;
use App\Models\Sales;
use App\Models\Members;
use App\Models\Supplier;
use DB;
use Auth;

class CafeteriaController extends Controller
{
    public function __construct(Request $request)
    {   
        // dd('herererer');
    }

   public function SalesView(Request $request){
    try{
        // $sales = Sales::where('type', 'cafeteria')->get();
        $sales = DB::table('sales')
            ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('sales.*', 'items.items', 'members.id', 'members.name')
            ->get();
        
        // foreach($sales as $sale){
        //     // dd($sales);
        //     $members = Members::where('id', $sale->member_id)->get();
        //     dd($sales);
        // }
        $members = Members::get();
        return view('cafeteria.sales_view',compact('sales'));
    }
    catch (Exception $e) 
        {
            return response()->json($e,500);
        }
   }
   
//    Show All Purchasing Data start here..
    public function PurchasingData(Request $request){
        try{
            // $purchase_data = Items::where('type', 'Cafeteria')->get();
            $purchase_data = DB::table('items')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            // ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('items.type', 'Cafeteria')
            ->get();

            return view('cafeteria.purchasing-items',compact('purchase_data'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function EditItems(Request $request, $id){
        try{
            $data['supplier_data'] = Supplier::get();
            $data['items_data'] = Items::where('item_id',$id)->first();
            // dd($data);
        	return view('cafeteria.edit_items',compact('data'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

   public function DeleteSales(Request $request,$id)
    {	
        try 
        {
        	if(Sales::where('sale_id',$id)->delete())
        	{
	          	return redirect()->route('cafeteria.sales-view')->with('success','Sales Deleted Successfully');
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

    public function AddCafeteria(Request $request)
    {	
        try 
        {
        	$products = Items::get();
            $members = Members::get();
        	return view('cafeteria.add-cafeteria',compact('products', 'members'));
          
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function AllStock(Request $request){
        try 
        {
        	$sales = DB::table('items')
            // ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('type', 'Cafeteria')
            ->get();
        
        // foreach($sales as $sale){
            // dd($sales);
        //     $members = Members::where('id', $sale->member_id)->get();
        //     dd($sales);
        // }
        $members = Members::get();
        	return view('cafeteria.all-stock-view',compact('sales', 'members'));
          
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SalesEdit(Request $request, $id){
        try 
        {
            // $sales_data = Sales::where('sale_id',$id)->first();
            $sales_data = DB::table('sales')
            ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('sales.*', 'items.item_id', 'items.items', 'members.id', 'members.name')
            ->where('sales.sale_id', $id)
            ->first();
            $products = Items::get();
            $members = Members::get();
            
            // dd($sales_data);
        	return view('cafeteria.sales-edit',compact('sales_data', 'products', 'members'));
          
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveCafeteria(Request $request){
        try{
            $input = $request->all();
            if($input['discount'] == ''){
                $input['discount'] = 0;
            }
            $items_qty = Items::where('item_id', $_POST['Items'])->first();
            $total_qty = $items_qty->qty - $_POST['item_qty'];
            // echo $total_qty;
            // dd($input);
            if(Sales::insert([
                'member_id'  => $input['Member'],
                'type' 	 => 'cafeteria',
                'item_id' => $input['Items'],
                'qty' 	 => $input['item_qty'],
                'batch'	 => $input['item_batch'],
                'expiry_date'	=> $input['expiry_date'],
                'volume'	 => $input['item_volume'],
                'discount'	 => $input['discount'],
                'sale_price'	 => $input['price_per_item'],
                'purchase_price'	 => $input['Purchase_Price'],
                'total_amount' => $input['total'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]));
            if(Items::where('item_id', $_POST['Items'])->update([
                'qty'	 => $total_qty,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]))
    {
        return redirect()->back()->withInput()->with('success','Sales Add Successfully.');
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
    public function UpdateItems(Request $request)
    {	
        try 
        {	
            $input = $request->all();

            // if(Items::where('items',trim(strtolower($input['item_name'])))->count() > 0)
            // {
	        //   	return redirect()->back()->withInput()->with('failed','Item Already Exist.');
            // }


            $image= $request->file('image');
            if (empty($image) && empty($input['image_old'])) 
            {
                $path = "users/default_user_icon.png";
            }
                else
                {
                    if(isset($input['image_old'])){
                        $path = $input['image_old'];
                    }else{
                    $input['imagename'] =  uniqid().".".$image->getClientOriginalExtension();
                    // dd($input);
                   
                    $destinationPath = public_path('/images/users');

                    if($image->move($destinationPath, $input['imagename']))
                    {
                            $path =  'users/'.$input['imagename'];
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed","Something Went Wrong for Image Uploading");
                    }

                }
            }

            // dd($path);
            // exit;
            if(Items::where('item_id',$input['id'])->update([
                        'items' 		 => $input['item_name'],
                        'type' 	 => $input['type'],
                        'supplier' 	 => $input['supplier'],
                        'batch'	 => $input['batch'],
                        'size'	 => $input['item_size'],
                        'expiry_date'	 => $input['expiry_date'],
                        'item_img'	 => $path,
                        'qty'	 => $input['item_qty'],
                        'sale_price'	 => $input['sale_price'],
                        'purchase_price'	 => $input['purchase_price'],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]))
            {
	          	return redirect()->route('cafeteria.purchasing-data')->with('success','Items Updated Successfully.');
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
    public function SearchCafeteria(Request $request){
        try{
            $input = $request->all();
            // dd($input);
            $sport = $input['sport'];
            $coach = $input['coach'];
            $member = $input['member'];
            $start_date = $input['start_date'];
            $end_date = $input['end_date'];
            // if($input['name'])
            // var_dump(empty($start_date));
            // exit;
            if(empty($start_date) && empty($end_date)){
                $sales = DB::table('sales')
            ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('sales.*', 'items.items', 'members.id', 'members.name')
            ->where('members.name', 'LIKE', "%$member%")
            ->get();
            }else{
            $sales = DB::table('sales')
            ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('sales.*', 'items.items', 'members.id', 'members.name')
            ->where('members.name', 'LIKE', "%$member%")
            ->whereBetween('sales.created_at', [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
            ->get();
            }
        return view('cafeteria.sales_view',compact('sales','sport', 'coach', 'member', 'start_date', 'end_date'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SearchStockCafeteria(Request $request){
        try{
            $input = $request->all();
            dd($input);
            $sport = $input['sport'];
            $coach = $input['coach'];
            $member = $input['member'];
            $start_date = $input['start_date'];
            $end_date = $input['end_date'];
            // if($input['name'])
            // var_dump(empty($start_date));
            // exit;
            if(empty($start_date) && empty($end_date)){
                $sales = DB::table('items')
            // ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('type', 'Cafeteria')
            ->where('members.name', 'LIKE', "%$member%")
            ->get();
            }else{
                $sales = DB::table('items')
                // ->join('items', 'sales.item_id', '=', 'items.item_id')
                ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
                ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
                ->where('type', 'Cafeteria')
                ->where('members.name', 'LIKE', "%$member%")
                ->whereBetween('sales.created_at', [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
                ->get();
            }

            $sales = DB::table('items')
            // ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('type', 'Cafeteria')
            ->get();
        return view('cafeteria.all-stock-view',compact('sales','sport', 'coach', 'member', 'start_date', 'end_date'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SalesUpdate(Request $request){
        try{
            $input = $request->all();
            // echo "<pre>";
            // print_r($input);
            // echo "</pre>";
            // exit;
            if(Sales::where('sale_id',$input['id'])->update([
                'member_id'  => $input['Member'],
                // 'type' 	 => 'store',
                'item_id' => $input['Items'],
                'qty' 	 => $input['Qty'],
                'batch'	 => $input['Batch'],
                'expiry_date'	=> $input['Expiry_Date'],
                'volume'	 => $input['Volume'],
                'discount'	 => $input['discount'],
                'sale_price'	 => $input['Sale_Price'],
                'purchase_price'	 => $input['Purchase_Price'],
                'total_amount' => $input['TotalAmount'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ])){
                return redirect()->back()->withInput()->with('success','Sales Updated Successfully.');
            }else{
            return redirect()->back()->withInput()->with('success','Sales Updated Successfully.');
            }
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SearchPurchaseItem(Request $request){
        try{
            $input = $request->all();
            // dd($input);
            $product = $input['product'];
            $supplier = $input['supplier'];
            $start_date = $input['start_date'];
            $end_date = $input['end_date'];
            // if($input['name'])
            if(empty($start_date) && empty($end_date)){
            $purchase_data = DB::table('items')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            // ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('items.type', 'Cafeteria')
            ->where('items.items', 'LIKE', "%$product%")
            ->where('supplier.supplier_name', 'LIKE', "%$supplier%")
            ->get();
            }else{
                $purchase_data = DB::table('items')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            // ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('items.type', 'Cafeteria')
            ->where('items.items', 'LIKE', "%$product%")
            ->where('supplier.supplier_name', 'LIKE', "%$supplier%")
            ->whereBetween('items.created_at', [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
            ->get();
            }
        
            return view('cafeteria.purchasing-items',compact('purchase_data', 'product', 'supplier', 'start_date', 'end_date'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function MembersName(Request $request,$name){
        try{
            $members = Members::where('name','like','%'.$name.'%')->orderBy('name','asc')->get();
            // dd($members);
        	foreach ($members as $key) 
        	{
        		?>
        			<a class="btn btn-secondary" id="member_new" style="width: 80%; margin: 4px;" onclick='SelectMember("<?php echo $key['id'] ?>","<?php echo $key['name'] ?>")'><?php echo $key['name']; ?></a>
        		<?php
        	}
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
}
