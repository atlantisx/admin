<?php

use Atlantis\Admin\ModelHelper;
use Atlantis\Admin\Fields\Field;


View::composer(array('admin::layouts.common','layouts.common'), function($view){
    $view->appbase = url('/') . '/';

    /*================================================================
        Common Assets
    ================================================================*/
    Basset::collection('common', function($collection){

        //[i]========================================================= CSS Framework
        $collection->stylesheet('components/bootstrap/css/bootstrap.css')->apply('JsMin');
        $collection->stylesheet('components/bootstrap/css/bootstrap-theme.css')->apply('JsMin');

        //[i] Fonts
        $collection->stylesheet('//fonts.googleapis.com/css?family=Open+Sans:400,600,800');

        //[i]========================================================= Core JS
        $collection->directory('components', function($collection){
            #$collection->javascript('require.js');
            $collection->javascript('jquery/jquery.js');
            $collection->javascript('jquery/jquery-migrate.js');
            $collection->javascript('angularjs/angular.js');
            $collection->javascript('angularjs/angular-resource.js');
            $collection->javascript('bootstrap/js/bootstrap.js');
        })->apply('JsMin');

        //[i]========================================================= Atlantis CSS
        $collection->directory('packages/atlantis/admin/stylesheet', function($collection){
            $collection->requireDirectory('../javascript/libs/atlantis/css');
        })->apply('CssMin');
    });


    /*================================================================
        User Assets
    ================================================================*/
    Basset::collection('user', function($collection){
        $locale = Config::get('app.locale');

        //[i]========================================================= User CSS
        $collection->directory('assets/stylesheet', function($collection)
        {
            $collection->stylesheet('less/user.less')->apply('Less');
            $collection->requireDirectory('css')->except('application.css');
            $collection->requireDirectory('libs/jquery.ui');
            $collection->requireDirectory('libs/jquery.ui.timepicker');
            $collection->requireDirectory('libs/jquery.ui.colorpicker');
            $collection->requireDirectory('libs/jquery.customscroll');
        })->apply('CssMin')
            ->andApply('UriRewriteFilter')
            ->andApply('UriPrependFilter')
            ->setArguments(Config::get('app.url'));

        //[i]========================================================= User JS
        $collection->directory('assets/javascript', function($collection) use($locale)
        {
            $collection->requireDirectory('libs/jquery.ui');
            $collection->requireDirectory('libs/jquery.uniform');
            $collection->requireDirectory('libs/jquery.colocpicker');
            $collection->requireDirectory('libs/jquery.customscroll');
            $collection->requireDirectory('libs/jquery.ui.timepicker');
            $collection->requireDirectory('libs/jquery.ui.slider');
            $collection->requireDirectory('libs/bootstrap.wizard');
            $collection->requireDirectory('libs/bootstrap.datepicker');

            //$collection->javascript('jquery.ui/i18n/jquery.ui.datepicker-'.$locale.'.js');
            //$collection->javascript('jquery.ui.timepicker/i18n/jquery-ui-timepicker-'.$locale.'.js');
        })->apply('JsMin');

        //[i]========================================================= Atlantis CSS
        $collection->directory('packages/atlantis/admin/stylesheet', function($collection){
            $collection->stylesheet('css/custom.css');
            $collection->stylesheet('../javascript/libs/jquery.touch/jquery.touch.css');
        })->apply('CssMin');

        //[i]========================================================= Atlantis Package JS
        $collection->directory('packages/atlantis/admin/javascript/libs', function($collection) use($locale){
            $collection->requireDirectory('jquery.icheck');
            $collection->requireDirectory('jquery.psteps');
            $collection->javascript('jquery.select2/jquery.select2.js');
            $collection->javascript('jquery.validation/jquery.validationEngine.js');
            $collection->javascript('jquery.touch/jquery.touch.js');
            $collection->javascript('jquery.datatables/jquery.datatables.js');
            $collection->javascript('jquery.noty/jquery.noty.js');
            $collection->javascript('jquery.noty/layouts/topRight.js');
            $collection->javascript('jquery.noty/themes/default.js');
            $collection->javascript('jquery.fineuploader/jquery.fineuploader-4.3.1.js');
            $collection->requireDirectory('bootstrap.wysihtml');
            $collection->requireDirectory('bootstrap.bootbox');

            //[i] Angular Modules
            $collection->javascript('angular.xeditable/js/xeditable.js');
            $collection->javascript('angular.ui-bootstrap/angular.ui-bootstrap.js');
            $collection->javascript('angular.ui-select2/angular.ui-select2.js');

            //[i] Atlantis-Angular Modules
            $collection->javascript('atlantis/plugins/angular.atlantis.ui.js');
            $collection->javascript('atlantis/plugins/angular.atlantis.api.js');
            $collection->javascript('atlantis/plugins/angular.atlantis.rest.js');
            $collection->javascript('atlantis/plugins/angular.atlantis.js');

            //[i] Atlantis Core
            $collection->javascript('atlantis/atlantis.js');
            $collection->javascript('atlantis/core/atlantis.alert.js');

            #i: Locale
            $collection->javascript('jquery.validation/jquery.validationEngine-'.$locale.'.js');
            //$collection->javascript('jquery.select2/i18n/select2_locale_'.$locale.'.js');
        })->apply('JsMin');
    });


    /*================================================================
        Admin Assets
    ================================================================*/
    Basset::collection('admin', function($collection){
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


//header view
View::composer(array('admin::partials.header'), function($view)
{
    $view->menu = App::make('admin_menu')->getMenu();
    $view->settingsPrefix = App::make('admin_config_factory')->getSettingsPrefix();
    $view->pagePrefix = App::make('admin_config_factory')->getPagePrefix();
    $view->configType = App::bound('itemconfig') ? App::make('itemconfig')->getType() : false;
});


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