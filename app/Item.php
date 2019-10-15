<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'description','is_complated','completed_at','due','urgency','updated_by','assignee_by','task_id'
    ];

    public function template(){
        return $this->belongsTo(Template::class,'task_id');
    }
}