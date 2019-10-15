<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'domain','description','is_complated','completed_at','updated_by','due','urgency'
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