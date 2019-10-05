<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'domain','description','is_complated','completed_at','updated_by','due','urgency'
    ];
}