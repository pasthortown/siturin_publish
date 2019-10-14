<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','email','password','api_token','address','address_map_latitude','address_map_longitude','main_phone_number','secondary_phone_number','identification','ruc'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       'password','api_token',
    ];

    function ProfilePicture()
    {
       return $this->belongsTo('App\ProfilePicture');
    }

}