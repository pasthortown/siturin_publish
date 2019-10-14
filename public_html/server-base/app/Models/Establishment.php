<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'ruc_code_id','commercially_known_name','address_main_street','address_map_latitude','address_map_longitude','url_web','as_turistic_register_date','address_reference','contact_user_id','address_secondary_street','address_number','franchise_chain_name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Ruc()
    {
       return $this->hasOne('App\Ruc');
    }

    function PreviewRegisterCodes()
    {
       return $this->belongsToMany('App\PreviewRegisterCode')->withTimestamps();
    }

    function Languages()
    {
       return $this->belongsToMany('App\Language')->withTimestamps();
    }

    function Ubication()
    {
       return $this->hasOne('App\Ubication');
    }

    function Workers()
    {
       return $this->belongsToMany('App\Worker')->withTimestamps();
    }

    function EstablishmentPropertyType()
    {
       return $this->hasOne('App\EstablishmentPropertyType');
    }

    function EstablishmentPicture()
    {
       return $this->belongsTo('App\EstablishmentPicture');
    }

    function EstablishmentState()
    {
       return $this->belongsTo('App\EstablishmentState');
    }

    function EstablishmentCertifications()
    {
       return $this->belongsToMany('App\EstablishmentCertification')->withTimestamps();
    }

    function RucNameType()
    {
       return $this->hasOne('App\RucNameType');
    }

}