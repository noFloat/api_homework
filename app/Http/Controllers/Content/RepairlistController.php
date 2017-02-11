<?php

namespace App\Http\Controllers\Content;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Pipeline\Pipeline;
use App\Repairlist;

class RepairlistController extends Controller
{
    public function insert(Request $request)
    {
    	$goal = new Repairlist();

       	$data = array_filter($request->input());

        foreach ($data as $key => $value) {
            $goal->$key = $value;
        }
        $goal->status = 1;
        $info = [
	                "status"  => 200,
	                "info"    =>"success"
	            ];
        $goal->save();
        return response()->json($info);
    }

    public function grab(Request $request){
    	$repairlist = new Repairlist();
    	if(empty($request->input('repairlist_id'))||empty($request->input('repairer_id'))){
    		$info = [
	                "status"  => 500,
	                "info"    =>"invalid parameter"
            	];
    	}else{
    		$goal = $repairlist->where('id','=',$request->input('repairlist_id'))->update(
    			[
    				'repairer_id'=>$request->input('repairer_id'),
    				'status'=>3
    			]
    		);
	    	$info = [
		                "status"  => 200,
		                "info"    =>"success"
		            ];
    	}
	    return response()->json($info);
    }

    public function confirm(Request $request){
    	$repairlist = new Repairlist();
    	if(empty($request->input('repairlist_id'))){
    		$info = [
	                "status"  => 500,
	                "info"    =>"invalid parameter"
            	];
    	}else{
    		$goal = $repairlist->where('id','=',$request->input('repairlist_id'))->update(
    			[
    				'status'=>2
    			]
    		);
	    	$info = [
		                "status"  => 200,
		                "info"    =>"success"
		            ];
    	}
	    return response()->json($info);
    }

    public function evaluate(Request $request){
    	$goal = new Repairlist();
    	if(empty($request->input('rank_star'))||empty($request->input('remark_content'))){
    		$info = [
	                "status"  => 500,
	                "info"    =>"invalid parameter"
            	];
    	}else{
    		$content = array(
    			"rank_star" => $request->input('rank_star'),
    			"remark_content"=>$request->input('remark_content')

    		);
	    	$goal->where('id','=',$request->input('id'))->update($content);
	    	$info = [
		                "status"  => 200,
		                "info"    =>"success"
		            ];
    	}
	    return response()->json($info);
    }

    public function repairerSearch(Request $request){
    	$goal = new Repairlist();
    	if(empty($request->input('page'))||empty($request->input('limit'))){
    		$info = [
	                "status"  => 500,
	                "info"    =>"invalid parameter"
            	];
    	}else{
    		$take = $request->input('limit');
    		$skip = ($request->input('page')-1)*$take;
	    	$data=$goal->where('status','=','1')->orderBy('created_at','DESC')->join('equipments','repairlists.equipment_id','=','equipments.id')->join(
	    		'users','repairlists.user_id','=','users.id')->skip($skip)->take($take)->select('repairlists.id as repairlist_id','repairlists.equipment_id','repairlists.created_at','equipments.equipment_name','equipments.equipment_type','users.phone')->get();
	    	$info = [
		                "status"  => 200,
		                "info"    =>"success",
		                'data'   => $data
		            ];
    	}
	    return response()->json($info);
    }

    public function userSearch(Request $request){
    	$goal = new Repairlist();
    	if(empty($request->input('page'))||empty($request->input('limit'))||empty($request->input('user_id'))){
    		$info = [
	                "status"  => 500,
	                "info"    =>"invalid parameter"
            	];
    	}else{
    		$take = $request->input('limit');
    		$skip = ($request->input('page')-1)*$take;
	    	$data=$goal->where('user_id','=',$request->user_id)->orderBy('created_at','DESC')->leftJoin('equipments','repairlists.equipment_id','=','equipments.id')->leftJoin(
	    		'repairers','repairlists.repairer_id','=','repairers.id')->skip($skip)->take($take)->select('repairlists.id as repairlist_id','repairlists.equipment_id','repairlists.created_at','repairlists.status','equipments.equipment_name','equipments.equipment_type','repairers.phone')->get();
	    	$info = [
		                "status"  => 200,
		                "info"    =>"success",
		                'data'   => $data
		            ];
    	}
	    return response()->json($info);
    }

}
