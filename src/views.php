<?php

use Atlantis\Admin\ModelHelper;
use Atlantis\Admin\Fields\Field;


View::composer(array('admin::layouts.common','layouts.common'), function($view){
    $view->appbase = url('/') . '/';

    /* Common Assets
    * ---------------------------------------------------------------------*/
    Basset::collection('common', function($collection){
        //[i] CSS Framework
        $collection->directory('../vendor/twitter/bootstrap/dist/css', function($collection){
            $collection->stylesheet('bootstrap.css');
            $collection->stylesheet('bootstrap-theme.css');
        })->apply('CssMin');

        //[i] Fonts
        $collection->stylesheet('//fonts.googleapis.com/css?family=Open+Sans:400,600,800');

        //[i] Core JS
        #$collection->javascript('../components/require.js')->apply('JsMin');
        $collection->javascript('../components/jquery/jquery.js')->apply('JsMin');
        $collection->javascript('../components/jquery/jquery-migrate.js')->apply('JsMin');
        $collection->javascript('../vendor/twitter/bootstrap/dist/js/bootstrap.js')->apply('JsMin');
        $collection->javascript('../components/angularjs/angular.js')->apply('JsMin');
        $collection->javascript('../components/angularjs/angular-resource.js')->apply('JsMin');

        //[i] Atlantis CSS
        $collection->directory('packages/atlantis/admin/stylesheet', function($collection){
            #$collection->stylesheet('css/main.css');
        })->apply('CssMin');
    });


    /* User Assets
    * ---------------------------------------------------------------------*/
    Basset::collection('user', function($collection){
        $locale = Config::get('app.locale');

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
            ->whenEnvironmentIs('local')
            ->setArguments('/ependahuluan');

        $collection->directory('assets/javascript', function($collection)
        {
            $collection->requireDirectory('coffee')->only('user.coffee')->apply('CoffeeScript');
            $collection->requireDirectory('libs/jquery.ui');
            $collection->requireDirectory('libs/jquery.icheck');
            $collection->requireDirectory('libs/jquery.uniform');
            $collection->requireDirectory('libs/jquery.select2');
            $collection->requireDirectory('libs/jquery.colocpicker');
            $collection->requireDirectory('libs/jquery.customscroll');
            $collection->requireDirectory('libs/jquery.ui.timepicker');
            $collection->requireDirectory('libs/jquery.ui.slider');
            $collection->requireDirectory('libs/jquery.psteps');
            $collection->requireDirectory('libs/bootstrap.wizard');
            $collection->requireDirectory('libs/bootstrap.datepicker');
            $collection->javascript('libs/jquery.validation/jquery.validationEngine.js');
            $collection->javascript('libs/ckeditor/ckeditor.js');
            $collection->javascript('libs/ckeditor/adapters/jquery.js');
            $collection->javascript('libs/jquery.fineuploader/jquery.fineuploader-4.3.1.js');
        })->apply('JsMin');

        $collection->directory('packages/atlantis/admin/javascript/libs', function($collection){
            $collection->javascript('jquery.noty/jquery.noty.js');
            $collection->javascript('jquery.noty/layouts/topRight.js');
            $collection->javascript('jquery.noty/themes/default.js');

            //[i] Angular Modules
            $collection->javascript('angular.xeditable/js/xeditable.js');

            //[i] Atlantis-Angular Modules
            $collection->javascript('atlantis/plugins/angular.atlantis.ui.js');
            $collection->javascript('atlantis/plugins/angular.atlantis.api.js');
            $collection->javascript('atlantis/plugins/angular.atlantis.js');

            //[i] Atlantis Core
            $collection->javascript('atlantis/atlantis.js');
            $collection->javascript('atlantis/core/atlantis.alert.js');
        })->apply('JsMin');

        $collection->directory('assets/javascript/libs', function($collection) use($locale){
            $collection->javascript('plupload/i18n/'.$locale.'.js');
            $collection->javascript('jquery.ui/i18n/jquery.ui.datepicker-'.$locale.'.js');
            $collection->javascript('jquery.ui.timepicker/i18n/jquery-ui-timepicker-'.$locale.'.js');
            $collection->javascript('jquery.validation/jquery.validationEngine-'.$locale.'.js');
        })->apply('JsMin');
    });


    /* Admin Assets
    * ---------------------------------------------------------------------*/
    Basset::collection('admin', function($collection){
        $collection->directory('packages/atlantis/admin/javascript/libs', function($collection){
            $collection->javascript('knockout/knockout-2.2.0.js');
            $collection->javascript('knockout/knockout.mapping.js');
            $collection->javascript('knockout/KnockoutNotification.knockout.min.js');
            $collection->javascript('knockout/knockout.updateData.js');
            $collection->javascript('markdown/markdown.js');
            $collection->javascript('accounting/accounting.js');
            $collection->javascript('history/history.min.js');
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