<?php

namespace tlb\action\admin;
use tlb\common\controller\Base;
use think\Db;
class Login extends Base
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


    public function login(){
        if(request()->isPost()){
            $data=input('post.');
            return true;
        }
    }

}
