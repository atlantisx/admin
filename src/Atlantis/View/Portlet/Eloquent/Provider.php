<?php namespace Atlantis\View\Portlet\Eloquent;
/**
 * Part of the Atlantis package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Atlantis
 * @version    1.0.0
 * @author     Nematix LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 1997 - 2013, Nematix LLC
 * @link       http://nematix.com
 */


use Atlantis\View\Portlet\PortletNotFoundException;
use Atlantis\View\Portlet\ProviderInterface;


class Provider implements ProviderInterface {

	/**
	 * The Eloquent portlet model.
	 *
	 * @var string
	 */
	protected $model = 'Atlantis\View\Portlet\Eloquent\Portlet';

	/**
	 * Create a new Eloquent Portlet provider.
	 *
	 * @param  \Cartalyst\Sentry\Hashing\HasherInterface  $hasher
	 * @param  string  $model
	 * @return void
	 */
	public function __construct($model = null)
	{
		if (isset($model))
		{
			$this->model = $model;
		}
	}

	/**
	 * Finds a portlet by the given portlet ID.
	 *
	 * @param  mixed  $id
	 * @return \Atlantis\View\Portlet\PortletInterface
	 * @throws \Atlantis\View\Portlet\PortletNotFoundException
	 */
	public function findById($id)
	{
		$model = $this->createModel();

		if ( ! $portlet = $model->newQuery()->find($id))
		{
			throw new PortletNotFoundException("A portlet could not be found with ID [$id].");
		}

		return $portlet;
	}


    /**
     * Finds a user by the given portlet name.
     *
     * @param  mixed  $id
     * @return \Atlantis\View\Portlet\PortletInterface
     * @throws \Atlantis\View\Portlet\PortletNotFoundException
     */
    public function findByName($name)
    {
        $model = $this->createModel();

        if ( ! $portlet = $model->newQuery()->where('name','=',$name)->first() )
        {
            throw new PortletNotFoundException("A portlet could not be found with name [$name].");
        }

        return $portlet;
    }


	/**
	 * Returns an array containing all portlet.
	 *
	 * @return array
	 */
	public function findAll()
	{
		return $this->createModel()->newQuery()->get()->all();
	}

    /**
     * Returns an empty user object.
     *
     * @return \Atlantis\View\Portlet\PortletInterface
     */
    public function getEmptyPortlet()
    {
        return $this->createModel();
    }

	/**
	 * Creates a portlet.
	 *
	 * @param  array  $credentials
	 * @return \Atlantis\View\Portlet\PortletInterface
	 */
	public function create()
	{
		$user = $this->createModel();
		$user->save();

		return $user;
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createModel()
	{
		$class = '\\'.ltrim($this->model, '\\');

		return new $class;
	}

}
