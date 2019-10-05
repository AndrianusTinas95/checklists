<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'loggable_type','loggable_id','action','kwuid','value'
    ];
}