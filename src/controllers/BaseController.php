<?php namespace Atlantis\Admin;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;


class BaseController extends Controller {
    /**-----------------------------------------------------------------------------------------------------------------
     * Global Attributes
     -----------------------------------------------------------------------------------------------------------------*/
    protected $user;
    protected $user_role;
    protected $superuser;
    protected $route_name;


	/**-----------------------------------------------------------------------------------------------------------------
	 * Setup the layout used by the controller.
	 *
	 * @return void
     -----------------------------------------------------------------------------------------------------------------*/
	protected function setupLayout()
	{
		#i: Default layout
        if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
            $this->layout->page = false;
		}

        #i: Current User
        if( \Sentry::getUser() ){
            $this->user = \Sentry::getUser();
            $this->user_role = \Atlantis::users()->getUserRoleById($this->user->id)->name;
        }

        #i: Superuser
        $superuser = \Sentry::findAllUsersWithAccess('superuser')[0];
        if( $superuser ){
            $this->superuser = $superuser;
        }

        #i: Current route name
        $this->route_name = \Route::currentRouteName();
	}

}