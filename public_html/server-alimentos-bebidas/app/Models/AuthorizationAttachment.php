<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorizationAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'authorization_attachment_file_type','authorization_attachment_file_name','authorization_attachment_file',
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