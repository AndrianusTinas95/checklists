<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function checklist(){
        return $this->hasOne(Checklist::class,'object_id');
    }

    public function items(){
        return $this->hasMany(Item::class,'task_id');
    }
}