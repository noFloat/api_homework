<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;

class Equipment extends Model
{
	public function building()
    {
        return $this->belongsTo(Building::class, 'building_id', 'id');
    }


}