<?php

namespace App\Http\Controllers\Content;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Userdomination;

class UserdominationController extends Controller
{
	public function search(Request $request){
		$userdomination = new Userdomination();
		$user_id = $request->input('user_id');
		$list = $userdomination->where('user_id',$user_id)->join('buildings','userdominations.building_id','=','buildings.id')->join('equipments','userdominations.equipment_id','=','equipments.id')->get();
		$info = array(
				'status' => 200,
				'info'   => 'success',
				'data'   => $list[0]['attributes']
			);
		return response()->json($info);
	}
	
	public function add(Request $request){
		$userdomination = new Userdomination();
		if(empty($request->input('user_id'))||empty($request->input('building_id'))||empty($request->input('equipment_id'))){
			$info = [
	                "status"  => 500,
	                "info"    =>"invalid parameter"
            	];

		}else{
			$content = array(
				"user_id" =>$request->input('user_id'),
				"building_id"=>$request->input('building_id'),
				"equipment_id" => $request->input('equipment_id')
			);
			$goal = $userdomination->where('user_id','=',$request->input('user_id'))
			->where('equipment_id','=',$request->input('equipment_id'))
			->where('building_id','=',$request->input('building_id'))->first();
			if(!empty($goal)){
				$info = [
	                "status"  => 502,
	                "info"    =>"exist!!!"
            	];
			}else{
				$userdomination->insertGetId($content);
				$info = [
		                "status"  => 200,
		                "info"    =>"success"
		            ];
			}
			
		}
		return response()->json($info);
	}

}
