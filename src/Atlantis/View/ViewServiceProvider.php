<?php namespace Atlantis\View;
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
use Illuminate\Support\Facades\Config;
use Illuminate\View\Engines\CompilerEngine;
use Atlantis\View\Portlet\Eloquent\Provider as PortletProvider;
use Atlantis\View\Facades\Laravel\Portlet;
use Atlantis\View\Compilers\BladeCompiler;
use Atlantis\View\Compilers\Compiler;
use Illuminate\View\View;


class ViewServiceProvider extends \Illuminate\View\ViewServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(){
        $this->registerBladeExtensions();

        parent::boot();
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register(){
        $this->registerBladeCustomEngine();

        $this->registerPortletProvider();

        $this->registerViewProvider();
    }


    /**
     *
     *
     * @return void
     */
    protected function registerBladeCustomEngine(){
        $app = $this->app;

        //[i] Get engine resolver
        $resolver = $this->app['view.engine.resolver'];

        //[i] Load custom Blade engine
        $resolver->register('blade',function() use($app){
            $cache = $app['path.storage'].'/views';

            $compiler = new BladeCompiler($app['files'], $cache);

            return new CompilerEngine($compiler, $app['files']);
        });
    }

    /**
     *
     *
     * @return void
     */
    protected function registerBladeExtensions(){

        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->extend(function($value,$compiler) {
            //[i] Portlet matcher
            $matcher = $compiler->createMatcher('portlet');

            //[i] Get blade tag value
            if( preg_match_all($matcher,$value,$matched) ){
                //[i] Removed bracket+quote from value
                $name = preg_replace("([(')])", "", ($matched[2][0]));

                //[i] Get portlet template
                $template = Portlet::findByName($name)->template();

                return preg_replace($matcher, $template, $value);
            }else{
                return $value;
            }
        });
    }


    /**
     *
     *
     * @return void
     */
    protected function registerPortletProvider(){
        \App::bind('view.portlet', function(){
            return new PortletProvider();
        });
    }


    /**
     *
     *
     * @return void
     */
    protected function registerViewProvider(){
        $this->app['view'] = $this->app->share(function($app){

            //[i] Getting resolver & finder
            $resolver = $app['view.engine.resolver'];
            $finder = $app['view.finder'];

            //[i] Get new instance of Environment
            $env = new Environment(
                $resolver,
                $finder,
                $app['events']
            );

            //[i] Setting container for this view env
            $env->setContainer($app);
            $env->share('app',$app);

            return $env;
        });
    }


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('view');
	}
}
