<?php

namespace tlb\action\module;
use tlb\common\controller\Base;
use think\Db;
class Download extends Base
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

    //创建资源信息
    public function createDown(){
        if(request()->isPost()){
            $data=input('post.');
            $data['last_update_time'] = date("Y-m-d H:i:s");
            $id = Db::name('file_downlod')->insert($data,false,true);
            if($id){
                $data['id'] = $id;
                return $data;
            }else{
                $this->error('新增失败，请稍后再试！');
            }
        }
    }
    
    //删除资源
    public function delDown(){
        if(request()->isPost()){
            $ids = input('ids/a');
            $ids = implode(",",$ids) ;

            $map['id']  = array('in',$ids);
            $r=Db::name("file_downlod")->where($map)->delete();
            if($r){
                //删除文件
                $files = input('files/a');
                $path = $_SERVER['DOCUMENT_ROOT'] . '/upload/download/';
                foreach ( $files as $item ) {
                    if(!empty($item)){
                        try{
                            unlink($path . $item);
                        }catch(Exception $e){}
                    }
                }
                return true;
            }else{
                return $this->error("删除失败！");
            }
        }
    }

    //修改资源信息
    public function updateDown(){
        if(request()->isPost()){
            $data=input('post.');
            $data['last_update_time'] = date("Y-m-d H:i:s");
            $r=Db::name("file_downlod")->update($data);
            if($r){
                return $data['last_update_time'];
            }else{
                return $this->error("修改失败！");
            }
        }
    }

    //查询资源信息数据集合
    public function getDownList(){
        return Db::name('file_downlod')->order('last_update_time desc')->select();
    }
}
