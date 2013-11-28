<?php

use Atlantis\Admin\ModelHelper;
use Atlantis\Admin\Fields\Field;

//View Composers

//admin index view
View::composer('admin::admin.admin', function($view)
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
View::composer('admin::admin.settings', function($view)
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



View::composer(array('admin::layouts.common'), function($view){
    $locale = Config::get('app.locale');

    //if (!$view->page){
        Basset::collection('common', function($collection){
            $collection->directory('packages/atlantis/admin/stylesheet', function($collection){
                //$collection->stylesheet('css/main.css');
            })->apply('CssMin');

            $collection->directory('packages/atlantis/admin/javascript/libs', function($collection){
                $collection->javascript('knockout/knockout-2.2.0.js');
                $collection->javascript('knockout/knockout.mapping.js');
                $collection->javascript('knockout/KnockoutNotification.knockout.min.js');
                $collection->javascript('knockout/knockout.updateData.js');
                $collection->javascript('markdown/markdown.js');
            });
        });

        Basset::collection('admin', function($collection){
            $collection->directory('packages/atlantis/admin/javascript', function($collection){
                $collection->javascript('js/knockout.binding.js');
                $collection->javascript('js/accounting.js');
                $collection->javascript('libs/history/history.min.js');
                $collection->javascript('js/admin.js');
                $collection->javascript('js/settings.js');
            });
        });
    //}

    Basset::collection('admin', function($collection){
        $collection->javascript('packages/atlantis/admin/javascript/js/page.js');
    });

});



View::composer(array('admin::admin.admin'), function($view){
    //
});



View::composer(array('admin::admin.settings'), function($view){
    //
});


View::composer(array('admin::layouts.user'), function($view){
    $permissions = Config::get('admin::admin.permissions');
    $view->settingsPrefix = App::make('admin_config_factory')->getSettingsPrefix();
    $view->pagePrefix = App::make('admin_config_factory')->getPagePrefix();
    $view->configType = App::bound('itemconfig') ? App::make('itemconfig')->getType() : false;

    //[i] (User Role Only) Menu : Admin
    if( Sentry::getUser()->hasAnyAccess($permissions) ){
        $view->menu_admin = App::make('admin_menu')->getMenu();
    }

    //[i] (Global Role) Sidebar : Applications
    View::share( array('sidebar' => array(
        'applications' => Config::get('admin::admin.sidebar.applications'),
        'user' => Config::get('admin::admin.sidebar.user'),
    )));
});