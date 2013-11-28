<?php namespace Atlantis;
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


use Atlantis\Admin\Environment as AdminProvider;
use Atlantis\User\Environment as UserProvider;
use Atlantis\View\Environment as ViewProvider;


class Atlantis {

    protected $adminProvider;
    protected $userProvider;
    protected $viewProvider;


    public function __construct(
        AdminProvider $adminProvider = null,
        UserProvider $userProvider = null,
        ViewProvider $viewProvider = null
    ){

        $this->adminProvider = $adminProvider;
        $this->userProvider = $userProvider;
        $this->viewProvider = $viewProvider;

    }


    public function users(){
        return $this->userProvider;
    }
}