<?php

use Atlantis\Admin\ModelHelper;
use Atlantis\Admin\Fields\Field;

//View Composers

//admin index view
View::composer('admin::index', function($view)
{
	//get a model instance that we'll use for constructing stuff
	$config = App::make('itemconfig');
	$fieldFactory = App::make('admin_field_factory');
	$columnFactory = App::make('admin_column_factory');
	$actionFactory = App::make('admin_action_factory');
	$dataTable = App::make('admin_datatable');
	$model = $config->getDataModel();
	$baseUrl = URL::route('admin_dashboard');
	$route = parse_url($baseUrl);

	//add the view fields
	$view->config = $config;
	$view->dataTable = $dataTable;
	$view->primaryKey = $model->getKeyName();
	$view->editFields = $fieldFactory->getEditFields();
	$view->arrayFields = $fieldFactory->getEditFieldsArrays();
	$view->dataModel = $fieldFactory->getDataModel();
	$view->columnModel = $columnFactory->getColumnOptions();
	$view->actions = $actionFactory->getActionsOptions();
	$view->globalActions = $actionFactory->getGlobalActionsOptions();
	$view->actionPermissions = $actionFactory->getActionPermissions();
	$view->filters = $fieldFactory->getFiltersArrays();
	$view->rows = $dataTable->getRows(App::make('db'), $view->filters);
	$view->formWidth = $config->getOption('form_width');
	$view->baseUrl = $baseUrl;
	$view->assetUrl = URL::to('packages/atlantis/admin/');
	$view->route = $route['path'].'/';
	$view->itemId = isset($view->itemId) ? $view->itemId : null;
});


//admin settings view
View::composer('admin::settings', function($view)
{
	$config = App::make('itemconfig');
	$fieldFactory = App::make('admin_field_factory');
	$actionFactory = App::make('admin_action_factory');
	$baseUrl = URL::route('admin_dashboard');
	$route = parse_url($baseUrl);

	//add the view fields
	$view->config = $config;
	$view->editFields = $fieldFactory->getEditFields();
	$view->arrayFields = $fieldFactory->getEditFieldsArrays();
	$view->actions = $actionFactory->getActionsOptions();
	$view->baseUrl = $baseUrl;
	$view->assetUrl = URL::to('packages/atlantis/admin/');
	$view->route = $route['path'].'/';
});


//header view
View::composer(array('admin::partials.header'), function($view)
{
	$view->menu = App::make('admin_menu')->getMenu();
	$view->settingsPrefix = App::make('admin_config_factory')->getSettingsPrefix();
	$view->pagePrefix = App::make('admin_config_factory')->getPagePrefix();
	$view->configType = App::bound('itemconfig') ? App::make('itemconfig')->getType() : false;
});


//the layout view
View::composer(array('admin::layouts.default'), function($view)
{
	//set up the basic asset arrays
	$view->css = array();
	$view->js = array(
		'jquery' => asset('packages/atlantis/admin/js/jquery/jquery-1.8.2.min.js'),
		'jquery-ui' => asset('packages/atlantis/admin/js/jquery/jquery-ui-1.10.3.custom.min.js'),
		'customscroll' => asset('packages/atlantis/admin/js/jquery/customscroll/jquery.customscroll.js'),
	);

	//add the non-custom-page css assets
	if (!$view->page)
	{
		$view->css += array(
			'jquery-ui' => asset('packages/atlantis/admin/css/ui/jquery-ui-1.9.1.custom.min.css'),
			'jquery-ui-timepicker' => asset('packages/atlantis/admin/css/ui/jquery.ui.timepicker.css'),
			'select2' => asset('packages/atlantis/admin/js/jquery/select2/select2.css'),
			'jquery-colorpicker' => asset('packages/atlantis/admin/css/jquery.lw-colorpicker.css'),
		);
	}

	//add the package-wide css assets
	$view->css += array(
		'customscroll' => asset('packages/atlantis/admin/js/jquery/customscroll/customscroll.css'),
		'main' => asset('packages/atlantis/admin/css/main.css'),
	);

	//add the non-custom-page js assets
	if (!$view->page)
	{
		$view->js += array(
			'select2' => asset('packages/atlantis/admin/js/jquery/select2/select2.js'),
			'jquery-ui-timepicker' => asset('packages/atlantis/admin/js/jquery/jquery-ui-timepicker-addon.js'),
			'ckeditor' => asset('packages/atlantis/admin/js/ckeditor/ckeditor.js'),
			'ckeditor-jquery' => asset('packages/atlantis/admin/js/ckeditor/adapters/jquery.js'),
			'markdown' => asset('packages/atlantis/admin/js/markdown.js'),
			'plupload' => asset('packages/atlantis/admin/js/plupload/js/plupload.full.js'),
		);

		//localization js assets
		$locale = Config::get('app.locale');

		if ($locale !== 'en')
		{
			$view->js += array(
				'plupload-l18n' => asset('packages/atlantis/admin/js/plupload/js/i18n/'.$locale.'.js'),
				'timepicker-l18n' => asset('packages/atlantis/admin/js/jquery/localization/jquery-ui-timepicker-'.$locale.'.js'),
				'datepicker-l18n' => asset('packages/atlantis/admin/js/jquery/i18n/jquery.ui.datepicker-'.$locale.'.js'),
			);
		}

		//remaining js assets
		$view->js += array(
			'knockout' => asset('packages/atlantis/admin/js/knockout/knockout-2.2.0.js'),
			'knockout-mapping' => asset('packages/atlantis/admin/js/knockout/knockout.mapping.js'),
			'knockout-notification' => asset('packages/atlantis/admin/js/knockout/KnockoutNotification.knockout.min.js'),
			'knockout-update-data' => asset('packages/atlantis/admin/js/knockout/knockout.updateData.js'),
			'knockout-custom-bindings' => asset('packages/atlantis/admin/js/knockout/custom-bindings.js'),
			'accounting' => asset('packages/atlantis/admin/js/accounting.js'),
			'colorpicker' => asset('packages/atlantis/admin/js/jquery/jquery.lw-colorpicker.min.js'),
			'history' => asset('packages/atlantis/admin/js/history/native.history.js'),
			'admin' => asset('packages/atlantis/admin/js/admin.js'),
			'settings' => asset('packages/atlantis/admin/js/settings.js'),
		);
	}

	$view->js += array('page' => asset('packages/atlantis/admin/js/page.js'));
});