<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FloorAuthorizationCertificate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'floor_authorization_certificate_file_type','floor_authorization_certificate_file_name','floor_authorization_certificate_file','register_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

}