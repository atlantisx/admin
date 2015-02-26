<?php namespace Atlantis\User\Models;

use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class People extends Eloquent {
    /** @var array Hidden fields */
    protected $hidden = array('id','object','data_id','data_type');

    /** @var array Guarded fields */
    protected $guarded = array('id','user_id','object','data_id','data_type','updated_at','created_at','meta','data');

    /**
     * Boot model
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($people){
            if( $people->data ){
                $people->data()->delete();
            }
        });
    }


    /**
     * Relationship : User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('User');
    }


    /**
     * Polymorphic Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function data(){
        return $this->morphTo();
    }


    /**
     * Meta get attribute
     *
     * @param $value
     * @return mixed
     */
    public function getMetaAttribute($value){
        if( json_decode($value) ) {
            return json_decode($value);
        }else{
            return $value;
        }
    }


    /**
     * Meta set attribute
     *
     * @param $value
     */
    public function setMetaAttribute($value){
        $this->attributes['meta'] = json_encode($value);
    }


    /**
     * Birth date set attribute
     *
     * @param $value
     */
    public function setBirthDateAttribute($value){
        /** Set formatted date */
        $this->attributes['birth_date'] = Carbon::parse($value);
    }


    /**
     * Birth date get attribute
     *
     * @param $value
     * @return string
     */
    public function getBirthDateAttribute($value){
        /** @var $date Carbon date instance */
        $date = new Carbon($value);

        /** Return formatted date */
        return $date->format(Config::get('core::app.format.date'));
    }

}