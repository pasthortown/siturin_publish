<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountRol extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','description','code','father_code',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function AccountRolAssigment()
    {
       return $this->belongsTo('App\AccountRolAssigment');
    }

    function AuthLocation()
    {
       return $this->belongsTo('App\AuthLocation');
    }

}