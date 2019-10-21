<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * login
     */
    public function login(Request $request){
        /**
         * validate
         */
        $this->validate($request,[
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|string|max:255'
        ]);

        /**
         * 
         * get user
         */
        $user = User::where('email',$request->input('email'))->first();
        
        /**
         * check user
         */
        if(!$user) return $this->resp('error','Email Wrong, User Not Found',404);
        if(!Hash::check($request->input('password'),$user->password)) 
        return $this->resp('error', 'Password Wrong',404); 

        /**
         * create apiToken and update apiToken
         */
        $apiToken = base64_encode(uniqid().uniqid().uniqid().uniqid());
        $user->update([
            'api_token' => $apiToken,
        ]);

        /**
         * data response
         */
        $data = [
            'message'     => 'susscess',
            'Token'       => 'Bearer '.$apiToken
        ];

        /**
         * response
         */
        return $this->resp(null,$data,201);
        
    }

    /**
     * register
     */
    public function register(Request $request){
        /**
         * validate
         */
        $this->validate($request,[
            'name'      =>'required|string|max:100',
            'email'     =>'required|unique:users,email',
            'password'  =>'required|string|min:6,confirmed'
        ]);

        /**
         * hash input password
         * create api token
         */
        $password = Hash::make($request->input('password'));
        $apiToken = base64_encode(uniqid().uniqid().uniqid().uniqid());

        /**
         * save user 
         */
        User::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => $password,
            'api_token' => $apiToken
        ]);

        /**
         * create data response
         */
        $data = [
            'message'     => 'Register Success',
            'Token'       => 'Bearer '.$apiToken
        ];

         /**
         * response
         */
        return $this->resp(null,$data,201);
    }

}
