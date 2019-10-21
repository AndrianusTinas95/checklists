<?php

namespace App\Traits;

use App\User;
/**
 * login for test
 */
trait LoginTrait
{
    public function token(){
        $user=User::find(1);
        return $user->api_token;
    }
}
 