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
use App\Models\Items;
use App\Models\Sales;
use App\Models\Members;
use File;
use DB;

use Auth;

class StoreController extends Controller
{
    public function __construct(Request $request)
    {   
        
    }

    public function AddItems(Request $request){
        try
        {
            $supplier_data = Supplier::get();
        	return view('store.add_items',compact('supplier_data'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function ViewItems(Request $request){
        try
        {
            // $items_data = Items::get();
            $items_data = DB::table('items')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            // ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('items.type', 'store')
            ->get();
        	return view('store.items_list',compact('items_data'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function StockViewItems(){
        try
        {
            // $items_data = Items::get();
            $items_data = DB::table('items')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            // ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('items.type', 'store')
            ->get();
        	return view('store.stock_list',compact('items_data'));
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
        	return view('store.edit_items',compact('data'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SalesEdit(Request $request, $id){
        try{
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
        	return view('store.sales.edit_sales',compact('sales_data', 'products', 'members'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SalesAdd(Request $request){
        try
        {
            $products = Items::get();
            $members = Members::get();
        	return view('store.sales.create',compact('products', 'members'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SalesView(Request $request){
        try
        {
            $sales = DB::table('sales')
            ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('members', 'sales.member_id', '=', 'members.id')
            ->select('sales.*', 'items.items', 'items.size', 'members.id', 'members.name')
            ->where('sales.type', 'store')
            ->get();
            // $sales = Sales::where('type', 'store')->get();
        	return view('store.sales.sales',compact('sales'));
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
	          	return redirect()->route('store.sales-view')->with('success','Sales Deleted Successfully');
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


    public function CreateItems(Request $request)
    {	
        try 
        {	
            $input = $request->all();

            if(Items::where('items',trim(strtolower($input['item_name'])))->count() > 0)
            {
	          	return redirect()->back()->withInput()->with('failed','Item Already Exist.');
            }


            $image= $request->file('image');
            if (empty($image)) 
            {
                $path = "users/default_user_icon.png";
            }
                else
                {

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


            if(Items::insert([
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
	          	return redirect()->route('store.add-items')->with('success','Items Added Successfully.');
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
	          	return redirect()->route('store.view-items')->with('success','Items Updated Successfully.');
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
    
    public function DeleteItems(Request $request, $id){
        try{
            if(Items::where('item_id',$id)->delete())
        	{
	          	return redirect()->route('store.view-items')->with('success','Item Deleted Successfully');
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
    public function DeleteItemsImage(Request $request, $id){
        try{
            $item_data = Items::where('item_id',$id)->first();
            $image_path = "images/".$item_data['item_img'];
            Items::where('item_id',$id)->update(array('item_img' => ''));
            if(File::exists(public_path($image_path))) {
                File::delete(public_path($image_path));
            }
            return redirect()->back();
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SalesAjax(Request $request){
        $itemid = $request->id;
        $saleprice = Items::where('item_id', $itemid)->get();
        return $saleprice;
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
    public function SalesSave(Request $request){
        try{
            $input = $request->all();
            // echo "<pre>";
            // print_r($input);
            // echo "</pre>";
            // exit;
            if($input['discount'] == ''){
                $input['discount'] = 0;
            }
            // echo $total_qty;
            // dd($input);
            $items_qty = Items::where('item_id', $_POST['Items'])->first();
            $total_qty = $items_qty->qty - $_POST['Qty'];
            if(Sales::insert([
                'member_id'  => $input['Member'],
                'type' 	 => 'store',
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
    public function ItemsName(Request $request,$name){
        try{
            $items = Items::where('items','like','%'.$name.'%')->orderBy('items','asc')->get();

        	foreach ($items as $key) 
        	{
        		?>
        			<a class="btn btn-secondary" id="items_new" style="width: 80%; margin: 4px;" onclick='SelectEmployee("<?php echo $key['item_id'] ?>","<?php echo $key['items'] ?>")'><?php echo $key['items'] ?></a>
        		<?php
        	}
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function ItemsGetById(Request $request,$id){
        try{
            $items = Items::where('item_id', $id)->first();
            dd($items);
        	foreach ($items as $key) 
        	{
        		?>
        			<a class="btn btn-secondary" id="items_new" style="width: 80%; margin: 4px;" onclick='SelectEmployee("<?php echo $key['item_id'] ?>","<?php echo $key['items'] ?>")'><?php echo $key['items'] ?></a>
        		<?php
        	}
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SearchAllSales(Request $request){
        try{
            $input = $request->all();
            // dd($input);
            $sport = $input['sport'];
            $coach = $input['coach'];
            $member = $input['member'];
            $start_date = $input['start_date'];
            $end_date = $input['end_date'];
            // if($input['name'])
            if(empty($start_date) && empty($end_date)){
                $sales = DB::table('sales')
                ->join('items', 'sales.item_id', '=', 'items.item_id')
                ->join('members', 'sales.member_id', '=', 'members.id')
                ->where('sales.type', 'store')
                ->select('sales.*', 'items.items', 'members.id', 'members.name')
                ->where('members.name', 'LIKE', "%$member%")
                ->get();
            }else{    
            $sales = DB::table('sales')
            ->join('items', 'sales.item_id', '=', 'items.item_id')
            ->join('members', 'sales.member_id', '=', 'members.id')
            ->where('sales.type', 'store')
            ->select('sales.*', 'items.items', 'members.id', 'members.name')
            ->where('members.name', 'LIKE', "%$member%")
            ->whereBetween('sales.created_at', [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
            
            ->get();
            }
            return view('store.sales.sales',compact('sales', 'sport', 'coach', 'member', 'start_date', 'end_date'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }
    public function SearchAllPurchasingItem(Request $request){
        try{
            $input = $request->all();
            // dd($input);
            $product = $input['product'];
            $supplier = $input['supplier'];
            $start_date = $input['start_date'];
            $end_date = $input['end_date'];
            // if($input['name'])
            if(empty($start_date) && empty($end_date)){
                $items_data = DB::table('items')
                ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
                ->where('items.type', 'store')
                ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
                ->where('items.items', 'LIKE', "%$product%")
                ->where('supplier.supplier_name', 'LIKE', "%$supplier%")
                ->get();
            }else{    
            $items_data = DB::table('items')
            ->join('supplier', 'items.supplier', '=', 'supplier.supplier_id')
            ->where('items.type', 'store')
            ->select('items.*', 'supplier.supplier_id', 'supplier.supplier_name')
            ->where('items.items', 'LIKE', "%$product%")
            ->where('supplier.supplier_name', 'LIKE', "%$supplier%")
            ->whereBetween('items.created_at', [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))])
            
            ->get();
            }
            return view('store.items_list',compact('items_data', 'product', 'supplier', 'start_date', 'end_date'));
        }
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

}
