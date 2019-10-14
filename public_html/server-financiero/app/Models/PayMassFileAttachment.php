<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayMassFileAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'pay_mass_file_attachment_file_type','pay_mass_file_attachment_file_name','pay_mass_file_attachment_file','date',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

}