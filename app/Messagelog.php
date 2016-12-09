<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Messagelog extends Model
{
	protected $fillable = ['phone', 'verify', 'created_at','updated_at','state'];

}