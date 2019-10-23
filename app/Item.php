<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description','is_completed','completed_at','due','urgency','updated_by','created_by','assignee_id','task_id'
    ];
    
    protected $casts = [
        'due'           => 'datetime:Y-m-d',
        'completed_at'  => 'datetime:Y-m-d',
    ];

    public function template(){
        return $this->belongsTo(Template::class,'task_id');
    }
}