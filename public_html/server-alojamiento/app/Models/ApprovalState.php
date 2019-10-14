<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalState extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'value','date_assigment','notes','id_user','date_fullfill',
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

    function Approval()
    {
       return $this->hasOne('App\Approval');
    }

    function ApprovalStateAttachment()
    {
       return $this->belongsTo('App\ApprovalStateAttachment');
    }

    function ApprovalStateReport()
    {
       return $this->belongsTo('App\ApprovalStateReport');
    }

}