<?php namespace Atlantis\Admin\Api\V1;


class UserController extends \BaseController{

    public function index(){
        $users = \User::all();
        $users->load('profile');

        return $users;
    }

    public function show($id){
        //[i] Search user
        $user = \User::find($id);

        //[i] Return user
        if($user){
            $user->load('profile');
            return $user;
        }

        return \Response::json(array('Error in query'),400);
    }

}