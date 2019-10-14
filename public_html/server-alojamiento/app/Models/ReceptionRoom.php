<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceptionRoom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'quantity','fullfill',
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

}