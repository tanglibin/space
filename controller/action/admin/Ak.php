<?php

namespace tlb\action\admin;
use tlb\common\controller\AdminBase;
use think\Db;
class Ak extends AdminBase
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

    //ak校验
    private function validAk($data){
        $data=input('post.');
        if(empty($data['key'])){
            $this->error('Key不能为空！');
        }elseif(empty($data['value'])){
            $this->error('Value不能为空！');
        }elseif(empty($data['name'])){
            $this->error('名称不能为空！');
        }

        if(isset($data['id'])){
            $map['id'] = array('neq',$data['id']);
        }

        $map['key'] = $data['key'];
        $akinfo=Db::name('ak')->where($map)->find();
        if($akinfo){
            $this->error('Key已存在！');
        }
        unset($map['key']);

        $map['value'] = $data['value'];
        $akinfo=Db::name('ak')->where($map)->find();
        if($akinfo){
            $this->error('Value已存在！');
        }
        return true;
    }

    //创建AK
    public function createAk(){
        if(request()->isPost()){
            $data=input('post.');
            if($this->validAk($data)){
                $data['last_update_time'] = date("Y-m-d H:i:s");
                $id = Db::name('ak')->insert($data,false,true);
                if($id){
                    $data['id'] = $id;
                    return $data;
                }else{
                    $this->error('新增失败，请稍后再试！');
                }
            }
        }
    }
    
    //删除AK
    public function delAk(){
        if(request()->isPost()){
            $ids = input('id/a');
            $ids = implode(",",$ids) ;

            $map['id']  = array('in',$ids);
            $r=Db::name("ak")->where($map)->delete();
            if($r){
                return true;
            }else{
                return $this->error("删除失败！");
            }
        }
    }

    //修改AK
    public function updateAk(){
        if(request()->isPost()){
            $data=input('post.');
            if($this->validAk($data)){
                $r=Db::name("ak")->update($data);
                if($r){
                    return true;
                }else{
                    return $this->error("修改失败！");
                }
            }
        }
    }

    //查询AK数据集合
    public function getAkList(){
        return Db::name('ak')->order('last_update_time desc')->select();
    }
}
