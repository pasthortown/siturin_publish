<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplementaryServiceFood extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'quantity_tables','quantity_chairs',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function ComplementaryServiceFoodType()
    {
       return $this->hasOne('App\ComplementaryServiceFoodType');
    }

    function Registers()
    {
       return $this->belongsToMany('App\Register')->withTimestamps();
    }

}