<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterProcedure extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'date',
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
       return $this->hasOne('App\Register');
    }

    function ProcedureJustification()
    {
       return $this->hasOne('App\ProcedureJustification');
    }

}