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

}
