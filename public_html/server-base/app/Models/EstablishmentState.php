<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstablishmentState extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'justification',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function State()
    {
       return $this->hasOne('App\State');
    }

    function Establishment()
    {
       return $this->hasOne('App\Establishment');
    }

}