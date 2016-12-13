<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;

class Building extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    public function equipments(){
        return $this->hasMany(Equipment::class, 'building_id', 'id');
    }
    // *
    //  * The attributes that should be hidden for arrays.
    //  *
    //  * @var array
     
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];
}
