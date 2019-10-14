<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountRolAssigment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function AccountRol()
    {
       return $this->hasOne('App\AccountRol');
    }

    function User()
    {
       return $this->hasOne('App\User');
    }

}