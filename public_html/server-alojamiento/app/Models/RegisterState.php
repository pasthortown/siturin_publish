<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterState extends Model
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

    function Register()
    {
       return $this->hasOne('App\Register');
    }

    function State()
    {
       return $this->hasOne('App\State');
    }

}