<?php namespace Atlantis\View\Interfaces;


interface Realm {

    public function current();
    public function byUserId($user_id);

}