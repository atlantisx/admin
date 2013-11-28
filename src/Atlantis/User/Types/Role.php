<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Azri
 * Date: 11/26/13
 * Time: 1:03 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Atlantis\User\Types;


class Role {

    public $name;
    public $description;
    public $homePath;

    public function __construct($role){
        $user_home = \Config::get('admin::admin.user_home','home');

        $this->name = $role;
        $this->description = studly_case(str_singular($role));
        $this->homePath = url($role . '/' . $user_home);
    }

}