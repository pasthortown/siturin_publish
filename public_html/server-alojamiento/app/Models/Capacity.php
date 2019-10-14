<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capacity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'quantity','max_beds','max_spaces',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Registers()
    {
       return $this->belongsToMany('App\Register')->withTimestamps();
    }

    function Beds()
    {
       return $this->belongsToMany('App\Bed')->withTimestamps();
    }

    function CapacityType()
    {
       return $this->hasOne('App\CapacityType');
    }

    function CapacityPicture()
    {
       return $this->belongsTo('App\CapacityPicture');
    }

}