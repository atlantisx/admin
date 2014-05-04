<?php

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
*/

Route::filter('auth.sentry', function()
{
    if ( ! Sentry::check())
    {
        return Redirect::to( Config::get('admin::admin.page.public') );
    }
});


//validate_admin filter
Route::filter('auth.admin', function ()
{
    if ( ! Sentry::check())
    {
        return Redirect::to( Config::get('admin::admin.page.public') );
    }

	//$configFactory = App::make('admin_config_factory');
    $user = Sentry::getUser();

	//[i] Get the admin check closure that should be supplied in the config
	$permissions = Config::get('admin::admin.permissions');
    $loginUrl = URL::to(Config::get('admin::admin.page.login', 'user/login'));
    $redirectKey = Config::get('admin::admin.login_redirect_key', 'redirect');
    $redirectUri = Request::url();

    $response = $user->hasAnyAccess($permissions);

	//[i] If this is a simple false value, send the user to the login redirect
	if (!$response)
	{
		return Redirect::to($loginUrl)->with($redirectKey, $redirectUri);
	}
	//[i] Otherwise if this is a response, return that
	else if (is_a($response, 'Illuminate\Http\JsonResponse') || is_a($response, 'Illuminate\Http\Response'))
	{
		return $response;
	}
	//[i] if it's a redirect, send it back with the redirect uri
	else if (is_a($response, 'Illuminate\\Http\\RedirectResponse'))
	{
		return $response->with($redirectKey, $redirectUri);
	}
});


//validate_model filter
Route::filter('validate_model', function($route, $request)
{
	$modelName = $route->getParameter('model');

	App::singleton('itemconfig', function($app) use ($modelName)
	{
		$configFactory = App::make('admin_config_factory');
		return $configFactory->make($modelName, true);
	});
});


//validate_settings filter
Route::filter('validate_settings', function($route, $request)
{
	$settingsName = $route->getParameter('settings');

	App::singleton('itemconfig', function($app) use ($settingsName)
	{
		$configFactory = App::make('admin_config_factory');
		return $configFactory->make($configFactory->getSettingsPrefix() . $settingsName, true);
	});
});


//post_validate filter
Route::filter('post_validate', function($route, $request)
{
	$config = App::make('itemconfig');

	//if the model doesn't exist at all, redirect to 404
	if (!$config)
	{
		App::abort(404, 'Page not found');
	}

	//check the permission
	$p = $config->getOption('permission');

	//if the user is simply not allowed permission to this model, redirect them to the dashboard
	if (!$p)
	{
		return Redirect::to(URL::route('admin_dashboard'));
	}

	//get the settings data if it's a settings page
	if ($config->getType() === 'settings')
	{
		$config->fetchData(App::make('admin_field_factory')->getEditFields());
	}

	//otherwise if this is a response, return that
	if (is_a($p, 'Illuminate\Http\JsonResponse') || is_a($p, 'Illuminate\Http\Response') || is_a($p, 'Illuminate\\Http\\RedirectResponse'))
	{
		return $p;
	}
});
