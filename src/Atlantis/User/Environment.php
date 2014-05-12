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



class Environment {
    protected $app;
    protected $auth;
    protected $realm;


    public function __construct($app, $auth, $realm){
        $this->app = $app;
        $this->auth = $auth;
        $this->realm = $realm;
    }


    public function boot(){
        #i: Get controller provider
        $controller = $this->app['atlantis.controller'];

        $controller->extend('auth', $this->auth);
        $controller->extend('realm', $this->realm);

        #i: Extending controller
        if( $this->auth->getUser() ){
            $controller->extend('user', $this->auth->getUser());
            $controller->extend('superuser',  $this->auth->findAllUsersWithAccess('superuser')[0]);
            $controller->extend('user_realm', $this->realm->current()->name);
        }
    }

}