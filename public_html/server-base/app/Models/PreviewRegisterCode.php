<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreviewRegisterCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'code',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Establishments()
    {
       return $this->belongsToMany('App\Establishment')->withTimestamps();
    }

    function SystemName()
    {
       return $this->hasOne('App\SystemName');
    }

}