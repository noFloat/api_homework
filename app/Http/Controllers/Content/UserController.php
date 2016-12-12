<?php

namespace App\Http\Controllers\Content;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\User;

class UserController extends Controller
{

	public function register(Request $request){
		$user = new User();
		$userExist = $user->checkExist($request->input('phone'));
		if($userExist){
			$info = [
                "status"  => 304,
                "info"    =>"用户存在"
            ];
		}else{
			$user_id=$user->insertGetId(
			    [
			    	'phone' 	=> $request->input('phone'),
			    	'nick_name' => $request->input('nick_name'),
			    	'password'  =>	bcrypt($request->input('password'))

			   	]
			);
			if($user_id){
				$info = [
	                "status"  => 200,
	                "info"    =>"success",
	                'user_id' => $user_id
	            ];
			}else{
				$info = [
	                "status"  => 500,
	                "info"    =>"invalid parameter"
            	];
			}
		}
		return response()->json($info);
	}
}
