<?php

namespace App\Helpers;

class Curl 
{

    public static function zone($zone){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://worldtimeapi.org/api/timezone/".$zone,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: authorization"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $resp = [
                'susccess' => false,
                'data'     =>  json_decode($err,true)
            ];
        } else {
            $resp = [
                'susccess' => true,
                'data'     =>  json_decode($response,true)
            ];
        }
        return $resp;
    }
}