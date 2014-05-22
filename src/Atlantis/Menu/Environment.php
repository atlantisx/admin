<?php namespace Atlantis\Menu;

use Illuminate\Config\Repository AS Config;


class Environment {

	protected $app;


	public function __construct($app)
	{
		$this->config = $app;
	}


	/**
	 * Gets the menu items indexed by their name with a value of the title
	 *
	 * @param array		$subMenu (used for recursion)
	 *
	 * @return array
	 */
	public function getMenu($subMenu = null)
	{
		$menu = array();

		if (!$subMenu)
		{
			$subMenu = $this->config->get('admin::admin.menu');
		}

		//iterate over the menu to build the return array of valid menu items
		foreach ($subMenu as $key => $item)
		{
			//if the item is a string, find its config
			if (is_string($item))
			{
				//fetch the appropriate config file
				$config = $this->configFactory->make($item);

				//if a config object was returned and if the permission passes, add the item to the menu
				if (is_a($config, 'Atlantis\Admin\Config\Config') && $config->getOption('permission'))
				{
					$menu[$item] = $config->getOption('title');
				}
				//otherwise if this is a custom page, add it to the menu
				else if ($config === true)
				{
					$menu[$item] = $key;
				}
			}
			//if the item is an array, recursively run this method on it
			else if (is_array($item))
			{
				$menu[$key] = $this->getMenu($item);

				//if the submenu is empty, unset it
				if (empty($menu[$key]))
				{
					unset($menu[$key]);
				}
			}
		}

		return $menu;
	}
}