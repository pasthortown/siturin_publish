<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterType extends Model
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

    function Register()
    {
       return $this->belongsTo('App\Register');
    }

    function Requisite()
    {
       return $this->belongsTo('App\Requisite');
    }

    function CapacityType()
    {
       return $this->belongsTo('App\CapacityType');
    }

}