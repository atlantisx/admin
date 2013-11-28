<?php namespace Atlantis;
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


use Illuminate\Support\ServiceProvider;


class AtlantisProvider extends ServiceProvider{

    protected $services = array(
        'admin',
        'user',
        'view'
    );

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(){
        $this->package('atlantis/admin'); //!! nematix/atlantis

        $this->observeEvents();
    }


    /**
     * Register service provider
     *
     * @return void
     */
    public function register(){
        $this->registerUserProvider();

        $this->app['atlantis'] = $this->app->share(function($app){
            $app['atlantis.loaded'] = true;

            return new Atlantis(
                null,
                $app['atlantis.user'],
                null
            );
        });
    }


    public function registerUserProvider(){
        if( !isset($app['atlantis.user']) ){
            $this->app['atlantis.user'] = $this->app->share(function($app){
                return new \Atlantis\User\Environment;
            });
        }
    }


    /**
     * Sets up event observations required by Atlantis.
     *
     * @return void
     */
    protected function observeEvents()
    {
        // Set the cookie after the app runs
        $app = $this->app;
        $this->app->after(function($request, $response) use ($app)
        {
            if (isset($app['atlantis.loaded']) and $app['atlantis.loaded'] == true)
            {
                // Anything to do when app loaded
            }
        });
    }
}