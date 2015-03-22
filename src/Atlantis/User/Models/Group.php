<?php

namespace Atlantis\User\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Group extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * Rules
     *
     * @var array
     */
    public static $rules = array
    (
        'name' => 'required'
    );


    /**
     * User relationship
     *
     * @var array
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany('User', 'users_groups');
    }
}