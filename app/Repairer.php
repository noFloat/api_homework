<?php

namespace App;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Repairer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','created_at','updated_at'
    ];
    public function checkExist($phone){
        $goal = Repairer::where('phone','=',$phone)->get();

        if(empty($goal[0]->id)){
            return false;
        }else{
            return true;
        }
    }
    // *
    //  * The attributes that should be hidden for arrays.
    //  *
    //  * @var array
     
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];
}
