<?php namespace Atlantis\Admin;


class UserController extends BaseController {
    protected $layout = 'admin::layouts.common';

    public function getHome(){
        $this->layout->content = \View::make('admin::user.home');
    }
}
