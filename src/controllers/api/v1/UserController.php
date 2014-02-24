<?php namespace Atlantis\Admin\Api\V1;


class UserController extends \BaseController{

    public function index(){
        $users = \User::all();
        $users->load('profile');

        return $users;
    }

    public function show($id){
        //[i] Search user
        $user = \User::find($id);

        //[i] Return user
        if($user){
            $user->load('profile');
            return $user;
        }

        return \Response::json(array('Error in query'),400);
    }

    public function update($id){
        $put = \Input::all();

        try{
            //[i] Search user
            $user = \User::find($id);

            //[i] Update user
            if($user){
                $user->fill($put);
                if($user->profile){
                    $user->profile->fill($put['profile']);
                }else{
                    $profile = new \People($put['profile']);
                    $user->profile()->save($profile);
                }
                $user->push();
            }
            $put = array_merge($put,array('status'=>'success','message'=>'Successfully update user!'));

        }catch (Exception $e){
            //return \Response::json(array('Error in query!'),400);
            $put = array_merge($put,array('status'=>'error','message'=>$e->getMessage()));
        }

        return \Response::json($put);
    }

    public function destroy($id){
        //[i] Search user
        $user = \User::find($id);

        if($user){
            $user->delete();
            return \Response::json(array('message'=>'Successfully delete user!'));
        }else{
            return \Response::json(array('Error in query'),400);
        }
    }

    public function changeEmail(){
        $post = \Input::all();

        //[i] Email validation
        $validator = \Validator::make(
            array('email'=>$post['email']),
            array('email'=>'required|email|unique:users')
        );

        try{
            if( $validator->passes() ){
                //[i] Find user by email
                $user = \Sentry::getUser();
                $user->email = $post['email'];
                $user->save();

                $post = array('status'=>'success','message'=>'Successfully changed email!');
            }else{
                $post = array('status'=>'error','message'=>'Error while updating email!');
            }
        }catch (Exception $e){
            return \Response::json(array('Error in query'),400);
        }

        return \Response::json($post);
    }

    public function missingMethod($parameters = array()){

    }

}