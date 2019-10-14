<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeclarationAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'declaration_attachment_file_type','declaration_attachment_file_name','declaration_attachment_file',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Declaration()
    {
       return $this->hasOne('App\Declaration');
    }

}