<?php
namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait Validate{
    use Response;

    public function listValidate($request,$table,$selected){
        $col = $request->$selected;
        if($selected=='fields' && $col){
            $fields= explode(',',$request->fields);
            foreach ($fields as $val) {
                if(!Schema::hasColumn($table, $val)) 
                return $this->resp('error',"$selected $val Not Found " ,402);
            }
        }else{
            return $col && !Schema::hasColumn($table, $col) ? 
            $this->resp('error',"$selected $col Not Found " ,402) : null;
        }
    }
}