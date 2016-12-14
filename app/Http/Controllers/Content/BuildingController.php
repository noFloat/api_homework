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
use DB;

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

		$before_d_value = 0;
		$before_e_value = 0;
		foreach ($data_date as $key => $value) {
			$before_d_value += $value['attributes']['before_dvalue'];
			$before_e_value += $value['attributes']['before_evalue'];
		}
		$buildinginfo['before_e_value'] = $before_e_value;
		$buildinginfo['before_d_value'] = $before_d_value;

		$tody_data = Daydata::whereIn('eiid',$equipmentId)->orderBy('etime','DESC')
		->skip(14)->take(14)->get();

		$today_d_value = 0;
		$today_e_value = 0;
		foreach ($tody_data as $key => $value) {

			$today_d_value += $value['attributes']['dvalue'];
			$today_e_value += $value['attributes']['evalue'];
		}
		$buildinginfo['today_e_value'] = $e_value-$today_e_value;
		$buildinginfo['today_d_value'] = $d_value-$today_d_value;

		$info = [
	            "status"  => 200,
	            "info"    =>"success",
	            'data'    => $buildinginfo
	    	];
	    return response()->json($info);
	}

	public function searchGoal(Request $request){
		$data_date = DB::select("select truncate((g1.evalue-g2.evalue),1) as evalue from energydatas as g1 INNER JOIN
    energydatas g2 ON g2.edId = g1.edId-13 where g1.edId>40000 order by g1.etime DESC limit 336");
		$mid_array['energy_all'] ='';
		$mid_array['energy_time'] = 0;
		$data = array_fill(0,24,$mid_array);;
		for($i=0;$i<24;$i++){
			for($j=0;$j<14;$j++){
				$data[$i]['energy_time'] = $i;
				$data[$i]['energy_all'] = $data[$i]['energy_all'].(string)$data_date[$i*14+$j]->evalue.','; 
			}
		}
		$info = [
	            "status"  => 200,
	            "info"    =>"success",
	            'data'    => $data
	    	];
	    return response()->json($info);
	}

	public function monitor(Request $request){
		$total = $request->input('num');
		$all_data =[];
		for($i = 0;$i<$total;$i++){
			$array = [
				"waterflow"=>rand(0,40)/10,
	            "host_power"=>rand(0,50)/10,
	            "cooling_capacity"=>rand(0,20000)/10,
	            "cop"=>rand(0,2000)/10,
	            "tower1_power"=>rand(0,400)/10,
	            "tower2_power"=>rand(0,550)/10,
	            "pump1_power"=>rand(0,300)/10,
	            "pump2_power"=>rand(0,500)/10
			];
			array_push($all_data,$array);
		}
		var_dump($all_data);
	}

	public function energy_now(){
		$energyData = new Energydata();
		$random = rand (1,10);
		$data = $energyData->join('equipment', 'equipment.id', '=', 'energydatas.eiid')
						   ->orderBy('energydatas.etime','DESC')->skip($random*14)->take(14)->get();
		foreach ($data as $key => $value) {
			$all_data[$key] = $value['attributes'];
		}

		$flag=array();
		foreach($all_data as $key=>$value){
		    	$id[$key] = $value['id'];
    			$eiId[$key] = $value['eiId'];
		    }
		array_multisort($eiId,SORT_NUMERIC,SORT_ASC,$id,SORT_STRING,SORT_ASC,$all_data);
		$info = [
	            "status"  => 200,
	            "info"    =>"success",
	            'data'    => $all_data
	    	];
	    return response()->json($info);
	}
}
