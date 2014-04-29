<?php namespace Atlantis\Admin;

use Former;


class UserController extends BaseController {
    protected $layout = 'admin::layouts.common';

    public function getHome(){
        $this->layout->content = \View::make('admin::user.home');
    }

    public function getProfile($user_id=null){
        $get = \Input::all();

        #i: Get current user
        if(empty($user_id)) $user_id = \Sentry::getUser()->id;

        $user = \User::find($user_id);

        $get['user_profile'] = $user;

        #i: Inline data populate
        \Former::populate($user);

        #i: Display view
        $this->layout->content = \View::make('admin::user.profile',$get);
    }
}
