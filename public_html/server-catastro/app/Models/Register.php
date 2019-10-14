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
       'ruc','comercial_name','register_code','as_turistic_date','activity','category','classification','legal_representant_name','legal_representant_identification','establishment_property_type','organization_type','ubication_main','ubication_sencond','ubication_third','address','main_phone_number','secondary_phone_number','email','web','system_source','georeference_latitude','georeference_longitude','establishment_ruc_code','max_capacity','max_areas','total_male','total_female','ruc_state','max_beds','establishment_state',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

}
