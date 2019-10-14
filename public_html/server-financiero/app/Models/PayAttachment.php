<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'pay_attachment_file_type','pay_attachment_file_name','pay_attachment_file',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Pay()
    {
       return $this->hasOne('App\Pay');
    }

}