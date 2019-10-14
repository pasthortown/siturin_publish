<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapacityType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','description','group_quantity','is_island','spaces','editable_groups','editable_spaces',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Tariff()
    {
       return $this->belongsTo('App\Tariff');
    }

    function Capacity()
    {
       return $this->belongsTo('App\Capacity');
    }

    function RegisterType()
    {
       return $this->hasOne('App\RegisterType');
    }

}