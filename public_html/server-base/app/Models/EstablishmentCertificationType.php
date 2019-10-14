<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstablishmentCertificationType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','code','father_code',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function EstablishmentCertification()
    {
       return $this->belongsTo('App\EstablishmentCertification');
    }

}