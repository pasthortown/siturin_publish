<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstablishmentCertificationAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'establishment_certification_attachment_file_type','establishment_certification_attachment_file_name','establishment_certification_attachment_file',
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