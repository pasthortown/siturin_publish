<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requisite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','description','father_code','to_approve','mandatory','type','params',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function RegisterType()
    {
       return $this->hasOne('App\RegisterType');
    }

    function RegisterRequisite()
    {
       return $this->belongsTo('App\RegisterRequisite');
    }

}