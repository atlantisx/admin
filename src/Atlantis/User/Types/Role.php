<?php namespace Atlantis\User\Types;


class Role {

    public $name;
    public $description;
    public $home_path;

    public function __construct($role){
        $user_home = \Config::get('admin::admin.user_home','home');

        $this->name = $role;
        $this->description = studly_case(str_singular($role));
        $this->home_path = url($role . '/' . $user_home);
    }

}