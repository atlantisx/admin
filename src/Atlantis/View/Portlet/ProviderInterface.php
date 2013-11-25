<?php namespace Atlantis\View\Portlet;
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



interface ProviderInterface {

    /**
     * Finds a portlet by the given portlet ID.
     *
     * @param  mixed  $id
     * @return \Atlantis\View\Portlet\PortletInterface
     * @throws \Atlantis\View\Portlet\UserNotFoundException
     */
    public function findById($id);


    /**
     * Returns an all portlets.
     *
     * @return array
     */
    public function findAll();


    /**
     * Creates a portlet.
     *
     * @return \Atlantis\View\Portlet\PortletInterface
     */
    public function create();

    /**
     * Returns an empty portlet object.
     *
     * @return \Atlantis\View\Portlet\PortletInterface
     */
    public function getEmptyPortlet();


}
