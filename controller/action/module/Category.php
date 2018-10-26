<?php

namespace tlb\action\module;
use tlb\common\controller\Base;
use think\Db;
class Category extends Base
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

    //获取分类标签列表
    public function getCategoryList(){
        return Db::name('category')->select();
    }

}
