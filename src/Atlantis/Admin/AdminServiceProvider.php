<?php namespace Atlantis\Admin;

use Atlantis\Admin\Config\Factory as ConfigFactory;
use Atlantis\Admin\Fields\Factory as FieldFactory;
use Atlantis\Admin\DataTable\Columns\Factory as ColumnFactory;
use Atlantis\Admin\Actions\Factory as ActionFactory;
use Atlantis\Admin\DataTable\DataTable;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator as LValidator;

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

		//set the locale
		$this->setLocale();

		//the admin validator
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

		//set up the shared instances
		$this->app['admin_config_factory'] = $this->app->share(function($app)
		{
			return new ConfigFactory($app->make('admin_validator'), Config::get('admin::admin'));
		});

		$this->app['admin_field_factory'] = $this->app->share(function($app)
		{
			return new FieldFactory($app->make('admin_validator'), $app->make('itemconfig'), $app->make('db'));
		});

		$this->app['admin_datatable'] = $this->app->share(function($app)
		{
			$dataTable = new DataTable($app->make('itemconfig'), $app->make('admin_column_factory'), $app->make('admin_field_factory'));
			$dataTable->setRowsPerPage($app->make('session.store'), Config::get('admin::admin.global_rows_per_page'));

			return $dataTable;
		});

		$this->app['admin_column_factory'] = $this->app->share(function($app)
		{
			return new ColumnFactory($app->make('admin_validator'), $app->make('itemconfig'), $app->make('db'));
		});

		$this->app['admin_action_factory'] = $this->app->share(function($app)
		{
			return new ActionFactory($app->make('admin_validator'), $app->make('itemconfig'), $app->make('db'));
		});

		$this->app['admin_menu'] = $this->app->share(function($app)
		{
			return new Menu($app->make('config'), $app->make('admin_config_factory'));
		});

		//include our filters, view composers, and routes
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
	public function register(){	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
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

}
