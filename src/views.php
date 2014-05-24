<?php


View::composer(array('core::layouts.common'), function($view){
    /*================================================================
        Admin Assets
    ================================================================*/
    Basset::collection('admin', function($collection){
        $collection->requireDirectory('libs/jquery.uniform');

        $collection->directory('packages/atlantis/admin/javascript/libs', function($collection){
            $collection->javascript('admin/knockout-2.2.0.js');
            $collection->javascript('admin/knockout.mapping.js');
            $collection->javascript('admin/knockout.notification.min.js');
            $collection->javascript('admin/knockout.update-data.js');
            $collection->javascript('admin/markdown.js');
            $collection->javascript('admin/accounting.js');
            $collection->javascript('admin/history.min.js');
        });

        $collection->directory('packages/atlantis/admin/javascript', function($collection){
            $collection->javascript('js/admin.binding.js');
            $collection->javascript('js/admin.js');
            $collection->javascript('js/settings.js');
        });
    });


    #i: Getting admin site title
    View::share('title',Config::get('admin::site.title'));

    #i: Authentication check
    if(Sentry::check()){
        #i: Get user
        $user = Sentry::getUser();

        #i: Get user role home path
        $user_realm = App::make('Atlantis\View\Interfaces\Realm')->current();

        View::share(compact('user','user_realm'));
    }
});


View::composer(array('admin::layouts.user'), function($view){
    $permissions = Config::get('admin::admin.permissions');
    $view->settingsPrefix = App::make('admin_config_factory')->getSettingsPrefix();
    $view->pagePrefix = App::make('admin_config_factory')->getPagePrefix();
    $view->configType = App::bound('itemconfig') ? App::make('itemconfig')->getType() : false;

    #i: (User Role Only) Menu : Admin
    if( Sentry::getUser()->hasAnyAccess($permissions) ){
        $view->menu_admin = App::make('atlantis.menu')->getMenu();
    }

    #i: (Global Role) Sidebar : Applications
    View::share( array('sidebar' => array(
        'applications' => Config::get('admin::menu.sidebar.applications'),
        'user' => Config::get('admin::menu.sidebar.user'),
    )));
});


View::composer(array('admin::partials.header'), function($view)
{
    $view->menu = App::make('atlantis.menu')->getMenu();
    $view->settingsPrefix = App::make('admin_config_factory')->getSettingsPrefix();
    $view->pagePrefix = App::make('admin_config_factory')->getPagePrefix();
    $view->configType = App::bound('itemconfig') ? App::make('itemconfig')->getType() : false;
});


View::composer('admin::admin.admin', function($view)
{
    #i: Get a model instance that we'll use for constructing stuff
    $config = App::make('itemconfig');
    $fieldFactory = App::make('admin_field_factory');
    $columnFactory = App::make('admin_column_factory');
    $actionFactory = App::make('admin_action_factory');
    $dataTable = App::make('admin_datatable');
    $model = $config->getDataModel();
    $baseUrl = URL::route('admin_dashboard');
    $route = parse_url($baseUrl);

    #i: Add the view fields
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