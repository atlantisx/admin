<?php namespace Atlantis\Admin;

use Atlantis\Atlantis;
use Cartalyst\Sentry\Facades\CI\Sentry;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller;
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
            #i: Register user
            $user = \Sentry::register(Input::only('email','first_name','last_name','password'));

            #i: Preparing data
            $data = array(
                'id'                => $user->id,
                'full_name'         => $user->first_name . ' ' . $user->last_name,
                'email'             => $user->email,
                'activation_code'   => $user->getActivationCode(),
                'activation_link'   => URL::to('user/activate', array($user->getActivationCode()))
            );

            #i: Send activation email to user
            Mail::queue('admin::emails.auth.activation',$data,function($message) use ($data){
                $message
                    ->to($data['email'],$data['full_name'])
                    ->subject(trans('admin::user.activation_email_subject',$data));
            });

            #e: Trigger user registered
            $user = \User::find($user->id);
            \Event::fire('user.registered', array($user,$role));

            #i: Change view to registered
            $view = 'registered';

        }catch (\Exception $e){
            $data['status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        #i: View scaffolding
        if(!View::exists($role . '.' . $view)) $role = 'admin::user';
        if($role == 'admin::user' && View::exists('user.'.$view)) $role = 'user';

        //[i] Display error
        $this->layout->content = View::make($role . '.' . $view ,$data);
    }


    public function getActivation(){
        $data = Input::all();

        //[i] Provide activation info
        $data['status'] = array(
            'type' => 'info',
            'message' => trans('admin::user.activation_prompt')
        );

        //[i] View scaffolding
        $view = 'admin::user.activation';
        if(View::exists('user.activation')) $view = 'user.activation';

        //[i] Display view
        $this->layout->content = View::make($view,compact('data'));
    }


    public function postActivation(){
        $data = Input::all();

        if(isset($data['login'])){
            try{
                //[i] Find user by login
                $user = \Sentry::findUserByLogin($data['login']);

                //[i] Prepare email variable if found
                $data = array(
                    'full_name'         => $user->first_name . ' ' . $user->last_name,
                    'email'             => $user->email,
                    'activation_link'   => URL::to('user/activate', array($user->getActivationCode()))
                );

                //[i] Queue job for activation email
                \Mail::queue('admin::emails.auth.activation',$data,function($message) use ($data){
                    $message
                        ->to($data['email'],$data['full_name'])
                        ->subject(trans('admin::user.activation_email_subject',$data));
                });

                //[i] Response
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


    public function getActivate($code=null){
        $get = Input::all();
        if( empty($code) ) $code = (isset($get['code']) ? $get['code'] : null);

        //[i] Activate if activation code provide
        if(!empty($code)){
            try{
                //[i] Find activation code by user
                $user = \Sentry::findUserByActivationCode($code);

                //[i] Attempt to activate, throw error if unsuccess
                if($user->attemptActivation($code)){
                    $get['status'] = array(
                        'type' => 'success',
                        'message' => trans('admin::user.activation_success')
                    );
                }else{
                    throw new Exception(trans('admin::user.activation_error'));
                }

            }catch(\Exception $e){
                $get['status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }
        }else{
            //[i] Provide info if no activation code provide
            $get['status'] = array(
                'type' => 'info',
                'message' => trans('admin::user.activate_prompt')
            );
        }

        //[i] View scaffolding
        $view = 'admin::user.activate';
        if(View::exists('user.activate')) $view = 'user.activate';

        //[i] Display view
        $this->layout->content = View::make($view,compact('get'));
    }


    public function getRecovery($login=null,$code=null){
        $get = Input::get();
        if( empty($login) ) $login = (isset($get['login']) ? $get['login'] : null);
        if( empty($code) ) $code = (isset($get['code']) ? $get['code'] : null);

        //[i] Activate if activation code provide
        if(!empty($login) && !empty($code)){
            try{
                //[i] Get and check user
                $user = \Sentry::findUserByLogin($login);

                //[i] Check activation code
                if( !$user->checkResetPasswordCode($code) ) throw new \Exception(trans('admin::user.recovery_password_error'));

                //[i] Provide info on providing new login info
                $get['user_id'] = $user->id;
                $get['login'] = $login;
                $get['code'] = $code;
                $get['status'] = array(
                    'type' => 'info',
                    'message' => trans('admin::user.recovery_password_prompt_new')
                );

            }catch(\Exception $e){
                $get['status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }

        }else{
            //[i] Provide info if no login & code provide
            $get['status'] = array(
                'type' => 'info',
                'message' => trans('admin::user.recovery_password_prompt')
            );
        }

        //[i] View scaffolding
        $view = 'admin::user.recovery';
        if(View::exists('user.recovery')) $view = 'user.recovery';

        //[i] Display view
        $this->layout->content = View::make($view,compact('get'));
    }


    public function postRecovery(){
        $post = Input::all();

        try{
            //[i] Get user
            $user = \Sentry::findUserByLogin($post['login']);

            //[i] Reset code generation
            if( $user && empty($post['code']) ){
                //[i] Get reset code for user
                $reset_code = $user->getResetPasswordCode();

                if($reset_code){
                    //[i] Prepare email variable
                    $data = array(
                        'full_name' => $user->first_name . ' ' . $user->last_name,
                        'email'         => $user->email,
                        'reset_link'    => URL::to('user/recovery', array($user->email,$reset_code))
                    );

                    //[i] Queue job for reset password email
                    \Mail::queue('admin::emails.auth.recovery',$data,function($message) use ($data){
                        $message
                            ->to($data['email'],$data['full_name'])
                            ->subject(trans('admin::user.recovery_password_email_subject',$data));
                    });

                    //[i] Response
                    $get['status'] = array(
                        'type' => 'success',
                        'message' => trans('admin::user.recovery_password_sended')
                    );
                }else{
                    throw new Exception(trans('admin::user.activation_error'));
                }

            //[i] Reset code verification
            }elseif( $user && !empty($post['code']) ){
                //[i] Verify reset code
                if( $user->checkResetPasswordCode($post['code']) ){
                    $user->attemptResetPassword($post['code'],$post['password']);

                    //[i] Response
                    $get['status'] = array(
                        'type' => 'success',
                        'message' => trans('admin::user.recovery_password_success')
                    );
                }
            }

        }catch(\Exception $e){
            $get['status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        //[i] View scaffolding
        $view = 'admin::user.recovery';
        if(View::exists('user.recovery')) $view = 'user.recovery';

        //[i] Display view
        $this->layout->content = View::make($view,compact('get'));
    }

    public function getLogin($role='admin::user'){
        $home = \Config::get('admin::admin.user_home','home');

        //[i] View scaffolding
        if($role == 'admin::user' && View::exists('user.login')) $role = 'user';

        //[i] Sentry auth check
        if( \Sentry::check() ) return Redirect::to($role.'/'.$home);

        //[i] Loading view
        $this->layout->content = View::make($role.'.login');
    }


    public function postLogin($role='user'){
        $post = Input::flash();
        $home = \Config::get('admin::admin.user_home','home');

        try{
            #i: Authenticate
            $credential = Input::only('email','password');
            $granted = \Sentry::authenticate($credential,false);

            #i: Redirect to user home if granted
            if($granted){
                #i: Check user role
                $user_role = \Atlantis::users()->getUserRoleById(\Sentry::getUser()->id)->name;
                if( $user_role ) $role = $user_role;

                #i: Redirect to home
                return Redirect::to($role.'/'.$home);
            }

        }catch ( \Exception $e){
            $post['status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        #i: Return to login page with error if any
        $this->layout->content = View::make($role . '.login', $post);
    }


    public function getLogout(){
        \Sentry::logout();
        return Redirect::to('public/page');
    }
}