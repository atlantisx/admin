<?php namespace Atlantis\Admin;

use Atlantis\Core\Controller\BaseController;
use Former;


class UserController extends BaseController {

    public function getHome(){
        $this->layout->content = \View::make('admin::user.home');
    }

    public function getProfile($user_id=null,$role='admin::user'){
        $get = \Input::all();

        #i: Get current user
        if(empty($user_id)) $user_id = \Sentry::getUser()->id;

        $user = \User::find($user_id);

        $get['user_profile'] = $user;

        #i: Inline data populate
        \Former::populate($user);

        #i: View scaffolding
        $view = 'admin::user.profile';
        if(\View::exists('user.profile')) $view = 'user.profile';

        #i: Display view
        $this->layout->content = \View::make($view,$get);
    }
}
