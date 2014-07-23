<?php namespace Users\Rpc\V1;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Dingo\Api\Routing\Controller;

class UserController extends Controller{

    public function actionChangeEmail($id)
    {
        $post = Input::all();

        //@info Email validation
        $validator = Validator::make(
            array('email'=>$post['email']),
            array('email'=>'required|email|unique:users')
        );

        try{
            if ($validator->passes()) {
                //@info Find user by email
                $user = \Sentry::find($id);
                $user->email = $post['email'];
                $user->save();

                $post = array('status'=>'success','message'=>'Successfully changed email!');
            } else {
                $post = array('status'=>'error','message'=>'Error while updating email!');
            }

        }catch (Exception $e){
            return Response::json(array('Error in query'),400);
        }

        return Response::json($post);
    }

} 