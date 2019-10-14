<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterRequisite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'fullfill','value',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Requisite()
    {
       return $this->hasOne('App\Requisite');
    }

    function Register()
    {
       return $this->hasOne('App\Register');
    }

}