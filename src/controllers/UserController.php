<?php namespace Atlantis\Admin;

use Former;


class UserController extends BaseController {
    protected $layout = 'admin::layouts.common';

    public function getHome(){
        $this->layout->content = \View::make('admin::user.home');
    }

    public function getProfile(){
        $get = \Input::all();

        #i: Get current user
        $user = \Sentry::getUser();
        $user = \User::find($user->id);

        #i: Inline data populate
        \Former::populate($user);

        #i: Display view
        $this->layout->content = \View::make('admin::user.profile',$get);
    }
}
