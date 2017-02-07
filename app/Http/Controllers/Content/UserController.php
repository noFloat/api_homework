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

	private function curl_init($url,$post_data){//初始化目标网站
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
        $output = curl_exec($ch);

        return $output;
    }

	public function login(Request $request){
		$data = ' {
"action_name": "QR_CARD", 
"expire_seconds": 1800,
"action_info": {
"card": {
"card_id": "puRCyjs_YJGOZ1apBcm_zTP_s-BE", 
"code": "1212",
"openid": "ouRCyjluyYmPNN5ges5JgoZ9A4J0",
"is_unique_code": true ,
"outer_id" : 1
  }
 }
}';
		$createRequest = $this->curl_init("https://api.weixin.qq.com/card/qrcode/create?access_token=WK3wDmvbamYJNoqf2ZExbFrfLft4WpTtOwYxRU7ASINXeVusaTTrpdeVc8nJa2G8849Po2JI-RaCvgDnDjpO-HbWHgKtXH9LMXJVmh3RF9jkGKYDUyELYCSq2RC24K6MEDBbACANMKm",$data);
		echo $createRequest;exit;
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
