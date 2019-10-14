<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalStateReport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'background','actions_done','conclution','recomendation',
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