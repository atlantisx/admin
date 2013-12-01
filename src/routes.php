<?php



/**
 * Routes : User
 */
Route::group(array('prefix' => 'user'), function(){
    $controller = 'Atlantis\Admin\AuthController';
    try{
        if( class_exists('AuthController') ){
            $controller = 'AuthController';
        }
    }catch (Exception $e){ }

    //[i] Login & Logout
    Route::get('login/{role?}', $controller.'@getLogin');
    Route::post('login/{role?}', $controller.'@postLogin');
    Route::get('logout', $controller.'@getLogout');

    //[i] Registration
    Route::get('register/{role?}', $controller.'@getRegister');
    Route::post('register/{role?}', $controller.'@postRegister');

    //[i] Activation
    Route::get('activation/{code?}', $controller.'@getActivation');
    Route::post('activation', $controller.'@postActivation');

    //[i] User method
    Route::group(array('before'=>'auth.sentry'), function(){
        Route::get('home','Atlantis\Admin\UserController@getHome');
        Route::get('profile','Atlantis\Admin\UserController@getProfile');
    });
});



/**
 * Routes
 */
Route::group(array('prefix' => Config::get('admin::admin.uri'), 'before' => 'auth.admin'), function()
{
	//Admin Dashboard
	Route::get('/', array(
		'as' => 'admin_dashboard',
		'uses' => 'Atlantis\Admin\AdminController@dashboard',
	));

	//File Downloads
	Route::get('file_download', array(
		'as' => 'admin_file_download',
		'uses' => 'Atlantis\Admin\AdminController@fileDownload'
	));

	//Custom Pages
	Route::get('page/{page}', array(
		'as' => 'admin_page',
		'uses' => 'Atlantis\Admin\AdminController@page'
	));

	//The route group for all other requests needs to validate admin, model, and add assets
	Route::group(array('before' => 'validate_model|post_validate'), function()
	{
		//Model Index
		Route::get('{model}', array(
			'as' => 'admin_index',
			'uses' => 'Atlantis\Admin\AdminController@index'
		));

		//Get Item
		Route::get('{model}/{id}', array(
			'as' => 'admin_get_item',
			'uses' => 'Atlantis\Admin\AdminController@item'
		))
		->where('id', '[0-9]+');

		//New Item
		Route::get('{model}/new', array(
			'as' => 'admin_new_item',
			'uses' => 'Atlantis\Admin\AdminController@item'
		));

		//Update a relationship's items with constraints
		Route::post('{model}/update_options', array(
			'as' => 'admin_update_options',
			'uses' => 'Atlantis\Admin\AdminController@updateOptions'
		));

		//Display an image or file field's image or file
		Route::get('{model}/file', array(
			'as' => 'admin_display_file',
			'uses' => 'Atlantis\Admin\AdminController@displayFile'
		));

		//File Uploads
		Route::post('{model}/{field}/file_upload', array(
			'as' => 'admin_file_upload',
			'uses' => 'Atlantis\Admin\AdminController@fileUpload'
		));

		//Updating Rows Per Page
		Route::post('{model}/rows_per_page', array(
			'as' => 'admin_rows_per_page',
			'uses' => 'Atlantis\Admin\AdminController@rowsPerPage'
		));

		//CSRF protection in forms
		Route::group(array('before' => 'csrf'), function()
		{
			//Save Item
			Route::post('{model}/{id?}/save', array(
				'as' => 'admin_save_item',
				'uses' => 'Atlantis\Admin\AdminController@save'
			))
			->where('id', '[0-9]+');

			//Delete Item
			Route::post('{model}/{id}/delete', array(
				'as' => 'admin_delete_item',
				'uses' => 'Atlantis\Admin\AdminController@delete'
			))
			->where('id', '[0-9]+');

			//Get results
			Route::post('{model}/results', array(
				'as' => 'admin_get_results',
				'uses' => 'Atlantis\Admin\AdminController@results'
			));

			//Custom Model Action
			Route::post('{model}/custom_action', array(
				'as' => 'admin_custom_model_action',
				'uses' => 'Atlantis\Admin\AdminController@customModelAction'
			))
			->where('id', '[0-9]+');

			//Custom Item Action
			Route::post('{model}/{id}/custom_action', array(
				'as' => 'admin_custom_model_item_action',
				'uses' => 'Atlantis\Admin\AdminController@customModelItemAction'
			))
			->where('id', '[0-9]+');
		});
	});


	Route::group(array('before' => 'validate_settings|post_validate'), function()
	{
		//Settings Pages
		Route::get('settings/{settings}', array(
			'as' => 'admin_settings',
			'uses' => 'Atlantis\Admin\AdminController@settings'
		));

		//Settings file upload
		Route::post('settings/{settings}/{field}/file_upload', array(
			'as' => 'admin_settings_file_upload',
			'uses' => 'Atlantis\Admin\AdminController@fileUpload'
		));

		//Display a settings file
		Route::get('settings/{settings}/file', array(
			'as' => 'admin_settings_display_file',
			'uses' => 'Atlantis\Admin\AdminController@displayFile'
		));

		//CSRF routes
		Route::group(array('before' => 'csrf'), function()
		{
			//Save Item
			Route::post('settings/{settings}/save', array(
				'as' => 'admin_settings_save',
				'uses' => 'Atlantis\Admin\AdminController@settingsSave'
			));

			//Custom Action
			Route::post('settings/{settings}/custom_action', array(
				'as' => 'admin_settings_custom_action',
				'uses' => 'Atlantis\Admin\AdminController@settingsCustomAction'
			));
		});
	});

	//Switch locales
	Route::get('switch_locale/{locale}', array(
		'as' => 'admin_switch_locale',
		'uses' => 'Atlantis\Admin\AdminController@switchLocale'
	));
});


//[i] API
Route::group(array('prefix'=>'api/v1'), function(){
    Route::resource('users','Atlantis\Admin\Api\V1\UserController');
});