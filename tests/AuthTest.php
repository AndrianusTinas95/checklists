<?php

use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $user=User::get()->random();
        $data=[
            'email'     => $user->email,
            'password'  => 'rahasiaku'
        ];
        $this->post('/login',$data,[]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'message',
            'Token'
        ]);
       
    }

    public function testRegister(){
        $data = factory(User::class)->make()->toArray();
        $data['password']='rahasiaku';
        $data['password_confirm']='rahasiaku';
        
        $this->post('/register',$data,[]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'message',
            'Token'
        ]);
    }
}
