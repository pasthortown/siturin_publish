<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'price','year','state_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function TariffType()
    {
       return $this->hasOne('App\TariffType');
    }

    function CapacityType()
    {
       return $this->hasOne('App\CapacityType');
    }

    function Register()
    {
       return $this->hasOne('App\Register');
    }

}