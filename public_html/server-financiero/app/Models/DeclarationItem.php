<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeclarationItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','description','factor','year','tax_payer_type_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function DeclarationItemCategory()
    {
       return $this->hasOne('App\DeclarationItemCategory');
    }

    function DeclarationItemValue()
    {
       return $this->belongsTo('App\DeclarationItemValue');
    }

}