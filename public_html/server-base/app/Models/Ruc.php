<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruc extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'number','baised_accounting','contact_user_id','owner_name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function TaxPayerType()
    {
       return $this->hasOne('App\TaxPayerType');
    }

    function Establishment()
    {
       return $this->belongsTo('App\Establishment');
    }

    function GroupGiven()
    {
       return $this->belongsTo('App\GroupGiven');
    }

}