<?php namespace Atlantis\Auth;
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

use Closure;
use Illuminate\Events\Dispatcher;


class Environment {
    protected $files;
    protected $config;

    protected $observables;
    //public $dispatcher;

    public function __construct($files=null, $config=null, Dispatcher $dispatcher=null){
        $this->files = $files;
        $this->config = $config;

        //static::$dispatcher = $dispatcher;
    }


    public function observe($class, Closure $closure=null){
        // Observable task
        // - register.before / register.after
        // - login.before / login.after
        // - logout.before / logout.after

        if( is_string($class) ){


        }else if( get_class($class) ){

        }

        /*$instance = new static;

        $className = get_class($class);

        // When registering a model observer, we will spin through the possible events
        // and determine if this observer has that method. If it does, we will hook
        // it into the model's event system, making it convenient to watch these.
        foreach ($instance->getObservableEvents() as $event)
        {
            if (method_exists($class, $event))
            {
                static::registerModelEvent($event, $className.'@'.$event);
            }
        }*/
    }


    /*protected static function registerModelEvent($event, $callback, $priority = 0)
    {
        if (isset(static::$dispatcher))
        {
            $name = get_called_class();

            static::$dispatcher->listen("auth.{$event}: {$name}", $callback, $priority);
        }
    }


    protected function fireModelEvent($event, $halt = true)
    {
        if ( ! isset(static::$dispatcher)) return true;

        // We will append the names of the class to the event to distinguish it from
        // other model events that are fired, allowing us to listen on each model
        // event set individually instead of catching event for all the models.
        $event = "auth.{$event}: ".get_class($this);

        $method = $halt ? 'until' : 'fire';

        return static::$dispatcher->$method($event, $this);
    }


    public function getObservableEvents()
    {
        return array_merge(
            array(
                'register', 'login', 'logout'
                //'creating', 'created', 'updating', 'updated',
                //'deleting', 'deleted', 'saving', 'saved',
                //'restoring', 'restored',
            ),
            $this->observables
        );
    }*/

}