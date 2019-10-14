<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'code','autorized_complementary_capacities','establishment_id','autorized_complementary_food_capacities',
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

    function RegisterState()
    {
       return $this->belongsTo('App\RegisterState');
    }

    function RegisterRequisite()
    {
       return $this->belongsTo('App\RegisterRequisite');
    }

    function Capacities()
    {
       return $this->belongsToMany('App\Capacity')->withTimestamps();
    }

    function ApprovalState()
    {
       return $this->belongsTo('App\ApprovalState');
    }

    function Tariff()
    {
       return $this->belongsTo('App\Tariff');
    }

    function RegisterProcedure()
    {
       return $this->belongsTo('App\RegisterProcedure');
    }

    function PropertyTitleAttachment()
    {
       return $this->belongsTo('App\PropertyTitleAttachment');
    }

    function AuthorizationAttachment()
    {
       return $this->belongsTo('App\AuthorizationAttachment');
    }

}