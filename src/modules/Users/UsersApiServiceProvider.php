<?php namespace Modules\Users\Api;

use Atlantis\Api\ServiceProviderFactory;


class UsersApiServiceProvider extends ServiceProviderFactory {

    /** @var string API Description */
    protected $title = 'Users API';

    /** @var string Api name */
    protected $name = 'users';

}