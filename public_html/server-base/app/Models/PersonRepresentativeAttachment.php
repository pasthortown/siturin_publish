<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonRepresentativeAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'person_representative_attachment_file_type','person_representative_attachment_file_name','person_representative_attachment_file','ruc','assignment_date',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function PersonRepresentative()
    {
       return $this->hasOne('App\PersonRepresentative');
    }

}