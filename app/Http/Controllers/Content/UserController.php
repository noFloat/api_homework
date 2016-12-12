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
		$user->checkExist($request->input('phone'));
		exit;
	}
}
