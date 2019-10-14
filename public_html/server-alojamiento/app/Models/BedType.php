<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BedType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Bed()
    {
       return $this->belongsTo('App\Bed');
    }

    function RegisterType()
    {
       return $this->hasOne('App\RegisterType');
    }

}