<?php namespace Atlantis\Auth\Provider;

use Illuminate\Support\Facades\Auth;


class Native implements AuthDriverInterface{

    public function register($array){
    }


    public function authenticate($array,$remember){
        return Auth::attempt($array,$remember);
    }

    public function login($array){
    }


    public function logout(){
        Auth::logout();
    }


    public function findUserByLogin($login){
    }


    public function findUserActivationCode($code){
    }


    public function check(){
        return Auth::check();
    }
}