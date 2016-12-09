<?php

namespace App\Http\Controllers\Content;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Pipeline\Pipeline;
use App\Movie;

class IndexController extends Controller
{
    public function index()
    {
        $query = Movie::query();

        $data = $query->orderBy('time','desc')->paginate(3);
        return view('movie.index')->with('data', $data);
    }

    public function search(Request $request){
    	$goal_title = $request->input();
    	$goal_title = $goal_title['goal'];
    	$query = Movie::query();

        $data = $query->where('name','like',"%$goal_title%")->orderBy('time','desc')->paginate(6);
        $data->setPath("movies?goal=$goal_title");
        return view('movie.list')->with('data', $data);

    }

    public function content(Request $request){
    	$goal_id = $request->input();
    	$goal_id = $goal_id['id'];
    	$query = Movie::query();

        $data = $query->where('id',$goal_id)->get();
        return view('movie.content')->with('data', $data);

    }
}
