<?php

namespace Modules\Users\Api\V1\Rpc\User;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Atlantis\Api\Controller\BaseController;


class UserController extends BaseController{

    public function methodChangeEmail()
    {
        $post = Input::all();
        $params = $post['params'];
        $this->result['id'] = $post['id'];

        /** Email validation */
        $validator = Validator::make(
            array('id'=>$params['id'], 'email'=>$params['email']),
            array('id'=>'required','email'=>'required|email|unique:users')
        );


        try{
            if ($validator->passes()) {
                /** Find user by email */
                $user = \User::find($params['id']);
                $user->email = $params['email'];
                $user->save();

                $this->result['result'] = 'Successfully changed email!';

            } else {
                $this->result['error'] = 'Error while updating email!';
            }

        }catch (Exception $e){
            $this->result['error'] = $e->getMessage();

        }

        return Response::json($this->result);
    }

} 