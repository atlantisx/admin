<?php namespace Atlantis\Auth;
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

use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class ServiceProvider extends BaseServiceProvider {

    /**
     * Registering Service Provider
     * @return void
     */
    public function register(){

    }


    public function registerServiceAuth(){
        $this->app['atlantis.auth'] = $this->app->share(function($app){
            return new Environment($app['files'],$app['config']);
        });
    }


    /**
     * SP Provides
     * @return array
     */
    public function provides(){
        return ['atlantis.auth'];
    }

}