<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'count',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Gender()
    {
       return $this->hasOne('App\Gender');
    }

    function WorkerGroup()
    {
       return $this->hasOne('App\WorkerGroup');
    }

    function Establishments()
    {
       return $this->belongsToMany('App\Establishment')->withTimestamps();
    }

}