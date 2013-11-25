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


use Illuminate\View\View as BaseView;
use Atlantis\View\Portlet\Eloquent\Portlet;
use Atlantis\View\Portlet\Eloquent\Provider as PortletProvider;
use Atlantis\View\Portlet\ProviderInterface as PortletProviderInterface;


class View extends BaseView{

    /*protected $portletProvider;

    public function __construct(
        PortletProviderInterface $portletProvider = null
    ){
        $this->portletProvider = $portletProvider ?: new PortletProvider;
    }


    public function portlet($name,$data=array()){
        $portlet = $this->portletProvider->findByName($name);

        if( $portlet ){
            return $portlet->html($data);
        };
    }*/

    /**
     * Get the evaluated contents of the view.
     *
     * @return string
     */
    /*protected function getContents()
    {
        //[i] Custom caching controller
        $cache = \App::make('cache');
        $compiler = $this->engine->getCompiler();

        //[i] Check file expiration
        if( $compiler->isExpired( $this->path ) ){
            $cache->forget($this->path);
        };

        //[i] Retrieve cache
        if( $cache->has($this->path) ){
            $content = $cache->get($this->path);
        }else{
            $content = $this->engine->get($this->path, $this->gatherData());
            $cache->add($this->path,$content,60);
        }

        return $content;
    }*/
}
