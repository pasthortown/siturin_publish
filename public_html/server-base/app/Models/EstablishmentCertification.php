<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstablishmentCertification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function EstablishmentCertificationType()
    {
       return $this->hasOne('App\EstablishmentCertificationType');
    }

    function EstablishmentCertificationAttachment()
    {
       return $this->hasOne('App\EstablishmentCertificationAttachment');
    }

    function Establishments()
    {
       return $this->belongsToMany('App\Establishment')->withTimestamps();
    }

}