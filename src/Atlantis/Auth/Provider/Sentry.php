<?php namespace Atlantis\Auth\Provider;

use Cartalyst\Sentry\Sentry as SentryProvider;


class Sentry implements AuthDriverInterface{

    public function register($array){
    }


    public function authenticate($array){

    }

    public function login(){
    }


    public function logout(){
        SentryProvider::logout();
    }


    public function findUserByLogin($login){

    }


    public function findUserActivationCode($code){

    }
}