<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'object_domain','description','is_completed','completed_at','updated_by','due','urgency','object_id'
    ];

    /**
     * timestamps
     */
    protected $casts = [
        'due'           => 'datetime:Y-m-d',
        'completed_at'  => 'datetime:Y-m-d',
    ];
    public function template(){
        return $this->belongsTo(Template::class,'object_id');
    }

}