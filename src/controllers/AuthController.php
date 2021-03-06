<?php namespace Atlantis\Admin;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Atlantis\Core\Controller\BaseController;


class AuthController extends BaseController {

    /*******************************************************************************************************************
     * User Register [GET]
     *
     * @params $role - User role
     ******************************************************************************************************************/
    public function getRegister($role='admin::user'){
        #i: View scaffolding
        if($role == 'admin::user' && View::exists('user.register')) $role = 'user';

        #i: Loading view
        $this->layout->content = View::make($role.'.register');
    }


    /*******************************************************************************************************************
     * User Register [POST]
     *
     * @params $role - User role
     ******************************************************************************************************************/
    public function postRegister($role='admin::user'){
        $view = 'register';
        Input::flash();

        try{
            #e: Trigger user registering
            Event::fire('user.registering', array(Input::all(),$role));

            #i: Start database transaction
            DB::beginTransaction();

            #i: Register user
            $user = $this->auth->register(Input::only('email','first_name','last_name','password'));

            #i: Get registration activation
            $activation_code = $user->getActivationCode();

            #i: Preparing data
            $data = array(
                'id'                => $user->id,
                'full_name'         => $user->first_name . ' ' . $user->last_name,
                'email'             => $user->email,
                'activation_code'   => $activation_code,
                'activation_link'   => URL::to('user/activate', array($activation_code))
            );

            #i: Send activation email to user
            Mail::queue('admin::emails.auth.activation',$data,function($message) use ($data){
                $message
                    ->to($data['email'],$data['full_name'])
                    ->subject(trans('admin::user.activation_email_subject',$data));
            });

            #e: Trigger user registered
            $user = \User::find($user->id);
            Event::fire('user.registered', array($user,$role));

            #i: Change view to registered
            $view = 'registered';

            #i: Commit DB transaction
            DB::commit();

        }catch (\Exception $e){
            $data['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );

            #i: Rollback DB transaction
            DB::rollback();
        }

        #i: View scaffolding
        if(!View::exists($role . '.' . $view)) $role = 'admin::user';
        if($role == 'admin::user' && View::exists('user.'.$view)) $role = 'user';

        #i: Display view
        $this->layout->content = View::make($role . '.' . $view ,$data);
    }


    /*******************************************************************************************************************
     * User Activation [GET]
     *
     ******************************************************************************************************************/
    public function getActivation(){
        $data = Input::all();

        #i: Provide activation info
        $data['_status'] = array(
            'type' => 'info',
            'message' => trans('admin::user.activation_prompt')
        );

        #i: View scaffolding
        $view = 'admin::user.activation';
        if(View::exists('user.activation')) $view = 'user.activation';

        #i: Display view
        $this->layout->content = View::make($view,$data);
    }


    /*******************************************************************************************************************
     * User Activation [POST]
     *
     ******************************************************************************************************************/
    public function postActivation(){
        $data = Input::all();

        if(isset($data['login'])){
            try{
                #i: Find user by login
                $user = $this->auth->findUserByLogin($data['login']);

                #i: Prepare email variable if found
                $data = array(
                    'full_name'         => $user->first_name . ' ' . $user->last_name,
                    'email'             => $user->email,
                    'activation_link'   => URL::to('user/activate', array($user->getActivationCode()))
                );

                #i: Queue job for activation email
                Mail::queue('admin::emails.auth.activation',$data,function($message) use ($data){
                    $message
                        ->to($data['email'],$data['full_name'])
                        ->subject(trans('admin::user.activation_email_subject',$data));
                });

                #i: Response
                $data['_status'] = array(
                    'type' => 'success',
                    'message' => trans('admin::user.activation_resend_success', array('email' => $data['email']))
                );

            }catch(\Exception $e){
                $data['_status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }
        }

        #i: View scaffolding
        $view = 'admin::user.activation';
        if(View::exists('user.activation')) $view = 'user.activation';

        #i: Loading view
        $this->layout->content = View::make($view,$data);
    }


    /*******************************************************************************************************************
     * User Activate [GET]
     *
     ******************************************************************************************************************/
    public function getActivate($code=null){
        $get = Input::all();
        if( empty($code) ) $code = (isset($get['code']) ? $get['code'] : null);

        #i: Activate if activation code provide
        if(!empty($code)){
            try{
                #i: Find activation code by user
                $user = $this->auth->findUserByActivationCode($code);

                #i: Attempt to activate, throw error if unsuccess
                if($user->attemptActivation($code)){
                    $get['_status'] = array(
                        'type' => 'success',
                        'message' => trans('admin::user.activation_success')
                    );

                    #i: Inject role into params
                    $get['role'] = $this->realm->byUserId($user->id)->name or '';

                    #i: Redirect to login
                    return Redirect::action('Atlantis\Admin\AuthController@getLogin',$get);

                }else{
                    throw new \Exception(trans('admin::user.activation_error'));
                }

            }catch(\Exception $e){
                $get['_status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }

        }else{
            #i: Provide info if no activation code provide
            $get['_status'] = array(
                'type' => 'info',
                'message' => trans('admin::user.activate_prompt')
            );
        }

        #i: View scaffolding
        $view = 'admin::user.activate';
        if(View::exists('user.activate')) $view = 'user.activate';

        #i: Display view
        $this->layout->content = View::make($view,$get);
    }


    /*******************************************************************************************************************
     * User Recovery [GET]
     *
     * @params $login - Login credential
     * @param $code - Recovery code
     ******************************************************************************************************************/
    public function getRecovery($login=null,$code=null){
        $get = Input::get();
        if( empty($login) ) $login = (isset($get['login']) ? $get['login'] : null);
        if( empty($code) ) $code = (isset($get['code']) ? $get['code'] : null);

        #i: Activate if activation code provide
        if(!empty($login) && !empty($code)){
            try{
                #i: Get and check user
                $user = $this->auth->findUserByLogin($login);

                #i: Check activation code
                if( !$user->checkResetPasswordCode($code) ) throw new \Exception(trans('admin::user.recovery_password_error'));

                #i: Provide info on providing new login info
                $get['user_id'] = $user->id;
                $get['login'] = $login;
                $get['code'] = $code;
                $get['_status'] = array(
                    'type' => 'info',
                    'message' => trans('admin::user.recovery_password_prompt_new')
                );

            }catch(\Exception $e){
                $get['_status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }

        }else{
            #i: Provide info if no login & code provide
            $get['_status'] = array(
                'type' => 'info',
                'message' => trans('admin::user.recovery_password_prompt')
            );
        }

        #i: View scaffolding
        $view = 'admin::user.recovery';
        if(View::exists('user.recovery')) $view = 'user.recovery';

        #i: Display view
        $this->layout->content = View::make($view,$get);
    }


    /*******************************************************************************************************************
     * User Recovery [POST]
     *
     ******************************************************************************************************************/
    public function postRecovery(){
        $post = Input::all();

        try{
            #i: Get user
            $user = $this->auth->findUserByLogin($post['login']);

            #i: Reset code generation
            if( $user && empty($post['code']) ){
                #i: Get reset code for user
                $reset_code = $user->getResetPasswordCode();

                if($reset_code){
                    #i: Prepare email variable
                    $data = array(
                        'full_name' => $user->first_name . ' ' . $user->last_name,
                        'email'         => $user->email,
                        'reset_link'    => URL::to('user/recovery', array($user->email,$reset_code))
                    );

                    #i: Queue job for reset password email
                    Mail::queue('admin::emails.auth.recovery',$data,function($message) use ($data){
                        $message
                            ->to($data['email'],$data['full_name'])
                            ->subject(trans('admin::user.recovery_password_email_subject',$data));
                    });

                    #i: Response
                    $post['_status'] = array(
                        'type' => 'success',
                        'message' => trans('admin::user.recovery_password_sended')
                    );

                }else{
                    throw new Exception(trans('admin::user.activation_error'));
                }

            #i: Reset password
            }elseif( $user && !empty($post['code']) ){
                #i: Verify reset code
                if( $user->checkResetPasswordCode($post['code']) ){
                    #i: Resetting password
                    $user->attemptResetPassword($post['code'],$post['password']);

                    #i: Response
                    $post['_status'] = array(
                        'type' => 'success',
                        'message' => trans('admin::user.recovery_password_success')
                    );

                    #i: Inject role into params
                    $post['role'] = $this->realm->byUserId($user->id)->name or '';

                    #i: Redirect to login
                    return Redirect::action('Atlantis\Admin\AuthController@getLogin',$post);
                }
            }

        }catch(\Exception $e){
            $post['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        #i: View scaffolding
        $view = 'admin::user.recovery';
        if(View::exists('user.recovery')) $view = 'user.recovery';

        #i: Display view
        $this->layout->content = View::make($view,$post);
    }


    /*******************************************************************************************************************
     * User Login [GET]
     *
     * @params $role User role
     ******************************************************************************************************************/
    public function getLogin($role='admin::user'){
        $get = Input::all();

        #i: Get default home
        $home = Config::get('admin::admin.user_home','home');

        #i: View scaffolding
        if($role == 'admin::user' && View::exists('user.login')) $role = 'user';

        #i: Auth check
        if( $this->auth->check() ) return Redirect::to($role.'/'.$home);

        #i: Loading view
        $this->layout->content = View::make($role.'.login',$get);
    }


    /*******************************************************************************************************************
     * User Login [GET]
     *
     * @params $role User role
     ******************************************************************************************************************/
    public function postLogin($realm='user'){
        $post = Input::all();
        $home = Config::get('admin::admin.user_home','home');

        try{
            #i: Get credential
            $credential = Input::only('email','password');

            #i: Validate credentials
            $validation = \Validator::make($credential, array('email'=>'email'));

            if( $validation->passes() ){
                #e: Login event
                Event::fire('auth.login', array($realm,$credential));

                #i: Authenticate
                $granted = $this->auth->authenticate($credential,false);

            }else{
                #e: Alternative login event, do your custom user searching and return user object
                $user = Event::fire('auth.login.alternative', array($realm,$credential));

                #i: Get user email
                if($user[0]) $credential['email'] = $user[0]->email;

                #i: Authenticate
                $granted = $this->auth->authenticate($credential,false);
            }

            #i: Redirect to user home if granted
            if($granted){
                #i: Check user role
                $user_realm = $this->realm->current()->name;
                if( $user_realm ) $realm = $user_realm;

                #i: Redirect to home
                if( View::exists($realm.'/'.$home) ){
                    return Redirect::to($realm.'/'.$home);
                }else{
                    return Redirect::to('user/'.$home);
                }
            }

            #i: Safe exception
            throw new \Exception('Cannot authenticate user.');

        }catch ( \Exception $e){
            /** Sentry language translation */
            $params = [];
            $error_message = $e->getMessage();
            preg_match('/\[(\S+(?<!email|password))\]/',$error_message,$match);

            if(!empty($match)){
                $error_message = preg_replace('/\[(\S+(?<!email))\]/','[%email%]',$e->getMessage());
                $params = ['%email%'=>$match[1]];
            }

            $post['_status'] = array(
                'type' => 'error',
                'message' => app('atlantis.language')->lang($error_message,$params,'translate')
            );
        }

        #i: Return to login page with error if any
        $this->layout->content = View::make($realm . '.login', $post);
    }


    /*******************************************************************************************************************
     * User Logout
     *
     ******************************************************************************************************************/
    public function getLogout(){
        $this->auth->logout();
        return Redirect::to('public/page');
    }
}