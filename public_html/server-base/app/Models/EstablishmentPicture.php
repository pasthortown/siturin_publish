<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstablishmentPicture extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'establishment_picture_file_type','establishment_picture_file_name','establishment_picture_file',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Establishment()
    {
       return $this->hasOne('App\Establishment');
    }

}