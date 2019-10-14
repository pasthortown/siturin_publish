<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkerGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','description','is_max',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Worker()
    {
       return $this->belongsTo('App\Worker');
    }

}