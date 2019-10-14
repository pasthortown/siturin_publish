<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TariffType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','code','father_code','is_reference','factor',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Tariff()
    {
       return $this->belongsTo('App\Tariff');
    }

}