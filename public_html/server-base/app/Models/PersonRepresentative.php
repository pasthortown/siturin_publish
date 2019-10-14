<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonRepresentative extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'identification',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function GroupGiven()
    {
       return $this->belongsTo('App\GroupGiven');
    }

    function PersonRepresentativeAttachment()
    {
       return $this->belongsTo('App\PersonRepresentativeAttachment');
    }

}