<?php namespace Modules\Users\Api\V1\Rest\User;

use Atlantis\Api\Rest\ResourceController;


class UserController extends ResourceController{

    public function index(){
        $users = \User::all();
        $users->load('profile');

        return $users;
    }


    public function show($id){
        $get = \Input::all();

        //@info Search user
        $user = \User::find($id);

        //@info Return user
        if($user){
            $user->load('profile');
            if( !empty($get['access']) ){
                if( $get['access'] == 'simple' ){
                    $user->_groups = $user->groups->lists('id');
                    $user->_roles = $user->roles->lists('id');

                }else{
                    $user->load('groups');
                    $user->load('roles');
                }
            }

            return $user;
        }

        return \Response::json(array('Error in query'),400);
    }


    public function update($id,$data=null){
        $put = \Input::all();

        try{
            //@info Search user
            $user = \User::find($id);

            //@info Update user
            if($user){
                $user->fill($put);
                if($user->profile){
                    //@todo Uncommon properties should be move to modules. eg: profile.idno_ic > profile.data.idno_ic
                    $validator = \Validator::make($put,array('profile.idno_ic'=>'unique:peoples,idno_ic,'.$user->id.',user_id'));
                    if($validator->fails()) throw new \Exception($validator->messages()->first());
                    
                    $user->profile->fill($put['profile']);
                }else{
                    $profile = new \People($put['profile']);
                    $user->profile()->save($profile);
                }
                $user->push();
            }
            $put = array_merge($put,array('status'=>'success','message'=>'Successfully update user!'));

        }catch (Exception $e){
            $put = array_merge($put,array('status'=>'error','message'=>$e->getMessage()));
        }

        return \Response::json($put);
    }


    public function destroy($id){
        //@info Search user
        $user = \User::find($id);

        if($user){
            $user->delete();
            return \Response::json(array('message'=>'Successfully delete user!'));
        }else{
            return \Response::json(array('Error in query'),400);
        }
    }

}