<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StateDeclaration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'justification','moment',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Declaration()
    {
       return $this->hasOne('App\Declaration');
    }

    function State()
    {
       return $this->hasOne('App\State');
    }

}