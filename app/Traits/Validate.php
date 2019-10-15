<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait Validate{
    
    public function valid(Request $request){
        $this->validate($request,[
            
        ]);
    }
}