<?php namespace Atlantis\User\Models;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Entrust\HasRole;


class User extends \Eloquent implements UserInterface, RemindableInterface {
    /**
     * Entrust traits
     */
    use HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Append custom attribute
     *
     * @var array
     */
    protected $appends = array('full_name');

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password','activation_code','persist_code','reset_password_code');

    /**
     * White-list
     *
     * @var array
     */
    protected $fillable = array('first_name','last_name');

    /**
     * Rules
     *
     * @var array
     */
    public static $rules = array
    (
        'first_name' => 'required',
        'last_name' => 'required'
    );


    /**
     * Boot
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($user){
            if( $user->profile->data ) $user->profile->data()->delete();
            $user->profile()->delete();
            $user->groups()->detach();
            $user->roles()->detach();
        });
    }

    /**
     * Profile relationship
     *
     * @return string
     */
    public function profile(){
        return $this->hasOne('People');
    }

    /**
     * Group relationship
     *
     * @return string
     */
    public function groups()
    {
        return $this->belongsToMany('Group', 'users_groups');
    }

    /**
     * name attribute accessor
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * full_name attribute accessor
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        $middle = ' ';

        if( isset($this->profile) ){
            $middle_gender = ( $this->profile->gender == 'male' ? ' Bin ' : ' Binti ');
            if( !empty($this->last_name) ) $middle = $middle_gender;
        }

        return $this->getAttribute('first_name').$middle.$this->getAttribute('last_name');
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getRememberToken(){
        return $this->remember_token;
    }

    /**
     *
     *
     */
    public function setRememberToken($value){
        $this->remember_token = $value;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getRememberTokenName(){
        return 'remember_token';
    }

}