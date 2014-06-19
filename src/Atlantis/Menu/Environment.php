<?php namespace Atlantis\Menu;

use Illuminate\Config\FileLoader;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Atlantis\Menu\Items\ItemList;


class Environment {
	protected $app;


	public function __construct($app)
	{
		$this->app = $app;
	}


	/**
	 * Gets the menu items indexed by their name with a value of the title
	 *
	 * @param array		$subMenu (used for recursion)
	 * @return array
	 */
	public function getMenu($subMenu = null)
	{
		$menu = array();

		if (!$subMenu) $subMenu = $this->app['config']->get('admin::menu.admin');

		#i: iterate over the menu to build the return array of valid menu items
		foreach ($subMenu as $key => $item){
			#i: If the item is a string, find its config
			if (is_string($item)){
				#i: Fetch the appropriate config, using Admin Config Factory
				$config = $this->app['admin_config_factory']->make($item);

				#i: If a config object was returned and if the permission passes, add the item to the menu
				if (is_a($config, 'Atlantis\Admin\Config\Config') && $config->getOption('permission')){
					$menu[$item] = $config->getOption('title');
				}
				#i: otherwise if this is a custom page, add it to the menu
				else if ($config === true){
					$menu[$item] = $key;
				}
			}
			#i: if the item is an array, recursively run this method on it
			else if (is_array($item))
			{
				$menu[$key] = $this->getMenu($item);

				#i: if the submenu is empty, unset it
				if (empty($menu[$key]))
				{
					unset($menu[$key]);
				}
			}
		}

		return $menu;
	}


    /**
     * The current IoC container
     * @var Container
     */
    protected static $container;

    /**
     * All the registered names and the associated ItemLists
     *
     * @var array
     */
    protected static $itemLists = array();

    /**
     * Get a MenuHandler.
     *
     * This method will retrieve ItemLists by name,
     * If an ItemList doesn't already exist, it will
     * be registered and added to the handler.
     *
     * <code>
     *    // Get the menu handler that handles the default name
     *    $handler = Menu::handler();
     *
     *    // Get a named menu handler for a single name
     *    $handler = Menu::handler('backend');
     *
     *    // Get a menu handler that handles multiple names
     *    $handler = Menu::handler(array('admin', 'sales'));
     * </code>
     *
     * @param string|array $names      The name this handler should respond to
     * @param array        $attributes Its attributes
     * @param string       $element    Its element
     *
     * @return MenuHandler
     */
    public static function handler($names = '', $attributes = array(), $element = 'ul')
    {
        $names = (array) $names;

        $itemLists = array();
        // Create a new ItemList instance for the names that don't exist yet
        foreach ($names as $name) {
            if (!array_key_exists($name, static::$itemLists)) {
                $itemList = new ItemList(array(), $name, $attributes, $element);
                static::setItemList($name, $itemList);
            }
            else {
                $itemList = static::getItemList($name);
            }

            $itemLists[] = $itemList;
        }

        // Return a Handler for the item lists
        return new MenuHandler($itemLists);
    }

    /**
     * Get a MenuHandler for all registered ItemLists
     *
     * @return MenuHandler
     */
    public static function allHandlers()
    {
        return new MenuHandler(static::$itemLists);
    }

    /**
     * Erase all menus in memory
     */
    public static function reset()
    {
        static::$itemLists = array();
    }

    ////////////////////////////////////////////////////////////////////
    //////////////////////// ITEM LISTS MANAGING ///////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Create a new ItemList
     *
     * @param string $name       The name of the ItemList
     * @param array  $attributes The HTML attributes for the list element
     * @param string $element    The HTML element for the list (ul or dd)
     *
     * @return ItemList
     */
    public static function items($name = null, $attributes = array(), $element = 'ul')
    {
        return new ItemList(array(), $name, $attributes, $element);
    }

    /**
     * Store an ItemList in memory
     *
     * @param  string   $name     The handle to store it to
     * @param  ItemList $itemList
     *
     * @return ItemList
     */
    public static function setItemList($name, $itemList)
    {
        static::$itemLists[$name] = $itemList;

        return $itemList;
    }

    /**
     * Get an ItemList from the memory
     *
     * @param string $name The ItemList handle
     *
     * @return ItemList
     */
    public static function getItemList($name = null)
    {
        if (is_null($name)) return static::$itemLists;
        return static::$itemLists[$name];
    }

    ////////////////////////////////////////////////////////////////////
    /////////////////////////// MAGIC METHODS //////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Magic Method for calling methods on the default handler.
     *
     * <code>
     *    // Call the "render" method on the default handler
     *    echo Menu::render();
     *
     *    // Call the "add" method on the default handler
     *    Menu::add('home', 'Home');
     * </code>
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters = array())
    {
        return call_user_func_array(array(static::handler(), $method), $parameters);
    }

    ////////////////////////////////////////////////////////////////////
    /////////////////////// DEPENDENCY INJECTIONS //////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Get the current dependencies
     *
     * @param string $dependency A dependency to make on the fly
     *
     * @return Container
     */
    public static function getContainer($dependency = null)
    {
        if (!static::$container) {
            $container = new Container;

            // Create HTML
            $container->bindIf('html', 'LaravelBook\Laravel4Powerpack\HTML');

            // Create basic Request instance to use
            $container->alias('Symfony\Component\HttpFoundation\Request', 'request');
            $container->bindIf('Symfony\Component\HttpFoundation\Request', function() {
                return Request::createFromGlobals();
            });

            static::setContainer($container);
        }

        // Shortcut for getting a dependency
        if ($dependency) {
            return static::$container->make($dependency);
        }

        return static::$container;
    }

    /**
     * Set the Container to use
     *
     * @param Container $container
     */
    public static function setContainer($container)
    {
        static::$container = $container;
    }

    /**
     * Get an option from the options array
     *
     * @param string $option The option key
     *
     * @return mixed Its value
     */
    public static function getOption($option = null)
    {
        if ($option == null) {
            $option = 'config';
        }
        return static::getContainer('config')->get('menu::'.$option);
    }

    /**
     * Set a global option
     *
     * @param key   $option The option
     * @param mixed $value  Its value
     */
    public static function setOption($option, $value)
    {
        if ($option == null) {
            $option = 'config';
        }
        static::getContainer('config')->set('menu::'.$option, $value);
    }
}