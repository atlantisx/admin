<?php namespace Atlantis\Admin;

use Former;


class UserController extends BaseController {
    protected $layout = 'admin::layouts.common';

    public function getHome(){
        $this->layout->content = \View::make('admin::user.home');
    }

    public function getProfile(){

        $user = \Sentry::getUser();
        $user = \User::find($user->id);

        Former::populate($user);

        $data = array(
            'form_action' => 'edit',
            'roles' => array(
                array( 'user' => 'User' ),
                array( 'student' => 'Student' )
            ),
            'user_status'   => array(
                0 => 'Tidak Aktif',
                1 => 'Aktif'
            )
        );

        $this->layout->content = \View::make('admin::user.profile',$data);
    }
}
