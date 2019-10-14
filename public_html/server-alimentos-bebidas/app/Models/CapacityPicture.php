<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapacityPicture extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'capacity_picture_file_type','capacity_picture_file_name','capacity_picture_file',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Capacity()
    {
       return $this->hasOne('App\Capacity');
    }

}