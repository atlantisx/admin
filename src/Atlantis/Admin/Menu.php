<?php
namespace Atlantis\Admin;

use Illuminate\Config\Repository AS Config;
use Atlantis\Admin\Config\Factory AS ConfigFactory;

class Menu {

	/**
	 * The config instance
	 *
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * The config instance
	 *
	 * @var \Atlantis\Admin\Config\Factory
	 */
	protected $configFactory;

	/**
	 * Create a new Menu instance
	 *
	 * @param \Illuminate\Config\Repository				$config
	 * @param \Atlantis\Admin\Config\Factory	$config
	 */
	public function __construct(Config $config, ConfigFactory $configFactory)
	{
		$this->config = $config;
		$this->configFactory = $configFactory;
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
			$subMenu = $this->config->get('administrator::administrator.menu');
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