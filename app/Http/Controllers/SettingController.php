<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\GeneralSetting;


use Auth;
use App;
use Mail;

class SettingController extends Controller
{
    public function __construct(Request $request)
    {   

    }

    public function UserAccount(Request $request)
    {	
    	$data = User::where('id',Auth::user()->id)->first();

    	if ($data == "") 
    	{
	         return redirect()->back()->with('failed',__('web.Invalid Data'));
    	}

    	return view('account.account_setting',compact('data'));
    }

    public function SaveAccountSetting(Request $request)
    {	
    	$validator = \Validator::make($request->all(), 
                                        [
                                          'name' => 'required',
                                          'email' => 'required|email',
                                          'image'    => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:5120',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $input = $request->all();

            $input['email'] = trim(strtolower($input['email']));

            $check_duplicate = User::where('email',$input['email'])->where('id','!=',Auth::user()->id)->first();

            if ($check_duplicate != "") 
            {
              return redirect()->back()->withInput()->with('failed',__('web.Email Already Exist'));
            }

            $user_data = User::where('id',Auth::user()->id)->first();

            $image= $request->file('image');
            if (empty($image)) 
            {
                $path = $user_data->profile_image;
                
            }
            else
            {
                   $imagename =  str_replace(" ", "_",trim(strtoupper($input['name'])))."_".rand(11111,99999).".".$image->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/users');

                    if($image->move($destinationPath, $imagename))
                    {
                            $path =  'users/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed",__("web.Something Went Wrong for Image Uploading"));
                    }
                   
            }

            $data = array(
            				'name' 		=> $input['name'],
            			  	'email' 	=> $input['email'],
            			  	'profile_image' => $path,
            			);

            if (User::where('id',Auth::user()->id)->update($data)) 
            {
            	return redirect()->route('account.user-account')->with('success',__('web.Data Updated Successfully'));
            }
            else
            {
                return redirect()->back()->withInput()->with('failed',__('web.Something went wrong. Try again later'));
            }

    }


    public function ChangeAccountPassword(Request $request)
    {	
    	$validator = \Validator::make($request->all(), 
                                        [
                                          'current_password' => 'required',
                                          'new_password' 	 => 'required',
                                          'confirm_password' => 'required',

                                        ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors());
            }

            $input = $request->all();

            $check_account = User::where('id',Auth::user()->id)->first();

	    	if ($check_account == "") 
	    	{
		         return redirect()->back()->with('failed',__('web.Invalid Data'));
	    	}

            if (!Hash::check($input['current_password'],$check_account->password)) 
            {
                    return redirect()->back()->with('failed',__('web.Invalid Current Password'));
            }

           
            $data = array(
            				'password' 		=> Hash::make($input['new_password']),
            			);

            if (User::where('id',Auth::user()->id)->update($data)) 
            {
            	return redirect()->route('account.user-account')->with('success',__('web.Data Updated Successfully'));
            }
            else
            {
                return redirect()->back()->with('failed',__('web.Something went wrong. Try again later'));
            }
    }



    public function GeneralSetting(Request $request)
    {	
    	$general_setting = GeneralSetting::first();

        if ($general_setting == "") 
        {
             return redirect()->back()->with('failed',__('web.Invalid Data'));
        }

    	return view('settings.general_setting',compact('general_setting'));
    }


    public function SaveGeneralSetting(Request $request)
    {   
            $input = $request->all();

            $get_data = GeneralSetting::first();

            if ($get_data == "") 
            {
                return redirect()->back()->withInput()->with('failed',__('web.Something went wrong. Try again later'));
            }

            $image= $request->file('image');
            if (empty($image)) 
            {
                $path = $get_data->system_logo;
                
            }
            else
            {
                   $imagename =  "LOGO_".rand(11111,99999).".".$image->getClientOriginalExtension();
                   
                    $destinationPath = public_path('/images/general_setting');

                    if($image->move($destinationPath, $imagename))
                    {
                            $path =  'general_setting/'.$imagename;
                    }
                    else
                    {
                            return redirect()->back()->withInput()->with("failed",__("web.Something Went Wrong for Image Uploading"));
                    }
                   
            }

            $data = array(
                            'printout_head_letter'  => $input['head_letter'],
                            'system_logo'           => $path,
                        );

            if (GeneralSetting::where('id',$get_data->id)->update($data)) 
            {
                return redirect()->route('setting.general-setting')->with('success',__('web.Data Updated Successfully'));
            }
            else
            {
                return redirect()->back()->withInput()->with('failed',__('web.Something went wrong. Try again later'));
            }



    }

}
