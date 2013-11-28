<?php namespace Atlantis\User;
/**
 * Part of the Atlantis package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Atlantis
 * @version    1.0.0
 * @author     Nematix LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 1997 - 2013, Nematix LLC
 * @link       http://nematix.com
 */


use \Illuminate\View\Environment as BaseEnvironment;
use \Atlantis\User\Types\Role;


class Environment extends BaseEnvironment {

    protected $sentry;


    public function __construct(){
        $this->sentry = \App::make('sentry');
    }


    public function getUserRoleById($userId){
        $user_role = $user_default_role = \Config::get('admin::admin.user_default_role','user');

        //[i] Find user based on Id
        $user = $this->sentry->findUserById($userId);

        //[i] Checking user role
        $user_groups = $user->getGroups();
        if( $user_groups->count() == 1 ){
            $group_permission = $user_groups->first()->getPermissions();

            if( count($group_permission) == 1){
                $user_role = key($group_permission);
            }
        }

        return new Role($user_role);
    }

}