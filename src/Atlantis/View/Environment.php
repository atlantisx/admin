<?php namespace Atlantis\View;
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

use Doctrine\Common\Cache\FilesystemCache;
use Illuminate\Support\Facades\Cache;
use \Illuminate\View\Environment as BaseEnvironment;
use \Illuminate\Support\Facades\File;


class Environment extends BaseEnvironment{

    /**
     * Get a evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = array(), $mergeData = array())
    {
        $path = $this->finder->find($view);

        $data = array_merge($mergeData, $this->parseData($data));

        $engine = $this->getEngineFromPath($path);

        $this->callCreator($newView = new View($this, $engine, $view, $path, $data));

        return $newView;
    }

}
