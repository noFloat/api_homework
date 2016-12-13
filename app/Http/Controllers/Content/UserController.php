<?php

namespace App\Http\Controllers\Content;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;
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

	public function update(Request $request){
		if(empty($request->input('phone'))){
			$info = [
                "status"  => 500,
                "info"    =>"invalid parameter"
        	];
        	return response()->json($info);
		}

		$file = $request->file('avatar');
		if(!empty($file)){
			if ($file->isValid()) {
	            // 获取文件相关信息
	            $originalName = $file->getClientOriginalName(); // 文件原名
	            $ext = $file->getClientOriginalExtension();     // 扩展名
	            $realPath = $file->getRealPath();   //临时文件的绝对路径

	            $type = $file->getClientMimeType();     // image/jpeg

	            // 上传文件
	            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
	            $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
	            User::where('phone','=',$request->input('phone'))->update(['avatar_src' => $filename]);
        	}else{
        		$info = [
	                "status"  => 501,
	                "info"    =>"server failed"
            	];
            	return response()->json($info);
        	}
		}

		if(!empty($request->input('nick_name'))){
			User::where('phone','=',$request->input('phone'))->update(['nick_name' => $request->input('nick_name')]);
		}
	
		$info = [
            "status"  => 200,
            "info"    =>"success"
    	];
    	return response()->json($info);
	}

	public function search(Request $request){
		$user = new User();
		$userInfo = $user->where('phone','=',$request->input('phone'))
		->select('phone','created_at','updated_at','user_type','avatar_src','nick_name')->get();
		if(empty($userInfo[0]['attributes'])){
			$info = [
                "status"  => 500,
                "info"    =>"invalid parameter"
        	];
		}else{
			$info = array(
				'status' => 200,
				'info'   => 'success',
				'data'   => $userInfo[0]['attributes']
			);
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
