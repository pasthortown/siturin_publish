<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyTitleAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'property_title_attachment_file_type','property_title_attachment_file_name','property_title_attachment_file',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Register()
    {
       return $this->hasOne('App\Register');
    }

}