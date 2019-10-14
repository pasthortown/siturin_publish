<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'quantity',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function BedType()
    {
       return $this->hasOne('App\BedType');
    }

    function Capacities()
    {
       return $this->belongsToMany('App\Capacity')->withTimestamps();
    }

}