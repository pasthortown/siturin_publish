<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalStateAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'approval_state_attachment_file_type','approval_state_attachment_file_name','approval_state_attachment_file',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function ApprovalState()
    {
       return $this->hasOne('App\ApprovalState');
    }

}