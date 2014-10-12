<?php namespace Atlantis\Admin;
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


use Atlantis\Admin\Config\Factory as ConfigFactory;
use Atlantis\Admin\Fields\Factory as FieldFactory;
use Atlantis\Admin\DataTable\Columns\Factory as ColumnFactory;
use Atlantis\Admin\Actions\Factory as ActionFactory;
use Atlantis\Admin\DataTable\DataTable;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator as LValidator;
use Illuminate\Foundation\AliasLoader;


class AdminServiceProvider extends ServiceProvider {

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
	public function boot()
	{
		$this->package('atlantis/admin');

		#i: Set the locale
		$this->setLocale();

        $this->registerServiceAdminValidator();
        $this->registerServiceAdminFactory();
        $this->registerServiceAdminDataTable();
        $this->registerServiceModules();

		#i: Include our filters, view composers, and routes
		include __DIR__.'/../../filters.php';
		include __DIR__.'/../../views.php';
		include __DIR__.'/../../routes.php';

		$this->app['events']->fire('admin.ready');
	}


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register(){
        $this->registerDependencies();
        $this->registerAlias();
    }


    /**
     *
     *
     * @return void
     */
    public function registerDependencies(){
        $this->app->register('Cartalyst\Sentry\SentryServiceProvider');
        $this->app->register('Zizaco\Entrust\EntrustServiceProvider');
        //$this->app->register('Menu\MenuServiceProvider');
        $this->app->register('Baum\BaumServiceProvider');
        $this->app->register('Thomaswelton\LaravelGravatar\LaravelGravatarServiceProvider');
    }


    /**
     *
     *
     * @return void
     */
    public function registerAlias(){
        $alias = AliasLoader::getInstance();

        $alias->alias('Sentry','Cartalyst\Sentry\Facades\Laravel\Sentry');
        $alias->alias('Entrust','Zizaco\Entrust\EntrustFacade');
        $alias->alias('Gravatar','Thomaswelton\LaravelGravatar\Facades\Gravatar');
    }


    /**
     *
     *
     * @return void
     */
    public function registerServiceAdminValidator(){
        $this->app['admin_validator'] = $this->app->share(function($app)
        {
            //get the original validator class so we can set it back after creating our own
            $originalValidator = LValidator::make(array(), array());
            $originalValidatorClass = get_class($originalValidator);

            //temporarily override the core resolver
            LValidator::resolver(function($translator, $data, $rules, $messages) use ($app)
            {
                $validator = new Validator($translator, $data, $rules, $messages);
                $validator->setUrlInstance($app->make('url'));
                return $validator;
            });

            //grab our validator instance
            $validator = LValidator::make(array(), array());

            //set the validator resolver back to the original validator
            LValidator::resolver(function($translator, $data, $rules, $messages) use ($originalValidatorClass)
            {
                return new $originalValidatorClass($translator, $data, $rules, $messages);
            });

            //return our validator instance
            return $validator;
        });
    }


    /**
     *
     *
     * @return void
     */
    public function registerServiceAdminFactory(){
        $this->app['admin_config_factory'] = $this->app->share(function($app)
        {
            return new ConfigFactory($app->make('admin_validator'), Config::get('admin::admin'));
        });

        $this->app['admin_field_factory'] = $this->app->share(function($app)
        {
            return new FieldFactory($app->make('admin_validator'), $app->make('itemconfig'), $app->make('db'));
        });

        $this->app['admin_column_factory'] = $this->app->share(function($app)
        {
            return new ColumnFactory($app->make('admin_validator'), $app->make('itemconfig'), $app->make('db'));
        });

        $this->app['admin_action_factory'] = $this->app->share(function($app)
        {
            return new ActionFactory($app->make('admin_validator'), $app->make('itemconfig'), $app->make('db'));
        });
    }


    /**
     *
     *
     * @return void
     */
    public function registerServiceAdminDataTable(){
        $this->app['admin_datatable'] = $this->app->share(function($app)
        {
            $dataTable = new DataTable($app->make('itemconfig'), $app->make('admin_column_factory'), $app->make('admin_field_factory'));
            $dataTable->setRowsPerPage($app->make('session.store'), Config::get('admin::admin.global_rows_per_page'));

            return $dataTable;
        });
    }


    /**
     *
     */
    public function registerServiceModules(){
        //@info Enable internal Api
        $this->app['atlantis.module']->extend('users.api','Modules\Users\Api\UsersApiServiceProvider');
    }


	/**
	 * Sets the locale if it exists in the session and also exists in the locales option
	 *
	 * @return void
	 */
	public function setLocale()
	{
		if ($locale = $this->app->session->get('admin_locale'))
		{
			$this->app->setLocale($locale);
		}
	}


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['atlantis.admin'];
    }

}
