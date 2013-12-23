<?php


class AtlantisSeeder extends Seeder {

    public function run(){
        if( class_exists('code',false) ){
            //[i] Roles Array
            $user_roles = array(
                array('category'=>'user.role', 'name'=>'user', 'value'=>'User'),
                array('category'=>'user.role', 'name'=>'staff', 'value'=>'Staff'),
                //todo: To be remove on deploy
                array('category'=>'user.role', 'name'=>'student', 'value'=>'Student'),
            );


            //[i] Code : Roles seeding
            $user = Code::create(array('category'=>'user', 'name'=>'user', 'value'=>'User Dictionary'));
            foreach($user_roles as $role){
                $user->children()->create($role);
            }
        }
    }

}