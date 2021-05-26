<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


use App\Models\UserType;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Modules;


use Auth;
use Mail;

class UserController extends Controller
{
    public function __construct(Request $request)
    {   
    }

    public function AddUserType(Request $request)
    {	
        try 
        {
          return view('user.add_type');
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveUserType(Request $request)
    {	
        try 
        {
            $input = $request->all();
          	
          	if (UserType::where('name',trim(strtolower($input['name'])))->count() > 0) 
          	{
          		return redirect()->back()->withInput()->with('failed','User Type Already Exists');
          	}
          	else
          	{
          		if(UserType::insert(array('name'=>trim(strtolower($input['name'])))))
          		{
	          		return redirect()->route('user.add-type')->with('success','User Type Added Successfully');
          		}
          		else
          		{
	          		return redirect()->back()->withInput()->with('failed','Something went wrong. Try again later');
          		}
          	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function EditUserType(Request $request,$id)
    {	
        try 
        {
        	$user_type = UserType::where('id',$id)->first();

         	return view('user.edit_type',compact('user_type'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function UpdateUserType(Request $request)
    {	
        try 
        {
            $input = $request->all();
          	
          	if (UserType::where('name',trim(strtolower($input['name'])))->where('id','!=',$input['id'])->count() > 0) 
          	{
          		return redirect()->back()->withInput()->with('failed','User Type Already Exists');
          	}
          	else
          	{
          		if(UserType::where('id',$input['id'])->update(array('name'=>trim(strtolower($input['name'])))))
          		{
	          		return redirect()->route('user.type-list')->with('success','User Type Updated Successfully');
          		}
          		else
          		{
	          		return redirect()->back()->withInput()->with('failed','Something went wrong. Try again later');
          		}
          	}

        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function DeleteUserType(Request $request,$id)
    {	
        try 
        {
        	if(UserType::where('id',$id)->delete())
        	{
	          	return redirect()->route('user.type-list')->with('success','User Type Deleted Successfully');
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

    public function UserTypeList(Request $request)
    {	
        try 
        {
        	$user_type = UserType::get();
       		return view('user.type_list',compact('user_type'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

   













    public function AddUser(Request $request)
    {	
        try 
        {
        	$user_type = UserType::get();
        	return view('user.add_user',compact('user_type'));
          
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveUser(Request $request)
    {	
        try 
        {	
            $input = $request->all();

            if(User::where('email',trim(strtolower($input['email'])))->where('user_type',$input['type'])->count() > 0)
            {
	          	return redirect()->back()->withInput()->with('failed','User Email Address Already Exist.');
            }


            $image= $request->file('image');
            if (empty($image)) 
            {
                $path = "users/default_user_icon.png";
            }
                else
                {

                    $input['imagename'] =  uniqid().$image->getClientOriginalExtension();
                   
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


            if(User::insert([
                        'name' 		 => $input['name'],
                        'email' 	 => trim(strtolower($input['email'])),
                        'password' 	 => Hash::make($input['password']),
                        'user_type'	 => $input['type'],
                        'profile_image'	 => $path,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]))
            {   
                $user_type = UserType::where('id',$input['type'])->first()->name;

                $app_url = config('app.url');
                $data = array('app_url'=>$app_url,'email'=>trim(strtolower($input['email'])),'password'=>$input['password'],'user_type'=>$user_type);
                Mail::send('user_register_mail', $data, function($message) use ($input){
                                                    $message->to(trim(strtolower($input['email'])),'')
                                                            ->subject('X-Factor User Registration');
                                                  });

	          	return redirect()->route('user.add-user')->with('success','User Added Successfully.');
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

    public function DeleteUser(Request $request,$id)
    {	
        try 
        {
          	if(User::where('id',$id)->delete())
        	{
	          	return redirect()->route('user.user-list')->with('success','User Deleted Successfully');
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

	public function UserList(Request $request)
    {	
        try 
        {
        	$user = User::where('id','!=',Auth::user()->id)->get();
        	return view('user.user_list',compact('user'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }










    public function UserRoles(Request $request)
    {	
        try 
        {	
        	$user_type = UserType::orderBy('id','asc')->get();
            if (count($user_type) == 0) 
            {
                return redirect()->route('user.add-type')->with('failed','Kindly Add User Type First.');
            }

            $type = $user_type[0]['id'];

            $all_modules = Modules::where('parent_id',0)->get();
            $all_sub_modules = array();
            foreach ($all_modules as $key) 
            {
                $all_sub_modules[] = Modules::where('parent_id',$key['id'])->get();
            }

            $user_role = UserRole::where('user_type',$type)->pluck('module_id')->toArray();


            return view('user.user_role',compact('user_type','all_modules','all_sub_modules','user_role','type'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function GetUserRoles(Request $request,$type)
    {	
        try 
        {
        	$user_type = UserType::orderBy('id','asc')->get();
            if (count($user_type) == 0) 
            {
                return redirect()->route('user.add-type')->with('failed','Kindly Add User Type First.');
            }


            $all_modules = Modules::where('parent_id',0)->get();
            $all_sub_modules = array();
            foreach ($all_modules as $key) 
            {
                $all_sub_modules[] = Modules::where('parent_id',$key['id'])->get();
            }

            $user_role = UserRole::where('user_type',$type)->pluck('module_id')->toArray();


            return view('user.user_role',compact('user_type','all_modules','all_sub_modules','user_role','type'));
        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }
    }

    public function SaveUserRoles(Request $request)
    {
        try 
        {   
            $input = $request->all();    
           
            UserRole::where('user_type',$input['user_type_id'])->delete();
            
            if (isset($input['main_module-cb'])) 
            {

                foreach ($input['main_module-cb'] as $key) 
                {
                    UserRole::insert(array(
                            'user_type' => $input['user_type_id'],
                            'module_id'   => $key,
                    ));
                }
            }


            return redirect()->route('user.user-roles')->with('success','User Roles Saved Successfully');
            


        } 
        catch (Exception $e) 
        {
            return response()->json($e,500);
        }

    }

}
