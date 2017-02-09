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

}
