<?php namespace Atlantis\Admin;


class AuthController extends Controller {
    protected $layout = 'layouts.common';


    public function getRegister($role='user'){
        $this->layout->content = View::make($role.'.register');
    }


    public function postRegister($role='user'){
        $view = 'register';
        $data = Input::flash();

        try{
            //[i] Register user
            $user = Sentry::register(Input::only('email','first_name','last_name','password'));

            $data = array(
                'id'            => $user->id,
                'name'          => $user->first_name . ' ' . $user->last_name,
                'email'         => $user->email,
                'activation_code' => $user->getActivationCode()
            );

            Mail::queue('emails.auth.activation',$data,function($message) use ($data){
                $message->to($data['email'],$data['name'])->subject('Account Activation');
            });

            $view = 'registered';

        }catch (\Exception $e){
            $data['status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        //[i] Display error
        $this->layout->content = View::make($role . '.' . $view ,$data);
    }


    public function getActivation($code=null){
        $data = Input::get();

        if(!empty($code)){
            try{
                $user = Sentry::findUserByActivationCode($code);

                if($user->attemptActivation($code)){
                    $data['status'] = array(
                        'type' => 'success',
                        'message' => 'Pengaktifan anda berjaya sila login ke sistem untuk selanjutnya.'
                    );
                }else{
                    throw new Exception('Pengaktifan anda tidak berjaya.');
                }
            }catch(\Exception $e){
                $data['status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }
        }

        //[i] Display view
        $this->layout->content = View::make('user.activation',compact('data'));
    }


    public function postActivation(){
        $data = Input::get();

        if(isset($data['login'])){
            try{
                $user = Sentry::findUserByLogin($data['login']);

                $data = array(
                    'id'            => $user->id,
                    'name'          => $user->first_name . ' ' . $user->last_name,
                    'email'         => $user->email,
                    'activation_code' => $user->getActivationCode()
                );

                Mail::queue('emails.auth.activation',$data,function($message) use ($data){
                    $message->to($data['email'],$data['name'])->subject('Account Activation');
                });

                $data['status'] = array(
                    'type' => 'success',
                    'message' => 'Kod aktivasi telah berjaya di hantar semula ke email ' . $data['email']
                );

            }catch(\Exception $e){
                $data['status'] = array(
                    'type' => 'error',
                    'message' => $e->getMessage()
                );
            }
        }

        $this->layout->content = View::make('user.activation',compact('data'));
    }


    public function getLogin($role='user'){
        if( Sentry::check() ) return Redirect::to($role.'/home');
        $this->layout->content = View::make($role.'.login');
    }


    public function postLogin($role='user'){
        $post = Input::flash();

        try{
            //[i] Authenticate
            $credential = Input::only('email','password');
            $granted = Sentry::authenticate($credential,false);

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
        Sentry::logout();
        return Redirect::to('public/page');
    }
}