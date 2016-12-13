<?php

namespace App\Http\Controllers\Content;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Building;
use App\Equipment;
use App\Daydata;
use App\Energydata;

class BuildingController extends Controller
{
	public function search(Request $request){

		$buildingInfo = Building::where('id','=','1')->get();
		$buildingId = $buildingInfo[0]->id;
		
		$equipmentInfo = Equipment::where('building_id','=',$buildingId)->get();
		foreach ($equipmentInfo as $key => $value) {
			$equipmentId[$key] = $value['id'];
		}
		$data_date = Daydata::whereIn('eiid',$equipmentId)->orderBy('etime','DESC')->take(15)->get();
		$d_value = 0;
		$e_value = 0;
		foreach ($data_date as $key => $value) {
			$d_value += $value['attributes']['dvalue'];
			$e_value += $value['attributes']['evalue'];
		}
		$buildinginfo = $buildingInfo[0]['attributes'];
		$buildinginfo['e_value'] = $e_value;
		$buildinginfo['d_value'] = $d_value;

		$tody_data = Daydata::whereIn('eiid',$equipmentId)->orderBy('etime','DESC')
		->skip(count($equipmentInfo))->take(count($equipmentInfo))->get();

		$today_d_value = 0;
		$today_e_value = 0;
		foreach ($data_date as $key => $value) {
			$today_d_value += $value['attributes']['dvalue'];
			$today_e_value += $value['attributes']['evalue'];
		}
		$buildinginfo['today_e_value'] = $e_value - $today_e_value;
		$buildinginfo['today_d_value'] = $d_value - $today_d_value;

		$info = [
	            "status"  => 200,
	            "info"    =>"success",
	            'data'    => $buildinginfo
	    	];
	    return response()->json($info);
	}

	public function energy_now(){
		$energyData = new Energydata();
		$data = $energyData->join('equipment', 'equipment.id', '=', 'energydatas.eiid')
						   ->orderBy('etime','DESC')->take(15)->get();

		foreach ($data as $key => $value) {
			$all_data[$key] = $value['attributes'];
		}
		$info = [
	            "status"  => 200,
	            "info"    =>"success",
	            'data'    => $all_data
	    	];
	    return response()->json($info);
	}
}
