<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','code','father_code','gmap_reference_latitude','gmap_reference_longitude','acronym',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Establishment()
    {
       return $this->belongsTo('App\Establishment');
    }

}