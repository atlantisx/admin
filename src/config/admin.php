<?php

return array(

	/**
	 * Package URI
	 *
	 * @type string
	 */
	'uri' => 'admin',


	/**
	 * Page title
	 *
	 * @type string
	 */
	'title' => 'Atlantis Administration',


	/**
	 * The path to your model config directory
	 *
	 * @type string
	 */
	'model_config_path' => app('path') . '/config/models',


	/**
	 * The path to your settings config directory
	 *
	 * @type string
	 */
	'settings_config_path' => app('path') . '/config/settings',

    /**
     *
     *
     * @type string
     */
    'settings' => array(
        'base_path' => 'admin'
    ),


	/**
	 * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
	 * is allowed to view the admin section.
	 *
     * e.g: array( 'admin' , 'user' )
     *
	 * @type closure
	 */
	'permission'=> function()
	{
		return Sentry::check();
	},


	/**
	 * This determines if you will have a dashboard (whose view you provide in the dashboard_view option) or a non-dashboard home
	 * page (whose menu item you provide in the home_page option)
	 *
	 * @type bool
	 */
	'use_dashboard' => false,


	/**
	 * If you want to create a dashboard view, provide the view string here.
	 *
	 * @type string
	 */
	'dashboard_view' => '',


	/**
	 * The menu item that should be used as the default landing page of the administrative section
	 *
	 * @type string
	 */
	'home_page' => 'settings.site',


    /**
     *
     *
     * @type string
     */
    'user_default_role' => 'user',

    /**
     * Default page configurations
     *
     * @type string
     */
    'page' => array(
        //[i] Public page
        'public'    => 'public/page',

        //[i] User home
        'home'      => 'home',

        //[i] The login path is the path where Atlantis/Admin will send the user if they fail a permission check.
        'login'     => 'user/login',

        //[i] The logout path is the path where Atlantis/Admin will send the user when they click the logout link.
        'logout'    => 'user/logout',
    ),

    /**
     * !! Deprecating
     *
     * @type string
     */
    'user_home' => 'home',

	/**
	 * !! Deprecating
     * The login path is the path where Administrator will send the user if they fail a permission check
	 *
	 * @type string
	 */
	'login_path' => 'user/login',


	/**
	 * The logout path is the path where Administrator will send the user when they click the logout link
	 *
	 * @type string
	 */
	'logout_path' => 'user/logout',


	/**
	 * This is the key of the return path that is sent with the redirection to your login_action. Input::get('redirect') will hold the return URL.
	 *
	 * @type string
	 */
	'login_redirect_key' => 'redirect',


	/**
	 * Global default rows per page
	 *
	 * @type NULL|int
	 */
	'global_rows_per_page' => 10,


	/**
	 * An array of available locale strings. This determines which locales are available in the languages menu at the top right of the Administrator
	 * interface.
	 *
	 * @type array
	 */
	'locales' => array('en'),

);
