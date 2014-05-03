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
	 * The menu structure of the site. For models, you should either supply the name of a model config file or an array of names of model config
	 * files. The same applies to settings config files, except you must prepend 'settings.' to the settings config file name. You can also add
	 * custom pages by prepending a view path with 'page.'. By providing an array of names, you can group certain models or settings pages
	 * together. Each name needs to either have a config file in your model config path, settings config path with the same name, or a path to a
	 * fully-qualified Laravel view. So 'users' would require a 'users.php' file in your model config path, 'settings.site' would require a
	 * 'site.php' file in your settings config path, and 'page.foo.test' would require a 'test.php' or 'test.blade.php' file in a 'foo' directory
	 * inside your view directory.
	 *
	 * @type array
	 *
	 * 	array(
	 *		'E-Commerce' => array('collections', 'products', 'product_images', 'orders'),
	 *		'homepage_sliders',
	 *		'users',
	 *		'Settings' => array('settings.site', 'settings.ecommerce', 'settings.social'),
	 * 		'Analytics' => array('E-Commerce' => 'page.ecommerce.analytics'),
	 *	)
	 */
	'menu' => array(
        'System Settings' => array('settings.site'),

        'Users' => array('users','groups','roles','permissions'),

        'Localization' => array('codes')
    ),

    'sidebar' => array(
        'applications' => array(
            'advance' => array(
                'title' => 'EPendahuluan',
                'route' => 'application',
                'items' => array(
                    'application' => 'Senarai Permohonan',
                    'application.new' => 'Permohonan Baru',
                    'application.review' => 'Semakan Permohonan',
                    'application.approve' => 'Kelulusan Permohonan',
                    'application.statistic' => 'Statistik Permohonan',
                )
            )
        )
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
