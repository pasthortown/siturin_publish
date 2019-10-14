<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupGiven extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'register_code',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Ruc()
    {
       return $this->hasOne('App\Ruc');
    }

    function PersonRepresentative()
    {
       return $this->hasOne('App\PersonRepresentative');
    }

    function GroupType()
    {
       return $this->hasOne('App\GroupType');
    }

}