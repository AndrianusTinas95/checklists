<?php

namespace App\Traits;

use Illuminate\Http\Request;

/**
 * Response data
 */
trait Response
{
    /**
     * response singgle data
     */
    public function single($type,$id,$data,$url){
        return[
            'data' => [
                'type'          => $type,
                'id'            => (int)$id,
                'attributes'    => $data,
                'links'         =>[
                    'self'      => $url
                ] 
            ]
        ];
       
    }

    /**
     * response general
     * type null response success
     * type error respionse error
     */
    public function resp($type = null ,$data,$status){
        return  $type == 'error' ? 
                $this->respError($data,$status) : $this->respSuccess($data,$status);
    }
    /**
     * response error
     */
    public function respError($error,$status){
        return response()->json([
            "status" => $status,
            "error"  => $error
        ],$status);
    }

    /**
     * response success
     */
    public function respSuccess($data,$status){
        return response()->json($data,$status);
    }
}
