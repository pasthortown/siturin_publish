<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplementaryServiceType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','code','father_code','description',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Registers()
    {
       return $this->belongsToMany('App\Register')->withTimestamps();
    }

}