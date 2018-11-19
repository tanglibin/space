<?php

namespace tlb\action\admin;
use think\Controller;
use think\Db;
class Login extends Controller
{
    //object 对象实例
    private static $instance;
	
	//单例模式	
	public static function getInstance(){    
        if (!(self::$instance instanceof self))  
        {  
            self::$instance = new self();  
        }  
        return self::$instance;  
    }

    //登录
    public function login(){
        if(request()->isPost()){
            $data=input('post.');
            $user_info=Db::name('user')->where(['username'=>$data['username'], 'password'=>$data['password'], 'user_type'=>1])->find();
            if($user_info){
                session('admin_username', $user_info['username']);
                return true;
            }
            $this->error('用户名或密码错误');
        }
    }

    //注销
    public function logout(){
        session('admin_username', null);
    }

}
