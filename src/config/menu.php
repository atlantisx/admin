<?php return array(

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
    'admin' => array(
        'System Settings'   => array('settings.site'),
        'Users'             => array('users','groups','roles','permissions'),
        'Localization'      => array('codes')
    ),

    'sidebar' => array(),


    // Global options ------------------------------------------------ /

    // The maximum depth a list can be generated
    // -1 means no limit
    'max_depth' => -1,

    // Items --------------------------------------------------------- /

    // Various options related to Items
    'item' => array(

        // The default Item element
        'element' => 'li',

        // Various classes to mark active items or children
        'active_class'       => 'active',
        'active_child_class' => 'active-child',
    ),

    // ItemLists ----------------------------------------------------- /

    'item_list' => array(

        // The default ItemList element
        'element' => 'ul',

        // The default breadcrumb separator, set to '' to not output any separators for
        // use with bootstrap.
        'breadcrumb_separator' => '/',

        // A prefix to prepend the links URLs with
        'prefix'         => null,

        // Whether links should inherit their parent/handler's prefix
        'prefix_parents' => false,
        'prefix_handler' => false,
    ),
);