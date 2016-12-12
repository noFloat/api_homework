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
			    	'password'  =>	password_hash($request->input('password'),PASSWORD_DEFAULT)

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

	public function login(Request $request){
		$user = new User();
		$userState = $user->where('phone','=',$request->input('phone'))->get();
		if (!password_verify ( $request->input('password') , $userState[0]->password)){
			$info = [
                "status"  => 500,
                "info"    =>"invalid parameter"
        	];
		} else {
			$info = [
                "status"  => 200,
                "info"    =>"success",
                'user_id' => $userState[0]->id
            ];
		}
		return response()->json($info);
	}
}
