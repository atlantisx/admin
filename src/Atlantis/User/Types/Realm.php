<?php namespace Atlantis\User\Types;


class Realm {

    public $name;
    public $description;
    public $home_path;

    public function __construct($realm){
        #i: Home view
        $user_home = \Config::get('admin::admin.user_home','home');

        #i: Realm name
        $this->name = $realm;

        #i: Realm description
        $this->description = studly_case(str_singular($realm));

        if( !\View::exists("$realm.$user_home") ) $realm = \Config::get('admin::admin.user_default_role','user');

        #i: Realm home path
        $this->home_path = url("$realm/$user_home");
    }

}