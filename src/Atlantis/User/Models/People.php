<?php namespace Atlantis\User\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class People extends Eloquent {
    protected $hidden = array('id','object','data_id','data_type');
    protected $guarded = array('id','user_id','object','data_id','data_type','updated_at','created_at','meta','data');

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($people){
            if( $people->data ){
                $people->data()->delete();
            }
        });
    }

    #i: Relationship : User
    public function user(){
        return $this->belongsTo('User');
    }

    #i: Polymorphic Relationship
    public function data(){
        return $this->morphTo();
    }

    public function getMetaAttribute($value){
        if( json_decode($value) ) {
            return json_decode($value);
        }else{
            return $value;
        }
    }

    public function setMetaAttribute($value){
        $this->attributes['meta'] = json_encode($value);
    }
}