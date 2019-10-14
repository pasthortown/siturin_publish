<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeclarationItemCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','description','year','tax_payer_type_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function DeclarationItem()
    {
       return $this->belongsTo('App\DeclarationItem');
    }

}