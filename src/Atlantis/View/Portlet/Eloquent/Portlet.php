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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Atlantis\View\Portlet\PortletInterface;

class Portlet extends Model implements PortletInterface {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'portlets';

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = array();


    public function getId(){
        return $this->name;
    }

    public function template($data=array()){
        if( $this->status == 0 ) return '';

        if( $this->type == 'model' ){
            return $this->body;

        }elseif( $this->type == 'view' ){
            return View::make($this->uri)->with($data)->render();

        }

    }
}
