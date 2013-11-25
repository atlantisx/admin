<?php namespace Atlantis\Admin;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;



class AuthController extends BaseController {
    protected $layout = 'admin::layouts.common';


    public function getRegister($role='admin::user'){
        //[i] View scaffolding
        if($role == 'admin::user' && View::exists('user.register')) $role = 'user';

        //[i] Loading view
        $this->layout->content = View::make($role.'.register');
    }


    public function postRegister($role='admin::user'){
        $view = 'register';
        $data = Input::flash();

        try{
            //[i] Register user
            $user = \Sentry::register(Input::only('email','first_name','last_name','password'));

            //[i] Preparing data
            $data = array(
                'id'            => $user->id,
                'name'          => $user->first_name . ' ' . $user->last_name,
                'email'         => $user->email,
                'activation_code' => $user->getActivationCode()
            );

            //[i] Send activation email to user
            Mail::queue('admin::emails.auth.activation',$data,function($message) use ($data){
                $message->to($data['email'],$data['name'])->subject(trans('admin::user.activation_email_subject',$data));
            });

            //[i] Change view to registered
            $view = 'registered';

        }catch (\Exception $e){
            $data['status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        //[i] View scaffolding
        if(!View::exists($role . '.' . $view)) $role = 'admin::user';

        if($role == 'admin::user' && View::exists('user.'.$view)) $role = 'user';

        //[i] Display error
        $this->layout->content = View::make($role . '.' . $view ,$data);
    }


    public function getActivation($code=null){
        $data = Input::get();

        if(!empty($code)){
            try{
                $user = \Sentry::findUserByActivationCode($code);

                if($user->attemptActivation($code)){
                    $data['status'] = array(
                        'type' => 'success',
                        'message' => trans('admin::user.activation_success')
                    );
                }else{
                    throw new Exception(trans('admin::user.activation_error'));
                }
            }catch(\Exception $e){
                $data['status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }
        }

        //[i] View scaffolding
        $view = 'admin::user.activation';
        if(View::exists('user.activation')) $view = 'user.activation';

        //[i] Display view
        $this->layout->content = View::make($view,compact('data'));
    }


    public function postActivation(){
        $data = Input::get();

        if(isset($data['login'])){
            try{
                $user = \Sentry::findUserByLogin($data['login']);

                $data = array(
                    'id'            => $user->id,
                    'name'          => $user->first_name . ' ' . $user->last_name,
                    'email'         => $user->email,
                    'activation_code' => $user->getActivationCode()
                );

                \Mail::queue('admin::emails.auth.activation',$data,function($message) use ($data){
                    $message->to($data['email'],$data['name'])->subject(trans('admin::user.activation_email_subject',$data));
                });

                $data['status'] = array(
                    'type' => 'success',
                    'message' => trans('admin::user.activation_resend_success', array('email' => $data['email']))
                );

            }catch(\Exception $e){
                $data['status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }
        }

        //[i] View scaffolding
        $view = 'admin::user.activation';
        if(View::exists('user.activation')) $view = 'user.activation';

        //[i] Loading view
        $this->layout->content = View::make($view,compact('data'));
    }


    public function getLogin($role='admin::user'){
        //[i] View scaffolding
        if($role == 'admin::user' && View::exists('user.login')) $role = 'user';

        //[i] Sentry auth check
        if( \Sentry::check() ) return Redirect::to($role.'/home');

        //[i] Loading view
        $this->layout->content = View::make($role.'.login');
    }


    public function postLogin($role='user'){
        $post = Input::flash();

        try{
            //[i] Authenticate
            $credential = Input::only('email','password');
            $granted = \Sentry::authenticate($credential,false);

            //[i] Redirect to student home if granted
            if($granted){
                return Redirect::to($role.'/home');
            }

        }catch ( \Exception $e){
            $post['status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        //[i] Return to login page with error if any
        $this->layout->content = View::make($role . '.login',$post);
    }


    public function getLogout(){
        \Sentry::logout();
        return Redirect::to('public/page');
    }
}