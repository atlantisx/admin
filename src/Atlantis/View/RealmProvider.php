<?php namespace Atlantis\View;

use Atlantis\View\Types\Realm;
use Atlantis\View\Interfaces\Realm as RealmInterface;


class RealmProvider implements RealmInterface {
    protected $app;
    protected $auth;

    public function __construct($app,$auth){
        $this->app = $app;
        $this->auth = $auth;
    }


    public function current(){
        #i: Get current user
        $user = $this->auth->getUser();

        #i: If user set, return the realm object
        if( isset($user) ){
            return $this->byUserId( $this->auth->getUser()->id );
        }

        return null;
    }


    public function byUserId($user_id){
        $user_realm = $this->base();

        #i: Find user based on Id
        $user = $this->auth->findUserById($user_id);

        #i: Checking user role
        $user_groups = $user->getGroups();

        if( $user_groups ){
            $user_realm = strtolower($user_groups->first()->name);
        }

        return new Realm($user_realm);
    }


    public function available(){
        // Check for available realm through user group
    }


    public function base(){
        return $this->app['config']->get('admin::admin.user_default_role','user');
    }

}